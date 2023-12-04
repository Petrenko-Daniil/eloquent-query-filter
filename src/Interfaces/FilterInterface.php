<?php

namespace DanilPetrenko\EloquentQueryFilter\Interfaces;

use Illuminate\Database\Eloquent\Builder;

interface FilterInterface
{

    public function getName(): string;

    public function run(...$vars): Builder;
}