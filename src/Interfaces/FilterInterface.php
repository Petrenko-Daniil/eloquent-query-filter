<?php

namespace DanilPetrenko\EloquentQueryFilter\Interfaces;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

interface FilterInterface
{


    public function run(Builder $query, Model $model): Builder;
}