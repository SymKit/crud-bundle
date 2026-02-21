<?php

declare(strict_types=1);

namespace Symkit\CrudBundle\Crud\Event;

use Doctrine\ORM\QueryBuilder;
use Symfony\Contracts\EventDispatcher\Event;

final class CrudListQueryEvent extends Event
{
    /** @param array<string, mixed> $filters */
    public function __construct(
        private readonly QueryBuilder $queryBuilder,
        private readonly string $entityClass,
        private readonly array $filters,
    ) {
    }

    public function getQueryBuilder(): QueryBuilder
    {
        return $this->queryBuilder;
    }

    public function getEntityClass(): string
    {
        return $this->entityClass;
    }

    /** @return array<string, mixed> */
    public function getFilters(): array
    {
        return $this->filters;
    }
}
