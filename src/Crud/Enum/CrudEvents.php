<?php

declare(strict_types=1);

namespace Symkit\CrudBundle\Crud\Enum;

final class CrudEvents
{
    /**
     * Dispatched before a new entity is persisted.
     */
    public const PRE_PERSIST = 'crud.pre_persist';

    /**
     * Dispatched after a new entity is persisted.
     */
    public const POST_PERSIST = 'crud.post_persist';

    /**
     * Dispatched before an existing entity is updated.
     */
    public const PRE_UPDATE = 'crud.pre_update';

    /**
     * Dispatched after an existing entity is updated.
     */
    public const POST_UPDATE = 'crud.post_update';

    /**
     * Dispatched before an entity is deleted.
     */
    public const PRE_DELETE = 'crud.pre_delete';

    /**
     * Dispatched after an entity is deleted.
     */
    public const POST_DELETE = 'crud.post_delete';

    /**
     * Dispatched when the list query is being built.
     */
    public const LIST_QUERY = 'crud.list_query';
}
