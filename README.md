# Eloquent Filter
[![StyleCI](https://github.styleci.io/repos/149638067/shield?branch=master)](https://github.styleci.io/repos/149638067)
[![Build Status](https://travis-ci.org/mehdi-fathi/eloquent-filter.svg?branch=master)](https://travis-ci.org/mehdi-fathi/eloquent-filter)

A package for filter data of models by query string.Easy to use and full dynamic.

## Installation

Run the Composer command

      $ composer require mehdi-fathi/eloquent-filter

## Basic Usage

Add Filterable trait to your models and set fields that you will want filter in whitelist.You can override this method in your models.

```php
use eloquentFilter\QueryFilter\modelFilters\Filterable;
class User extends Model
{
    use Filterable;
    
    private static $whiteListFilter =[
        'id',
        'username',
        'email',
        'created_at',
        'updated_at',
    ];
}
```
You can set `*` char for filter in all fields as like below example:
 
```php
private static $whiteListFilter = ['*'];
```
You can add or set `$whiteListFilter` on the fly in your method.For example:

#### Set array to WhiteListFilter
Note that this method override `$whiteListFilter`
```php
User::setWhiteListFilter(['name']); 
```
#### Add new field to WhiteListFilter
```php
User::addWhiteListFilter('name'); 
```

Change your code on controller as like below example:

```php
public function list(modelFilters\modelFilters $filters)
{
      if (!empty($filters->filters())) {

          $users = User::filter($filters)->with('orders')->orderByDesc('id')->paginate(10);

          $users->appends($filters->filters())->render();

      } else {
          $users = User::with('orders')->orderByDesc('id')->paginate(10);
      }
}
```

### Simple Example

You just pass data blade form to query string or generate query string in controller method.For example:

**Simple Where**
```
?email=mehdifathi.developer@gmail.com

SELECT ... WHERE ... email = 'mehdifathi.developer@gmail.com'
```

```
?first_name=mehdi&last_name=fathi

SELECT ... WHERE ... first_name = 'mehdi' AND last_name = 'fathi'
```

```
?username[]=ali&username[]=ali22&family=ahmadi

SELECT ... WHERE ... username = 'ali' OR username = 'ali22' AND family = 'ahmadi'
```
***Where by operator***

You can set any operator mysql in query string.

```
?count_posts['operator']=>&count_posts['value']=35

SELECT ... WHERE ... count_posts > 35
```
```
?count_posts['operator']=!=&username['value']=ali

SELECT ... WHERE ... username != ali
```
```
?count_posts['operator']=<&count_posts['value']=25

SELECT ... WHERE ... count_posts < 25
```

Just fields of query string be same rows table database and adjusted in `$whiteListFilter` in your model.

***Where between***

If you are going to make query whereBetween.you just send array as the value.you must fill keys from and to in array.
you can set it on query string as you know.this is a sample url with query string filter

```
?created_at[start]=2016/05/01&created_at[end]=2017/10/01

SELECT ... WHERE ... created_at BETWEEN '2016/05/01' AND '2017/10/01'
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
Note that fields of query string be same methods trait.use trait in your model:

```php
class User extends Model
{
    use usersFilter,Filterable;

    protected $table = 'users';
    protected $guarded = [];
    private static $whiteListFilter =[
        'id',
        'username',
        'email',
        'created_at',
        'updated_at',
    ];
    
}
```
