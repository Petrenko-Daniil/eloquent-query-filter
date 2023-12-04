<?php

namespace DanilPetrenko\EloquentQueryFilter;

use DanilPetrenko\EloquentQueryFilter\Filter;
use DanilPetrenko\EloquentQueryFilter\Interfaces\FactoryInterface;
use Exception;
class FilterFactory implements FactoryInterface
{
    /**
     * @throws Exception
     */
    static public function create(string $name = null, \Closure $closure = null): Filter
    {
        if (!$name or !$closure)
            throw new Exception('$name and $closure are required');
        return new Filter($name, $closure);
    }
}
