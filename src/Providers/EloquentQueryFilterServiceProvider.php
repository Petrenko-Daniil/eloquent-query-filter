<?php

namespace DanilPetrenko\EloquentQueryFilter\Providers;

class EloquentQueryFilterServiceProvider extends \Illuminate\Support\ServiceProvider
{
    public function boot()
    {
        $this->publishes([
            __DIR__ . '../../config/eloquent-filters.php' => config_path('eloquent-filters.php'),
        ]);
    }
}