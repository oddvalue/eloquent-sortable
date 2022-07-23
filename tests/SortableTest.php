<?php

use Oddvalue\EloquentSortable\Tests\Dummy;

it('can_be_move_records', function (int $from, int $to, array $expected) {
    $this->assertEquals(range(1, 10), Dummy::pluck('order_column')->all());

    Dummy::where('order_column', $from)->first()->moveTo(Dummy::find($to));

    $this->assertEquals($expected, Dummy::ordered()->pluck('original_position')->all());
})->with([
    [1, 2,  [2, 1, 3, 4, 5, 6, 7, 8, 9, 10]],
    [2, 6,  [1, 3, 4, 5, 6, 2, 7, 8, 9, 10]],
    [3, 10, [1, 2, 4, 5, 6, 7, 8, 9, 10, 3]],
    [4, 1,  [4, 1, 2, 3, 5, 6, 7, 8, 9, 10]],
    [5, 5,  [1, 2, 3, 4, 5, 6, 7, 8, 9, 10]],
    [6, 10, [1, 2, 3, 4, 5, 7, 8, 9, 10, 6]],
    [7, 1,  [7, 1, 2, 3, 4, 5, 6, 8, 9, 10]],
    [8, 5,  [1, 2, 3, 4, 8, 5, 6, 7, 9, 10]],
    [9, 3,  [1, 2, 9, 3, 4, 5, 6, 7, 8, 10]],
    [10, 1, [10, 1, 2, 3, 4, 5, 6, 7, 8, 9]],
]);

it('can_move_items_between', function (int $from, array $to, array $expected) {
    [$before, $after] = $to;

    Dummy::where('order_column', $from)->first()
        ->moveBetween(
            Dummy::where('order_column', $before)->first(),
            Dummy::where('order_column', $after)->first()
        );

    $this->assertEquals($expected, Dummy::ordered()->pluck('original_position')->all());
})->with([
    [1,  [1, 2],     [1, 2, 3, 4, 5, 6, 7, 8, 9, 10]],
    [2,  [5, 6],     [1, 3, 4, 5, 2, 6, 7, 8, 9, 10]],
    [3,  [10, null], [1, 2, 4, 5, 6, 7, 8, 9, 10, 3]],
    [4,  [null, 1],  [4, 1, 2, 3, 5, 6, 7, 8, 9, 10]],
    [5,  [4, 5],     [1, 2, 3, 4, 5, 6, 7, 8, 9, 10]],
    [6,  [10, null], [1, 2, 3, 4, 5, 7, 8, 9, 10, 6]],
    [7,  [null, 1],  [7, 1, 2, 3, 4, 5, 6, 8, 9, 10]],
    [8,  [4, 5],     [1, 2, 3, 4, 8, 5, 6, 7, 9, 10]],
    [9,  [10, null], [1, 2, 3, 4, 5, 6, 7, 8, 10, 9]],
    [10, [3, 4],     [1, 2, 3, 10, 4, 5, 6, 7, 8, 9]],
    [10, [100, 100], [1, 2, 3, 4, 5, 6, 7, 8, 9, 10]],
]);

it('can_move_items_before', function ($from, $to, $expected) {
    Dummy::where('order_column', $from)->first()->moveBefore($to);

    $this->assertEquals($expected, Dummy::ordered()->pluck('original_position')->all());
})->with([
    [1, 2,  [1, 2, 3, 4, 5, 6, 7, 8, 9, 10]],
    [2, 6,  [1, 3, 4, 5, 2, 6, 7, 8, 9, 10]],
    [3, 10, [1, 2, 4, 5, 6, 7, 8, 9, 3, 10]],
    [4, 1,  [4, 1, 2, 3, 5, 6, 7, 8, 9, 10]],
    [5, 5,  [1, 2, 3, 4, 5, 6, 7, 8, 9, 10]],
    [6, 8,  [1, 2, 3, 4, 5, 7, 6, 8, 9, 10]],
    [7, 2,  [1, 7, 2, 3, 4, 5, 6, 8, 9, 10]],
    [8, 5,  [1, 2, 3, 4, 8, 5, 6, 7, 9, 10]],
    [9, 3,  [1, 2, 9, 3, 4, 5, 6, 7, 8, 10]],
    [10, 1, [10, 1, 2, 3, 4, 5, 6, 7, 8, 9]],
]);

it('can_move_items_after', function ($from, $to, $expected) {
    Dummy::where('order_column', $from)->first()->moveAfter($to);

    $this->assertEquals($expected, Dummy::ordered()->pluck('original_position')->all());
})->with([
    [1, 2,  [2, 1, 3, 4, 5, 6, 7, 8, 9, 10]],
    [2, 6,  [1, 3, 4, 5, 6, 2, 7, 8, 9, 10]],
    [3, 10, [1, 2, 4, 5, 6, 7, 8, 9, 10, 3]],
    [4, 1,  [1, 4, 2, 3, 5, 6, 7, 8, 9, 10]],
    [5, 5,  [1, 2, 3, 4, 5, 6, 7, 8, 9, 10]],
    [6, 8,  [1, 2, 3, 4, 5, 7, 8, 6, 9, 10]],
    [7, 2,  [1, 2, 7, 3, 4, 5, 6, 8, 9, 10]],
    [8, 5,  [1, 2, 3, 4, 5, 8, 6, 7, 9, 10]],
    [9, 3,  [1, 2, 3, 9, 4, 5, 6, 7, 8, 10]],
    [10, 1, [1, 10, 2, 3, 4, 5, 6, 7, 8, 9]],
]);

it('performs_acceptably', function () {
    Dummy::query()->delete();
    collect(range(1, 5000))->each(fn ($i) => Dummy::create());
    $start = microtime(true);
    Dummy::first()->moveAfter(Dummy::inRandomOrder()->first());
    expect(microtime(true) - $start)->toBeLessThanOrEqual(2);
});
