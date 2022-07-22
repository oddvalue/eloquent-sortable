<?php

use Oddvalue\EloquentSortable\Tests\Dummy;

it('can_be_move_records', function (int $from, int $to, array $expected) {
    $featuredProducts = Dummy::all();

    $this->assertEquals(range(1, 10), $featuredProducts->pluck('order_column')->all());

    $featuredProducts->firstWhere('order_column', $from)->moveTo($to);

    $this->assertEquals($expected, $featuredProducts->fresh()->pluck('order_column')->all());
})->with([
    [1, 2,  [2, 1, 3, 4, 5, 6, 7, 8, 9, 10]],
    [2, 6,  [1, 6, 2, 3, 4, 5, 7, 8, 9, 10]],
    [3, 10, [1, 2, 10, 3, 4, 5, 6, 7, 8, 9]],
    [4, 1,  [2, 3, 4, 1, 5, 6, 7, 8, 9, 10]],
    [5, 5,  [1, 2, 3, 4, 5, 6, 7, 8, 9, 10]],
    [6, 10, [1, 2, 3, 4, 5, 10, 6, 7, 8, 9]],
    [7, 1,  [2, 3, 4, 5, 6, 7, 1, 8, 9, 10]],
    [8, 5,  [1, 2, 3, 4, 6, 7, 8, 5, 9, 10]],
    [9, 3,  [1, 2, 4, 5, 6, 7, 8, 9, 3, 10]],
    [10, 1, [2, 3, 4, 5, 6, 7, 8, 9, 10, 1]],
]);

it('can_move_items_between', function (int $from, array $to, array $expected) {
    $featuredProducts = Dummy::all();

    [$before, $after] = $to;

    $featuredProducts->firstWhere('order_column', $from)
        ->moveBetween(
            $featuredProducts->firstWhere('order_column', $before),
            $featuredProducts->firstWhere('order_column', $after)
        );

    $this->assertEquals($expected, $featuredProducts->fresh()->pluck('order_column')->all());
})->with([
    [1,  [1, 2],     [1, 2, 3, 4, 5, 6, 7, 8, 9, 10]],
    [2,  [5, 6],     [1, 5, 2, 3, 4, 6, 7, 8, 9, 10]],
    [3,  [10, null], [1, 2, 10, 3, 4, 5, 6, 7, 8, 9]],
    [4,  [null, 1],  [2, 3, 4, 1, 5, 6, 7, 8, 9, 10]],
    [5,  [4, 5],     [1, 2, 3, 4, 5, 6, 7, 8, 9, 10]],
    [6,  [10, null], [1, 2, 3, 4, 5, 10, 6, 7, 8, 9]],
    [7,  [null, 1],  [2, 3, 4, 5, 6, 7, 1, 8, 9, 10]],
    [8,  [4, 5],     [1, 2, 3, 4, 6, 7, 8, 5, 9, 10]],
    [9,  [10, null], [1, 2, 3, 4, 5, 6, 7, 8, 10, 9]],
    [10, [1, null],  [1, 3, 4, 5, 6, 7, 8, 9, 10, 2]],
]);

it('can_move_items_before', function ($from, $to, $expected) {
    $featuredProducts = Dummy::all();

    $featuredProducts->firstWhere('order_column', $from)->moveBefore($featuredProducts->firstWhere('order_column', $to));

    $this->assertEquals($expected, $featuredProducts->fresh()->pluck('order_column')->all());
})->with([
    [1, 2,  [1, 2, 3, 4, 5, 6, 7, 8, 9, 10]],
    [2, 6,  [1, 5, 2, 3, 4, 6, 7, 8, 9, 10]],
    [3, 10, [1, 2, 9, 3, 4, 5, 6, 7, 8, 10]],
    [4, 1,  [2, 3, 4, 1, 5, 6, 7, 8, 9, 10]],
    [5, 5,  [1, 2, 3, 4, 5, 6, 7, 8, 9, 10]],
    [6, 8,  [1, 2, 3, 4, 5, 7, 6, 8, 9, 10]],
    [7, 2,  [1, 3, 4, 5, 6, 7, 2, 8, 9, 10]],
    [8, 5,  [1, 2, 3, 4, 6, 7, 8, 5, 9, 10]],
    [9, 3,  [1, 2, 4, 5, 6, 7, 8, 9, 3, 10]],
    [10, 1, [2, 3, 4, 5, 6, 7, 8, 9, 10, 1]],
]);

/**
 * @dataProvider itemsToMoveAfter
 */
it('can_move_items_after', function ($from, $to, $expected) {
    $featuredProducts = Dummy::all();

    $featuredProducts->firstWhere('order_column', $from)->moveAfter($featuredProducts->firstWhere('order_column', $to));

    $this->assertEquals($expected, $featuredProducts->fresh()->pluck('order_column')->all());
})->with([
    [1, 2,  [2, 1, 3, 4, 5, 6, 7, 8, 9, 10]],
    [2, 6,  [1, 6, 2, 3, 4, 5, 7, 8, 9, 10]],
    [3, 10, [1, 2, 10, 3, 4, 5, 6, 7, 8, 9]],
    [4, 1,  [1, 3, 4, 2, 5, 6, 7, 8, 9, 10]],
    [5, 5,  [1, 2, 3, 4, 5, 6, 7, 8, 9, 10]],
    [6, 8,  [1, 2, 3, 4, 5, 8, 6, 7, 9, 10]],
    [7, 2,  [1, 2, 4, 5, 6, 7, 3, 8, 9, 10]],
    [8, 5,  [1, 2, 3, 4, 5, 7, 8, 6, 9, 10]],
    [9, 3,  [1, 2, 3, 5, 6, 7, 8, 9, 4, 10]],
    [10, 1, [1, 3, 4, 5, 6, 7, 8, 9, 10, 2]],
]);
