# Eloquent Filter


![alt text](./eloquent-filter.jpg "eloquent-filter")

[![Latest Stable Version](https://poser.pugx.org/mehdi-fathi/eloquent-filter/v/stable)](https://packagist.org/packages/mehdi-fathi/eloquent-filter)
![Run tests](https://github.com/mehdi-fathi/eloquent-filter/workflows/Run%20tests/badge.svg?branch=master)
[![License](https://poser.pugx.org/mehdi-fathi/eloquent-filter/license)](https://packagist.org/packages/mehdi-fathi/eloquent-filter)
[![GitHub stars](https://img.shields.io/github/stars/mehdi-fathi/eloquent-filter)](https://github.com/mehdi-fathi/eloquent-filter/stargazers)
[![StyleCI](https://github.styleci.io/repos/149638067/shield?branch=master)](https://github.styleci.io/repos/149638067)
[![Build Status](https://travis-ci.org/mehdi-fathi/eloquent-filter.svg?branch=master)](https://travis-ci.org/mehdi-fathi/eloquent-filter)
[![Total Downloads](https://poser.pugx.org/mehdi-fathi/eloquent-filter/downloads)](//packagist.org/packages/mehdi-fathi/eloquent-filter)
[![Daily Downloads](https://poser.pugx.org/mehdi-fathi/eloquent-filter/d/daily)](//packagist.org/packages/mehdi-fathi/eloquent-filter)

The Eloquent filter is a package for filter data of models by the query string in the Laravel application.
It's easy to use and fully dynamic.


## Table of Content
- [Introduction](#Introduction)
- [Basic Usage](#Basic-Usage)
    - [Config Model](#Config-Model-and-set-whitelist)
    - [Use in Controller](#Use-in-Controller)
    - [Simple Examples](#Simple-Examples)
    - [Custom query filter](#Custom-query-filter)

## Requirements
- PHP 7.2+
- Laravel 5.8+,6.x,7.x,8(prefer-stable)

## :microphone: Introduction

Let's say we want to make an advanced search page with multiple filter option params. When we navigate to:
                                                                                     
    http://localhost:8000/users/index?age_more_than=25&gender=male&created_at=25-09-2019
 
```php
<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $users = User::where('is_active', true);

        if ($request->has('age_more_than')) {
            $users->where('age', '>', $request->age_more_than);
        }

        if ($request->has('gender')) {
            $users->where('gender', $request->gender);
        }

        if ($request->has('created_at')) {
            $users->where('created_at','>=', $request->created_at);
        }

        return $users->get();
    }
}
```
We check out a condition for each request.

In the future, if your project will need more filter requests at that time you should add many conditions to the above code.
Imagine some of the queries may be advanced therefore your code to be like MONSTER! :boom:

The eloquent filter is proper for make advanced search filters or report pages. 
Eloquent filter saves your time and destroys the complexity of your code.

To filter that same input With Eloquent Filters:

Just change query string as the this example:
             
    http://localhost:8000/users/list?age_more_than[operator]=>&age[value]=35&gender=male&created_at[operator]==>&created_at[value]=25-09-2019

```php
/**
 * Class UsersController.
 */

namespace App\Http\Controllers;

use App\User;

class UsersController
{
    public function list()
    {
        return User::filter()->get();
    }
}
```
Just this!

## :electric_plug: Installation

1- Run the Composer command for installing latest version

      $ composer require mehdi-fathi/eloquent-filter
      
- **Note**  that installed for laravel version previous of 5.8 you should install version 2.2.5 

        $ composer require mehdi-fathi/eloquent-filter:2.2.5

2- Add `eloquentFilter\ServiceProvider::class` to provider app.php
   
   ```php
   'providers' => [
     /*
      * Package Service Providers...
      */
       eloquentFilter\ServiceProvider::class
   ],
   ```
3- Add Facade `'EloquentFilter' => eloquentFilter\Facade\EloquentFilter::class` to aliases app.php

```php
'alias' => [
  /*
   * Facade alias...
   */
    'EloquentFilter' => eloquentFilter\Facade\EloquentFilter::class,
],
```
That's it enjoy! :boom:
## :book: Basic Usage

### Config Model and set whitelist

Add Filterable trait to your models and set fields that you will want filter in whitelist. You can override this method in your models.

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

***Set array to WhiteListFilter***

- **Note** that this method override `$whiteListFilter`
```php
User::setWhiteListFilter(['name']); 
```
***Add new field to WhiteListFilter***
```php
User::addWhiteListFilter('name'); 
```

### Use in Controller

Change your code on controller of laravel as like below example:

```php

namespace App\Http\Controllers;

/**
 * Class UsersController.
 */
class UsersController
{

    public function list()
    {
          if (!empty(request()->get('username'))) {
          
              $users = User::ignoreRequest('perpage')->filter()->with('posts')
                        ->orderByDesc('id')->paginate(request()->get('perpage'),['*'],'page');

          } else {
              $users = User::filter(
                ['username' => ['mehdi','ali']]           
                )->with('posts')->orderByDesc('id')->paginate(10,['*'],'page');
          }
    }
}
```
-**Note**  that the Eloquent Filter by default using the query string or request data to make queries in the laravel.
 Also, you can set the array to `filter` method Model for making your own custom condition without query string.

-**Note**  that you must unset your own param as perpage. Just you can set page param for paginate this param ignore from filter.

You can ignore some request params by use of code it.

```php

User::ignoreRequest(['perpage'])->filter()
            ->paginate(request()->get('perpage'), ['*'], 'page');
```

Call `ignoreRequest` will ignore some requests that you don't want to use in conditions eloquent filter. 
For example perpage param will never be in the conditions eloquent filter. 
it's related to the paginate method. `page` param ignore by default in the Eloquent Filter Laravel.


- Another example use of a filter eloquent filter.
```php
User::filter()->paginate();
```
- `EloquentFilter::filterRequests()` get all params that used by the Eloquent Filter. You can set key to get specific index.
For example `EloquentFilter::filterRequests('username')` it's getting username index.

### Simple Examples

You just pass data blade form to query string or generate query string in controller method. For example:

**Simple Where**
```
/users/list?email=mehdifathi.developer@gmail.com

SELECT ... WHERE ... email = 'mehdifathi.developer@gmail.com'
```

```
/users/list?first_name=mehdi&last_name=fathi

SELECT ... WHERE ... first_name = 'mehdi' AND last_name = 'fathi'
```

***Where In***

This example make method `whereIn`.

```
/users/list?username[]=ali&username[]=ali22&family=ahmadi

SELECT ... WHERE ... username in ('ali','ali22') AND family = 'ahmadi'
```

***OrWhere (New feature :fire:)***

This example make method `orWhere`.

```
/users/list?name=mehdi&username=fathi&or[username]=ali

SELECT ... WHERE ... name = 'mehdi' AND username = 'fathi' or username = 'ali'
```

***Where like***

If you are going to make query by like conditions. You can do it that by this example.

```
/users/list?first_name[like]=%John%

SELECT ... WHERE ... first_name LIKE '%John%'

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

**Where the nested relation Model (New feature :fire:)**


You can set all nested relation in the query string just by the array query string. For example, the user model has a relation with posts.
and posts table has a relation with orders. You can make query conditions by set 'posts[count_post]' and 'posts[orders][name]' in the query string.
Just be careful you must set 'posts.count_post' and 'posts.orders.name' in the User model.

```php
use eloquentFilter\QueryFilter\ModelFilters\Filterable;

class User extends Model
{
    use Filterable;
   
    private static $whiteListFilter =[
        'username',
        'posts.count_post',
        'posts.category',
        'posts.orders.name',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\belongsTo
     */
    public function posts()
    {
        return $this->belongsTo('Models\Post');
    }

}
``` 

```
/users/list?posts[count_post]=876&username=mehdi

select * from "users" where exists 
         (select * from "posts" where "posts"."user_id" = "users"."id" 
         and "posts"."count_post" = 876)
         and "username" = "mehdi"
```
- The above example as the same code that you use without the eloquent filter. Check it under code.

```php
$user = new User();
$builder = $user->with('posts');
        $builder->whereHas('posts', function ($q) {
            $q->where('count_post', 876);
        })->where('username','mehdi');

```

***Where array the nested relation Model***

You can pass array to make whereIn condition.

```
/users/list?posts[category][]=php&posts[category][]=laravel&posts[category][]=jquery&username=mehdi

select * from "users" where exists 
         (select * from "posts" where 
         "posts"."category" in ('php','laravel','jquery') )
         and "username" = "mehdi"
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
you can set it on query string as you know. this params is good fit for filter by date.

```
/users/list?created_at[start]=2016/05/01&created_at[end]=2017/10/01

SELECT ... WHERE ... created_at BETWEEN '2016/05/01' AND '2017/10/01'
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
The Eloquent Filter doesn't support all of the conditions by default. For this situation you can make a override method.
If you are going to make yourself query filter you can do it easily. You just make a trait and use it on model:

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

-**Note** that fields of query string be same methods of trait. Use trait in your model:

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
- If you have any idea about the Eloquent Filter i will glad to hear that.
You can make an issue or contact me by email. My email is mehdifathi.developer@gmail.com.
