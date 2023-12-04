<?php

namespace DanilPetrenko\EloquentQueryFilter;

use Closure;
use DanilPetrenko\EloquentQueryFilter\FiltersRepository;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class FilterService
{
    private string|Builder $modelClass;
    private Model $model;
    private string $table;
    private FiltersRepository $filters;

    protected FiltersRepository $activeFilters;
    protected array $columns = [];
    protected array $inputData = [];

    /**
     * Expects Eloquent model class
     * @param string $modelClass
     */
    public function __construct(string $modelClass)
    {
        $this->filters = new FiltersRepository();
        $this->activeFilters = new FiltersRepository();

        $this->modelClass = $modelClass;
        $this->model = new $modelClass();
        $this->table = $this->model->getTable();
        $this->columns = DB::getSchemaBuilder()->getColumnListing($this->table);
        $this->initFilters();
    }

    /**
     * Use this method to check that model has column for filtering.
     * @return array
     */
    public function getColumns(): array
    {
        return $this->columns;
    }

    /**
     * Setting data for filters, use it to provide such data as
     * ids, another collections, search strings, etc.
     * @param array $data
     * @return $this
     */
    public function setInputData(array $data): static
    {
        $this->inputData = $data;
        return $this;
    }

    /**
     * Method to get input data in child class
     * @param $key
     * @return mixed
     */
    public function getInputData($key): mixed
    {
        if (isset($this->inputData[$key]))
            return $this->inputData[$key];
        return null;
    }

    /**
     * Method for usage in child class, specially inside initFilters()
     * method. Also, you can use it to define filters with one-time usage.
     * @param string $name
     * @param Closure $closure
     * @return $this
     * @throws Exception
     */
    public function addFilter(string $name, Closure $closure, bool $addToActive = false): static
    {
        if ($this->filters->getFilters()->where('name', $name)->first() != null)
            throw new Exception('Filter with the name '.$name.' already exists');
        $this->filters->makeNewFilter($name, $closure);
        if ($addToActive)
            $this->activeFilters->addFilter($this->filters->getFilters()->where('name', $name)->first());
        return $this;
    }

    /**
     * May be used inside initFilters() method or right before usage.
     * Method provides beautiful way to load filters from another class.
     * @param string $className
     * @return $this
     */
    public function loadFiltersFromClass(string $className): static
    {
        /** @var FiltersRepository $filtersProvider */
        $filtersProvider = new $className();
        $this->filters->merge($filtersProvider->getFilters());
        return $this;
    }

    /**
     * Pass a list of filter names to use them on getBuilder() method.
     * @param array|string[] $filterNames
     * @return $this
     * @throws Exception
     */
    public function setFilters(array $filterNames): static
    {

        foreach ($filterNames as $filterName)
        {
            $filterToMerge = $this->filters->getFilters()->where('name', $filterName)->first();
            if (!$filterToMerge)
                throw new Exception('Filter '.$filterName.' does not exist');
            $this->activeFilters->mergeOnce($filterToMerge);
        }
        return $this;
    }

    /**
     * Returns list of existing filters, may be used to check if filter exists.
     * @return array
     */
    public function getFiltersList(): array
    {
        return $this->filters->getFilters()->pluck('name')->toArray();
    }

    /**
     * Use this function after all data and list of filters are prepared.
     * Returns null in case you forgot to provide list of filters.
     * Return Eloquent/Builder in case everything went as expected.
     * @return Builder|null
     */
    public function getBuilder(): Builder|null
    {
        if (count($this->activeFilters->getFilters()) === 0)
            return null;
        $builder = null;
        foreach ($this->activeFilters as $filter){
            if ($builder == null){
                $builder = $this->modelClass::when(true, function ($query) use ($filter) {
                    return $filter($query);
                });
            } else {
                $builder = $builder->when(true, function ($query) use ($filter) {
                    return $filter($query);
                });
            }
        }
        return $builder;
    }

    /**
     * Redefine this method and provide data filters via addFilter() method.
     * @return void
     */
    protected function initFilters(): void
    {
        //Redefine me!
    }
}
