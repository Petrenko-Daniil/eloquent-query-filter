<?php

namespace DanilPetrenko\EloquentQueryFilter;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class BaseFilter
{
    private array $inputData = [];
    private string $modelClass;
    private Model $model;
    private string $table;
    private array $columns = [];
    private array $filters = [];
    private array $activeFilters = [];

    /**
     * Expects Eloquent model class
     * @param string $modelClass
     */
    public function __construct(string $modelClass)
    {
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
    public function addFilter(string $name, Closure $closure): static
    {
        if (!isset($this->filters[$name])){
            $this->filters[$name] = $closure;
        } else {
            throw new Exception('Filter with the name '.$name.' already exists');
        }
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
        $this->filters = array_merge($this->filters, $className::getFiltersArray());
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
            if (isset($this->filters[$filterName])) {
                $this->activeFilters[] = &$this->filters[$filterName];
            } else {
                throw new Exception('Filter '.$filterName.' does not exist');
            }
        }
        return $this;
    }

    /**
     * Returns list of existing filters, may be used to check if filter exists.
     * @return array
     */
    public function getFiltersList(): array
    {
        return array_keys($this->filters);
    }

    /**
     * Use this function after all data and list of filters are prepared.
     * Returns null in case you forgot to provide list of filters.
     * Return Eloquent/Builder in case everything went as expected.
     * @return Builder|null
     */
    public function getBuilder(): Builder|null
    {
        if (count($this->activeFilters) === 0)
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
    public function initFilters(): void
    {
        //Redefine me!
    }
}
