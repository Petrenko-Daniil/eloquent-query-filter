<?php

namespace DanilPetrenko\EloquentQueryFilter\Traits;

use DanilPetrenko\EloquentQueryFilter\Filter;
use DanilPetrenko\EloquentQueryFilter\FiltersRepository;
use Illuminate\Database\Eloquent\Builder;
use Exception;

trait HasFilter
{
    /**
     * @param Builder $query
     * @param FiltersRepository $repository
     * @param array|null $parameters
     * @return Builder
     * @throws Exception
     */
    public function scopeUseFiltersRepository(Builder $query, FiltersRepository $repository, array $parameters = null): Builder
    {
        $filters = $repository::getFilters();
        return $this->scopeUseFilters($query, $filters, $parameters);
    }

    /**
     * @param Builder $query
     * @param array|string[] $filters
     * @param array|null $parameters
     * @return Builder
     * @throws Exception
     */
    public function scopeUseFilters(Builder $query, array $filters, array $parameters = null): Builder
    {
        foreach ($filters as $filter) {
            $query = $this->scopeUseFilter($query, $filter, $parameters);
        }
        return $query;
    }

    /**
     * @param Builder $query
     * @param string $filter
     * @param array|null $parameters
     * @return Builder
     * @throws Exception
     */
    public function scopeUseFilter(Builder $query, string $filter, array $parameters = null): Builder
    {
        if (class_exists($filter)) {
            $filter = new $filter($parameters);
        } else {
            $filterClass = isset(config('eloquent-filters.filters')[$filter])
                ? config('eloquent-filters.filters')[$filter]
                : throw new Exception('No filter with such name found, try use class-string instead');
            $filter = $filterClass($parameters);
        }

        /** @var Filter $filter */
        return $filter->run($query);
    }
}
