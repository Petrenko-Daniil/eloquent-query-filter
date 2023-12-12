# **EloquentQueryFilter**

Package is developed for quick and easy managing filtering in your application.

### Usage:

Go to your model and add `use HasFilter;` trait, so you can inject filtration logic anywhere.

Via laravel scopes this trait adds simple methods to inject your filters as a

1. One filter `User::useFilter(ActiveFilter::class)->first();`
2. Array of filters `User::useFilters([ActiveFilter::class, IsAdminFilter::class])->first();`
3. FiltersRepository `User::useFiltersRepository(UserFiltersRepository::class)->first();`

Each of your filters have to extend Filter class and implement run() method. <br>
run() method should always return Builder instance and can use $model to check model's columns for example.

    public function run(Builder $query, Model $model): Builder
    {
        return $query->where('active', true);
    }

You can also use $this->parameters field to conditionally filter you model.<br>

### FiltersRepository

You can create repository of filters which extends FiltersRepository class and implements getFilters() method.<br>
As a return you should provide a list of filter classes.