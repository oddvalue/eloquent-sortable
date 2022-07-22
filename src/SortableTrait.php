<?php

namespace Oddvalue\EloquentSortable;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Spatie\EloquentSortable\Sortable;

trait SortableTrait
{
    use \Spatie\EloquentSortable\SortableTrait;

    public static function bootSortableTrait(): void
    {
        static::deleted([static::class, 'resortRowsDeleted']);
    }

    protected function getSortingValue(): int
    {
        return $this->{$this->determineOrderColumnName()};
    }

    protected function setSortingValue(int $value): void
    {
        $this->{$this->determineOrderColumnName()} = $value;
    }

    public static function resortRowsDeleted(Sortable $instance): void
    {
        if (!$instance->shouldSortWhenDeleting()) {
            return;
        }
        $instance->newQuery()
                 ->modifySortingQuery($instance)
                 ->where($instance->determineOrderColumnName(), '>', $instance->getSortingValue())
                 ->decrementSort();
    }

    public function shouldSortWhenDeleting(): bool
    {
        return $this->sortable['sort_when_deleting'] ?? config('eloquent-sortable.sort_when_deleting', true);
    }

    public function moveBetween(Sortable $before = null, Sortable $after = null): void
    {
        $from = $this->getSortingValue();
        $to = null;

        if ($before) {
            // if the node has a sibling before it, insert after it
            $beforePosition = $before->getSortingValue();
            if ($beforePosition === $from) {
                return;
            }
            $to = $beforePosition + ($from >= $beforePosition ? 1 : 0);
        } elseif ($after) {
            // if the node has a sibling after it, insert before it
            $afterPosition = $after->getSortingValue();
            if ($afterPosition === $from) {
                return;
            }
            $to = $afterPosition - ($from <= $afterPosition ? 1 : 0);
        }

        if ($to === null) {
            return;
        }

        $this->moveTo($to);
    }

    public function moveBefore(Sortable $target): void
    {
        $this->moveBetween(after: $target);
    }

    public function moveAfter(Sortable $target): void
    {
        $this->moveBetween(before: $target);
    }

    public function moveTo(int|Sortable $newPosition): void
    {
        if ($newPosition instanceof Sortable) {
            $newPosition = $newPosition->getSortingValue();
        }

        $from = $this->getSortingValue();

        $difference = $from - $newPosition;

        if ($difference === 0) {
            return;
        }
        $query = $this->buildSortQuery()->whereBetween(
            $this->determineOrderColumnName(),
            [min($from, $newPosition), max($from, $newPosition)]
        );

        if ($difference > 0) {
            $query->incrementSort();
        } else {
            $query->decrementSort();
        }
        $this->setSortingValue($newPosition);
        $this->save();
    }

    public function scopeIncrementSort(Builder $query): void
    {
        // Increment is called on the raw query builder to avoid touching the
        // updated timestamp
        $query->getQuery()->increment($this->determineOrderColumnName());
    }

    public function scopeDecrementSort(Builder $query): void
    {
        // Decrement is called on the raw query builder to avoid touching the
        // updated timestamp
        $query->getQuery()->decrement($this->determineOrderColumnName());
    }
}
