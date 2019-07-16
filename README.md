# Eloquent Filter
[![StyleCI](https://github.styleci.io/repos/149638067/shield?branch=master)](https://github.styleci.io/repos/149638067)

A package for filter data of models by query string.Easy to use and full dynamic.

## Installation

Run the Composer command

      $ composer require mehdi-fathi/eloquent-filter

## Basic Usage

Add trait to your models.You can override this method in your models.

```php
use Filterable;
```
Change your code on controller as like belove example:

```php
    public function list(modelFilters\modelFilters $filters)
    {
          if (!empty($filters->filters())) {

              //_User is a model in spinet code

              $users = $this->_User->filter($filters)->with('orders')->orderByDesc('id')->paginate(10);

              $users->appends($filters->filters())->render();

          } else {
              $users = $this->_User->with('orders')->orderByDesc('id')->paginate(10);
          }
     }
```

You just pass data blade form to query string or generate query string in your method you like do it.For example:

```
http://eloquent-filter.local/users/list?email=mehdifathi.developer@gmail.com
```
```
http://eloquent-filter.local/users/list?first_name=mehdi&last_name=fathi
```

Just fields of query string be same rows table database

### Date query filter

If you are going to make query whereBetween.you just send array as the value.you must fill keys from and to in array.
you can set it on query string as you know.this is a sample url with query string filter

```
http://example.com/users/list?created_at[from]=2016/05/01&created_at[to]=2017/10/01
```
### Custom query filter
If you are going to make yourself query filter you can do it easily.You just make a trait and use it on model:

```php
trait usersFilter
{
    public function username_like(Builder $builder, $value)
    {
        return $builder->where('username', 'like', '%' . $value . '%');
    }
}
```
Note that fields of query string be same methods trait.use trait in your model :

```php
class User extends Model
{
    use usersFilter,Filterable;

    protected $table = 'users';
    protected $guarded = [];
    
}
```
