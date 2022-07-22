<?php

namespace Oddvalue\EloquentSortable\Tests;

class Dummy extends \Illuminate\Database\Eloquent\Model implements \Oddvalue\EloquentSortable\Sortable
{
    use \Oddvalue\EloquentSortable\SortableTrait;

    protected $table = 'dummies';

    protected $guarded = [];

    public $timestamps = false;
}
