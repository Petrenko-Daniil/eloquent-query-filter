<?php

namespace DanilPetrenko\EloquentQueryFilter;

use Closure;
use DanilPetrenko\EloquentQueryFilter\Interfaces\FilterInterface;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

abstract class Filter implements FilterInterface
{
    protected array|null $parameters;

    public function __construct(array $parameters = null)
    {
        $this->parameters = $parameters;
    }

    abstract public function run(Builder $query, Model $model = null): Builder;
}
