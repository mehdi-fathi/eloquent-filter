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
use eloquentFilter\QueryFilter\ModelFilters\Filterable;

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

### Use in your controller

Change your code on controller as like below example:

```php

namespace App\Http\Controllers;

use eloquentFilter\QueryFilter\ModelFilters\ModelFilters;

/**
 * Class UsersController.
 */
class UsersController
{
    /**
     * @param \eloquentFilter\QueryFilter\ModelFilters\ModelFilters $modelFilters
     */
    public function list(ModelFilters $modelFilters)
    {
          if (!empty($modelFilters->filters())) {
              $users = User::filter($modelFilters)->with('posts')->orderByDesc('id')->paginate(10);
              $users->appends($modelFilters->filters())->render();
          } else {
              $users = User::with('posts')->orderByDesc('id')->paginate(10);
          }
    }
}
```

### Simple Example

You just pass data blade form to query string or generate query string in controller method.For example:

**Simple Where**
```
/users/list?email=mehdifathi.developer@gmail.com

SELECT ... WHERE ... email = 'mehdifathi.developer@gmail.com'
```

```
/users/list?first_name=mehdi&last_name=fathi

SELECT ... WHERE ... first_name = 'mehdi' AND last_name = 'fathi'
```

```
/users/list?username[]=ali&username[]=ali22&family=ahmadi

SELECT ... WHERE ... username = 'ali' OR username = 'ali22' AND family = 'ahmadi'
```
***Where by operator***

You can set any operator mysql in query string.

```
/users/list?count_posts[operator]=>&count_posts[value]=35

SELECT ... WHERE ... count_posts > 35
```
```
/users/list?username[operator]=!=&username[value]=ali

SELECT ... WHERE ... username != 'ali'
```
```
/users/list?count_posts[operator]=<&count_posts[value]=25

SELECT ... WHERE ... count_posts < 25
```

****Special Params****

You can set special params `limit` and `orderBy` in query string for make query by that.
```
/users/list?f_params[limit]=1

SELECT ... WHERE ... order by `id` desc limit 1 offset 0
```

```
/users/list?f_params[orderBy][field]=id&f_params[orderBy][type]=ASC

SELECT ... WHERE ... order by `id` ASC limit 10 offset 0
```
***Where between***

If you are going to make query whereBetween.You must fill keys `start` and `end` in query string.
you can set it on query string as you know.

```
/users/list?created_at[start]=2016/05/01&created_at[end]=2017/10/01

SELECT ... WHERE ... created_at BETWEEN '2016/05/01' AND '2017/10/01'
```

Also you can set jallali date in your params and eloquent-filter will detect jallali date and convert to gregorian then eloquent-filter generate new query. You just pass a jallali date by param

```
/users/list?created_at[start]=1397/10/11 10:11:46&created_at[end]=1397/11/17 10:11:46

SELECT ... WHERE ... created_at BETWEEN '2019-01-01 10:11:46' AND '2019-02-06 10:11:46'
``` 

****Advanced Where****
```
/users/list?count_posts[operator]=>&count_posts[value]=10&username[]=ali&username[]=mehdi&family=ahmadi&created_at[start]=2016/05/01&created_at[end]=2020/10/01
&f_params[orderBy][field]=id&f_params[orderBy][type]=ASC

select * from `users` where `count_posts` > 10 and `username` in ('ali', 'mehdi') and 
`family` = ahmadi and `created_at` between '2016/05/01' and '2020/10/01' order by 'id' asc limit 10 offset 0
```

Just fields of query string be same rows table database in `$whiteListFilter` in your model or declare method in your model as override method.
Override method can be considered custom query filter.

### Custom query filter
If you are going to make yourself query filter you can do it easily.You just make a trait and use it on model:

```php
use Illuminate\Database\Eloquent\Builder;

/**
 * Trait usersFilter.
 */
trait usersFilter
{
    /**
     * @param \Illuminate\Database\Eloquent\Builder $builder
     * @param                                       $value
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function username_like(Builder $builder, $value)
    {
        return $builder->where('username', 'like', '%'.$value.'%');
    }
}
```

Note that fields of query string be same methods of trait.Use trait in your model:

```
/users/list?username_like=a

select * from `users` where `username` like %a% order by `id` desc limit 10 offset 0
```

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
