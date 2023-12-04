<?php

namespace DanilPetrenko\EloquentQueryFilter\Interfaces;

use DanilPetrenko\EloquentQueryFilter\Filter;
use Illuminate\Support\Collection;

interface FiltersRepositoryInterface
{
    public function getFilters(): Collection;
    public function merge(Collection $collection): static;
    public function mergeOnce(Collection|Filter $toMerge): static;
    public function addFilter(Filter $filter): static;
    public function makeNewFilter(string $name, \Closure $closure):static;
    public function setFilters(): void;
}