<?php

declare(strict_types=1);

namespace Symkit\CrudBundle\Crud\Persistence\Manager;

use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symkit\CrudBundle\Contract\CrudPersistenceHandlerInterface;
use Symkit\CrudBundle\Contract\CrudPersistenceManagerInterface;
use Symkit\CrudBundle\Crud\Enum\CrudEvents;
use Symkit\CrudBundle\Crud\Event\CrudEvent;

final class CrudPersistenceManager implements CrudPersistenceManagerInterface
{
    public function __construct(
        private readonly CrudPersistenceHandlerInterface $handler,
        private readonly EventDispatcherInterface $eventDispatcher,
    ) {
    }

    public function persist(object $entity, ?FormInterface $form = null, ?Request $request = null): void
    {
        $this->eventDispatcher->dispatch(new CrudEvent($entity, $form, $request), CrudEvents::PRE_PERSIST);

        $this->handler->persist($entity);
        $this->handler->flush();

        $this->eventDispatcher->dispatch(new CrudEvent($entity, $form, $request), CrudEvents::POST_PERSIST);
    }

    public function update(object $entity, ?FormInterface $form = null, ?Request $request = null): void
    {
        $this->eventDispatcher->dispatch(new CrudEvent($entity, $form, $request), CrudEvents::PRE_UPDATE);

        $this->handler->update($entity);
        $this->handler->flush();

        $this->eventDispatcher->dispatch(new CrudEvent($entity, $form, $request), CrudEvents::POST_UPDATE);
    }

    public function delete(object $entity, ?Request $request = null): void
    {
        $this->eventDispatcher->dispatch(new CrudEvent($entity, null, $request), CrudEvents::PRE_DELETE);

        $this->handler->delete($entity);
        $this->handler->flush();

        $this->eventDispatcher->dispatch(new CrudEvent($entity, null, $request), CrudEvents::POST_DELETE);
    }
}
