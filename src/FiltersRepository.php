<?php

namespace DanilPetrenko\EloquentQueryFilter;

use DanilPetrenko\EloquentQueryFilter\FilterService;
use DanilPetrenko\EloquentQueryFilter\Interfaces\FiltersRepositoryInterface;
use Illuminate\Support\Collection;

class FiltersRepository implements FiltersRepositoryInterface
{
    /**
     * @var Collection
     */
    private Collection $filters;

    public function __construct()
    {
        $this->filters = collect();
        $this->setFilters();
    }

    public function getFilters(): Collection
    {
        return $this->filters;
    }

    public function merge(Collection $collection): static
    {
        $this->filters->merge($collection);
        return $this;
    }

    public function mergeOnce(Collection|Filter $toMerge): static
    {
        if ($toMerge instanceof Collection){
            foreach ($toMerge as $item){
                $this->mergeOnce($item);
            }
        }
        if (!$this->filters->where('name', $toMerge->getName())->first())
            $this->filters->merge(collect($toMerge));
        return $this;
    }

    public function addFilter(Filter $filter): static
    {
        $this->filters->add($filter);
        return $this;
    }

    public function makeNewFilter(string $name, \Closure $closure):static
    {
        $this->filters->add(FilterFactory::create($name, $closure));
        return $this;
    }

    public function setFilters(): void
    {

    }

}
