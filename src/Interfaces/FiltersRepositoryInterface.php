<?php

namespace DanilPetrenko\EloquentQueryFilter\Interfaces;

use DanilPetrenko\EloquentQueryFilter\Filter;
use Illuminate\Support\Collection;

interface FiltersRepositoryInterface
{
    public static function getFilters(): array;

}