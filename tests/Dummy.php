<?php

namespace Oddvalue\EloquentSortable\Tests;

class Dummy extends \Illuminate\Database\Eloquent\Model implements \Oddvalue\EloquentSortable\Sortable
{
    use \Oddvalue\EloquentSortable\SortableTrait;

    protected $table = 'dummies';

    protected $guarded = [];

    public $timestamps = false;

    public array $sortable = [
        'order_column' => 'order_column',
        'sort_when_deleting' => true,
    ];

    protected $casts = [
        'order_column' => 'integer',
        'original_position' => 'integer',
    ];
}
