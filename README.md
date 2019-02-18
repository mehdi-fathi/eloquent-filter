# eloquentFilter
A package for filter data of models by query string.it uses simplicity.

## Installation

Run the Composer update comand

      $ composer require eloquent-filter/filter

In your `config/app.php` add `eloquentFilter\QueryFilter\filterServiceProvider` to the end of the `$providers` array

```php
'providers' => [

    Illuminate\Foundation\Providers\ArtisanServiceProvider::class,
    Illuminate\Auth\AuthServiceProvider::class,
    ...
    eloquentFilter\QueryFilter\filterServiceProvider::class,

],
```
## Basic Usage

Add this method to your models.you should create parent model and add this method to it.

```php
public function scopeFilter($query, QueryFilter $filters)
{
  return $filters->apply($query,$this->getTable());
}
```
Change your code on controller as like belove example:

```php

    public function list(modelFilters\modelFilters $filters)
    {

          if (!empty($filters->filters())) {

              //_Cost is a model in spinet code

              $costs = $this->_Cost->filter($filters)->with('users')->orderByDesc('id')->paginate(10);

              $costs->appends($filters->filters())->render();

          } else {
              $costs = $this->_Cost->with('users')->orderByDesc('id')->paginate(10);
          }
     }
```

You just pass data blade form to query string or generate query string in your method you like do it.for example:

```
  list?cost=21&id=12
```

Juat fields of query string are as like as row table database
