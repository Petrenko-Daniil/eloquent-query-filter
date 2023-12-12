<?php

namespace DanilPetrenko\EloquentQueryFilter;

use DanilPetrenko\EloquentQueryFilter\Interfaces\FiltersRepositoryInterface;
use Illuminate\Support\Collection;

abstract class FiltersRepository implements FiltersRepositoryInterface
{
    abstract static public function getFilters(): array;
}
