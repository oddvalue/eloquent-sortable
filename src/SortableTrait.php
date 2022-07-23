<?php

namespace Oddvalue\EloquentSortable;

trait SortableTrait
{
    use \Spatie\EloquentSortable\SortableTrait;

    protected function getSortingValue(): int
    {
        return $this->{$this->determineOrderColumnName()};
    }

    public function moveBetween(Sortable|int $before = null, Sortable|int $after = null): void
    {
        $from = $this->getSortingValue();
        $to = null;

        if ($before instanceof Sortable) {
            $before = $before->getSortingValue();
        }

        if ($after instanceof Sortable) {
            $after = $after->getSortingValue();
        }

        if ($before) {
            // if the node has a sibling before it, insert after it
            if ($before === $from) {
                return;
            }
            $to = $before + ($from >= $before ? 1 : 0);
        } elseif ($after) {
            // if the node has a sibling after it, insert before it
            if ($after === $from) {
                return;
            }
            $to = $after - ($from <= $after ? 1 : 0);
        }

        if ($to === null) {
            return;
        }

        $this->moveTo($to);
    }

    public function moveBefore(Sortable|int $target): void
    {
        $this->moveBetween(after: $target);
    }

    public function moveAfter(Sortable|int $target): void
    {
        $this->moveBetween(before: $target);
    }

    public function moveTo(int|Sortable $newPosition): void
    {
        if ($newPosition instanceof Sortable) {
            $newPosition = $newPosition->getSortingValue();
        }

        $difference = $this->getSortingValue() - $newPosition;

        if ($difference === 0) {
            return;
        }

        $sequence = $this->buildSortQuery()
            ->ordered()
            ->where($this->getKeyName(), '!=', $this->getKey())
            ->getQuery()
            ->get([$this->determineOrderColumnName(), $this->getKeyName()]);

        [$before, $after] = $sequence->partition(function ($item) use ($newPosition, $difference) {
            if ($difference > 0) {
                return $item->{$this->determineOrderColumnName()} < $newPosition;
            } else {
                return $item->{$this->determineOrderColumnName()} <= $newPosition;
            }
        });

        static::setNewOrder([
            ...$before->pluck($this->getKeyName()),
            $this->getKey(),
            ...$after->pluck($this->getKeyName()),
        ]);
    }
}
