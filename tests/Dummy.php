<?php

namespace Oddvalue\EloquentSortable\Tests;

use Illuminate\Database\Eloquent\Model;
use Oddvalue\EloquentSortable\Sortable;
use Oddvalue\EloquentSortable\SortableTrait;

class Dummy extends Model implements Sortable
{
    use SortableTrait;

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
