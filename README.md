# **EloquentQueryFilter**
Package is developed for quick and easy managing filtering in your application.
### Usage:
Create a new class based on **BaseFilter** class

    class Filter extends BaseFilter
    {
		
	}
You can redefine **initFilters()** method to create filters

    public function initFilters():void
    {
	    $this->addFilter('active', funtion($query){
		    return $query->where('active', true);
		});
	}
And then you're able to use this filter anywhere.

    private function getUsers()
    {
		$filter = new Filter(User::class);
	    $users = $filter->setFilters(['active'])
		    ->getBuilder()
		    ->get();
		return $users;
	}

Every method is well explained in **BaseFilter** class, so it's easy to understand how you can interact with it.
I hope this package will help you in development. Feel free to open issues if needed. Contributors are also welcomed!