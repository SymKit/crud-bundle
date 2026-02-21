<?php

declare(strict_types=1);

namespace Symkit\CrudBundle\Tests\Unit\Crud\Event;

use PHPUnit\Framework\TestCase;
use Symkit\CrudBundle\Crud\Event\CrudEvent;

final class CrudEventTest extends TestCase
{
    public function testGetEntityReturnsConstructedEntity(): void
    {
        $entity = new \stdClass();
        $event = new CrudEvent($entity);

        self::assertSame($entity, $event->getEntity());
    }

    public function testGetFormAndGetRequestDefaultToNull(): void
    {
        $event = new CrudEvent(new \stdClass());

        self::assertNull($event->getForm());
        self::assertNull($event->getRequest());
    }
}
