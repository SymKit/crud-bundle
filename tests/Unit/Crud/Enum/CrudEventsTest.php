<?php

declare(strict_types=1);

namespace Symkit\CrudBundle\Tests\Unit\Crud\Enum;

use PHPUnit\Framework\TestCase;
use Symkit\CrudBundle\Crud\Enum\CrudEvents;

final class CrudEventsTest extends TestCase
{
    public function testConstantsHaveExpectedValues(): void
    {
        self::assertSame('crud.pre_persist', CrudEvents::PRE_PERSIST);
        self::assertSame('crud.post_persist', CrudEvents::POST_PERSIST);
        self::assertSame('crud.pre_update', CrudEvents::PRE_UPDATE);
        self::assertSame('crud.post_update', CrudEvents::POST_UPDATE);
        self::assertSame('crud.pre_delete', CrudEvents::PRE_DELETE);
        self::assertSame('crud.post_delete', CrudEvents::POST_DELETE);
        self::assertSame('crud.list_query', CrudEvents::LIST_QUERY);
    }
}
