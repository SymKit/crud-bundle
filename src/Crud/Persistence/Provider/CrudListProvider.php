<?php

declare(strict_types=1);

namespace Symkit\CrudBundle\Crud\Persistence\Provider;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symkit\CrudBundle\Contract\CrudListProviderInterface;
use Symkit\CrudBundle\Crud\Enum\CrudEvents;
use Symkit\CrudBundle\Crud\Event\CrudListQueryEvent;

final class CrudListProvider implements CrudListProviderInterface
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly EventDispatcherInterface $eventDispatcher,
    ) {
    }

    /** @param array<string, mixed> $filters
     * @param list<string> $searchFields
     *
     * @return Paginator<object>
     */
    public function getEntities(
        string $entityClass,
        array $filters,
        array $searchFields,
        ?string $sortBy,
        string $sortDirection,
        int $page,
        int $limit,
    ): Paginator {
        $qb = $this->entityManager->getRepository($entityClass)->createQueryBuilder('e');

        $this->eventDispatcher->dispatch(new CrudListQueryEvent($qb, $entityClass, $filters), CrudEvents::LIST_QUERY);

        $query = $filters['q'] ?? null;

        if ($query && !empty($searchFields)) {
            $orX = $qb->expr()->orX();
            foreach ($searchFields as $field) {
                $orX->add($qb->expr()->like("e.{$field}", ':query'));
            }
            $qb->andWhere($orX)->setParameter('query', '%'.$query.'%');
        }

        if ($sortBy) {
            $qb->orderBy('e.'.$sortBy, $sortDirection);
        }

        $query = $qb->getQuery();

        $paginator = new Paginator($query);
        $paginator->getQuery()
            ->setFirstResult($limit * ($page - 1))
            ->setMaxResults($limit)
        ;

        return $paginator;
    }
}
