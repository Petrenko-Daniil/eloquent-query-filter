<?php

namespace DanilPetrenko\EloquentQueryFilter;

class FilterFactory
{
    static public function create(string $name, \Closure $closure): Filter
    {
        return new Filter($name, $closure);
    }
}
