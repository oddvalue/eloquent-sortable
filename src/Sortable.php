<?php

namespace Oddvalue\EloquentSortable;

interface Sortable extends \Spatie\EloquentSortable\Sortable
{
    /**
     * Determine if the siblings should resorted when deleting.
     */
    public function shouldSortWhenDeleting(): bool;
}
