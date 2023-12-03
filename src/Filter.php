<?php

namespace DanilPetrenko\EloquentQueryFilter;

use Closure;
use Illuminate\Database\Eloquent\Builder;

class Filter
{
    private string $name;
    private Closure $closure;

    public function __construct(string $name, Closure $closure)
    {
        $this->name = $name;
        $this->closure = $closure;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function run(...$vars): Builder
    {
        return $this->closure->call(...$vars);
    }
}
