# Eloquent Filter [![Tweet](https://img.shields.io/twitter/url/http/shields.io.svg?style=social)](https://twitter.com/intent/tweet?text=Eloquent%20Filter%20adds%20custom%20filters%20to%20your%20Eloquent%20Models%20in%20Laravel%26url%3Dhttps%3A%2F%2Fgithub.com%2Fmehdi-fathi%2Feloquent-filter%26via%3Dmehdi-fathi%26hashtags%3Dlaravel%2Celoquent%2Cphp%2Cmysql%2Cdatabase%2Celoquentfilter)



![alt text](./eloquent-filter.jpg "eloquent-filter")

[![Latest Stable Version](https://img.shields.io/packagist/v/mehdi-fathi/eloquent-filter)](https://packagist.org/packages/mehdi-fathi/eloquent-filter)
![Run tests](https://github.com/mehdi-fathi/eloquent-filter/workflows/Run%20tests/badge.svg?branch=master)
[![License](https://poser.pugx.org/mehdi-fathi/eloquent-filter/license)](https://packagist.org/packages/mehdi-fathi/eloquent-filter)
[![GitHub stars](https://img.shields.io/github/stars/mehdi-fathi/eloquent-filter)](https://github.com/mehdi-fathi/eloquent-filter/stargazers)
[![StyleCI](https://github.styleci.io/repos/149638067/shield?branch=master)](https://github.styleci.io/repos/149638067)
[![Build Status](https://travis-ci.org/mehdi-fathi/eloquent-filter.svg?branch=master)](https://travis-ci.org/mehdi-fathi/eloquent-filter)
[![Monthly Downloads](https://img.shields.io/packagist/dm/mehdi-fathi/eloquent-filter?color=blue)](https://packagist.org/packages/mehdi-fathi/eloquent-filter)

Eloquent Filter adds custom filters to your Eloquent Models in Laravel.
It's easy to use and fully dynamic.

[![Bitcoin Donate Button](http://KristinitaTest.github.io/donate/Bitcoin-Donate-button.png)](https://www.bitcoinqrcodemaker.com/api/?style=bitcoin&amp;address=bc1qqzhw9euweyz28mtgqyjt703zzypyjxgxrxjgvm "eloquent-filter")

## Table of Content
- [Introduction](#Introduction)
- [Installation](#Installation)
- [Basic Usage](#Basic-Usage)
    - [Config Model](#Config-Model-and-set-whitelist)
    - [Use in Controller](#Use-in-Controller)
    - [Simple Examples](#Simple-Examples)
    - [Custom Query Filter](#Custom-query-filter)
    - [Custom Detection Condition](#Custom-Detection-Condition)
- [Configuring](#Configuring)    
    - [Publish Config](#Publish-Config)
    - [Config](#Config)
- [Magic Methods](#Magic-Methods)
    - [Request Filter](#Request-filter)
    - [Response Filter](#Response-filter)

## Requirements
- PHP 7.2+, 8.0 (new version)
- Laravel 5.8+,6.x,7.x,8(prefer-stable)

## :microphone: Introduction

Let's say we want to make an advanced search page with multiple filter option params.

![alt text](https://raw.githubusercontent.com/mehdi-fathi/mehdi-fathi.github.io/master/eloquent-filter/assets/img/Esfand-05-1399%2022-23-21.gif "sample 1 eloquent-filter")

### A simple implementation without Eloquent Filter
The Request URI could look like this:
                                                                                     
    http://localhost:8000/users/index?age_more_than=25&gender=male&created_at=25-09-2019
 
And a simple implementation in the Controller would look like this: 
 
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
This solution is simple ,but that works fine.
But you'd have to add a condition for each filter you need. 
Especially with more complex filtering, your code can become a Monster very fast! :boom: 


### A simple implementation with Eloquent Filter

Eloquent Filter can help you to fix that problem. Just you will set query string to work with that.
It will save you time and minimize the complexity of your code.

After installing Eloquent Filter the request URI could look like this:
             
    http://localhost:8000/users/list?age_more_than[operator]=>&age[value]=35&gender=male&created_at[operator]==>&created_at[value]=25-09-2019


And in the controller you'd just need that one line: 
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
With this Eloquent filter implementation, you can use all the documented filters!

## :electric_plug: Installation

1- Run this Composer command to install the latest version

      $ composer require mehdi-fathi/eloquent-filter
      
- **Note**  for Laravel versions older than 5.8 you should install version 2.2.5 

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

Add The Filterable trait to your models and set fields that you will want to filter in the whitelist array. 
as well You can override this method in your models.

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
You can set `*` char for that filter in all fields aslike below example:
 
```php
private static $whiteListFilter = ['*'];
```
You can add or set `$whiteListFilter` on the fly in your method.For example:

***Set array to WhiteListFilter***

- **Note** This method override `$whiteListFilter` array
```php
User::setWhiteListFilter(['name']); 
```
***Add new field to WhiteListFilter***
```php
User::addWhiteListFilter('name'); 
```

### Use in Controller

Change your code the controller of the laravel project as like below example:

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
-**Note** The Eloquent Filter config by default uses the query string to make queries in Laravel.
 Although, you can set the array to the `filter` method Model for making your own custom condition without query string.

-**Note**  Therefore you must unset yourself param as perpage. Just you can set page param for paginate this param ignore from the filter.

- You can ignore some of the request params by use of the bellow code.

```php

User::ignoreRequest(['perpage'])
            ->filter()
            ->paginate(request()->get('perpage'), ['*'], 'page');
```

Call `ignoreRequest` that will ignore some requests that you don't want to use in conditions eloquent filter. 
For example, the perpage param will never be in the conditions eloquent filter. 
it's related to the paginate method. `page` param ignore by default in Eloquent Filter of Laravel.

- You can filter some of the request params for using in Eloquent Filter.

```php

User::AcceptRequest(['username','id'])
            ->filter()
            ->paginate(request()->get('perpage'), ['*'], 'page');
```

Call `AcceptRequest` will accept requests which you want to use in conditions Eloquent Filter. 
For example `username` and `id` param will be in the conditions eloquent filter. Just notice you must set `$whiteListFilter`
in Model. This method is useful for query string manipulation by a user.


- Another example use of a filter eloquent filter.
```php
User::filter()->paginate();
```
- `EloquentFilter::filterRequests()` get all params that used by the Eloquent Filter. You can set key to get specific index.
For example `EloquentFilter::filterRequests('username')` it's getting username index.

- `EloquentFilter::getAcceptedRequest()` get all params that set by the AcceptRequest method.

- `EloquentFilter::getIgnoredRequest()` get all ignored params that set by the getIgnoreRequest method.

### Simple Examples

You just pass data blade form to query string or generate query string in the Controller method. For example:

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

If you are going to make a query by like conditions. You can do that by this example.

```
/users/list?first_name[like]=%John%

SELECT ... WHERE ... first_name LIKE '%John%'

```

***Where by operator***

You can set any operator mysql in the queries string.

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


You can set all nested relation in the query string just by the array of query string. For example, The user model has a relation with posts.
And posts table has a relation with orders table. You can make query conditions by set 'posts[count_post]' and 'posts[orders][name]' in the query string.
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
- The above example is the same code that you use without the eloquent filter. Check it under code.

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

You can set special params `limit` and `orderBy` in the query string to make a query by that.
```
/users/list?f_params[limit]=1

SELECT ... WHERE ... order by `id` desc limit 1 offset 0
```
```
/users/list?f_params[orderBy][field]=id&f_params[orderBy][type]=ASC

SELECT ... WHERE ... order by `id` asc
```
```
/users/list?f_params[orderBy][field]=id,count_posts&f_params[orderBy][type]=ASC

SELECT ... WHERE ...  order by `id` asc, `count_posts` asc
```
***Where between***

If you are going to make a query based on date, You must fill keys, `start`, and `end` in the query string.
Hence You can set it as a query string. These params are used for the filter by date.

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

Therefore fields of query string are same rows table database in `$whiteListFilter` in your model or declare the method in your model as override method.
The overridden method can be considered a custom query filter.

### Custom Query Filter

Eloquent Filter doesn't support all of the conditions by default. For this situation, you can make an overridden method.
If you are going to make yourself a query filter, you can do it easily.

You should run the command to make a trait and use it on the model:

    php artisan eloquentFilter:filter users

```php
namespace App\ModelFilters;

use Illuminate\Database\Eloquent\Builder;

/**
 * Trait UsersFilter.
 */
trait UsersFilter
{
    /**
     * This is a sample custom query
     * @param \Illuminate\Database\Eloquent\Builder $builder
     * @param                                       $value
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function sample_like(Builder $builder, $value)
    {
        return $builder->where('username', 'like', '%'.$value.'%');
    }
}
```

-**Note** These fields of query string are the same methods of the trait. Use trait in your model:

```
/users/list?sample_like=a

select * from `users` where `username` like %a% order by `id` desc limit 10 offset 0
```

```php

use App\ModelFilters\UsersFilter;

class User extends Model
{
    use UsersFilter,Filterable;

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

### Custom Detection Condition


Sometimes you want to make your custom condition to make a new query that Eloquent Filter doesn't support that by default.
The good news is you can make a custom condition in the eloquent filter from now on.

You can make conditions to generate a new query after checking by that.
 (New feature :fire: ). For example :

We must have two classes. The First detects conditions second class generates the query.
 
- Step 1: Create a class to detect some of the conditions

```php

use eloquentFilter\QueryFilter\Detection\DetectorContract;

/**
 * Class WhereRelationLikeCondition.
 */
class WhereRelationLikeCondition implements DetectorContract
{
    /**
     * @param $field
     * @param $params
     * @param $is_override_method
     *
     * @return string|null
     */
    public static function detect($field, $params, $is_override_method = false): ?string
    {
        if (!empty($params['value']) && !empty($params['limit']) && !empty($params['email'])) {
            $method = WhereRelationLikeConditionQuery::class;
        }

        return $method ?? null;
    }
}
```

- Step 2: After that create a class to generate a query. In this example we make `WhereRelationLikeConditionQuery` class:

```php
use eloquentFilter\QueryFilter\Queries\BaseClause;
use Illuminate\Database\Eloquent\Builder;

/**
 * Class WhereRelationLikeConditionQuery.
 */
class WhereRelationLikeConditionQuery extends BaseClause
{
    /**
     * @param $query
     *
     * @return Builder
     */
    public function apply($query): Builder
    {
        return $query
            ->whereHas('posts', function ($q) {
                $q->where('comment', 'like', "%" . $this->values['like_relation_value'] . "%");
            })
            ->where("$this->filter", '<>', $this->values['value'])
            ->where('email', 'like', "%" . $this->values['email'] . "%")
            ->limit($this->values['limit']);
    }
}
```
- Step 3: You make the method `EloquentFilterCustomDetection` for return array detections of the condition in the model.

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

    public function EloquentFilterCustomDetection(): array
    {
        return [
            WhereRelationLikeCondition::class
        ];
    }

}
``` 

- Each of the query params is used to detect in `WhereRelationLikeCondition` for the first time after that check by default detection eloquent filter.

Make method `EloquentFilterCustomDetection` in the above example and return array conditions class.

```
/users/list?username[value]=mehdi&username[limit]=10&username[email]=mehdifathi&username[like_relation_value]=mehdi&count_posts=10

select * from "users"
 where exists (select * from "posts" where 
"users"."post_id" = "posts"."id" 
and "comment" like ?) and "username" <> ? and "email" like ? and "count_posts" = ? limit 10
```
You just run code ` User::filter();` for see result.

-**Note** Also you can set custom detection on the fly by use of the method `SetCustomDetection`. For example :

```php
$users = User::SetCustomDetection([WhereRelationLikeCondition::class])->filter();
```

-**Note** You can disable `EloquentFilterCustomDetection` on the fly by this code :

```php
 User::SetLoadDefaultDetection(false)->filter();
```

-**Note** You can set many detection conditions. for example:

```php

class User extends Model
{
    use Filterable;
    public function EloquentFilterCustomDetection(): array
    {
        return [
            WhereRelationLikeCondition::class,
            WhereRelationLikeVersion2Condition::class,
            WhereRelationLikeVersion3Condition::class,
        ];
    }
}
```

- `EloquentFilter::getInjectedDetections()` gets all of your customs injected detection.

-**Note** Every custom detection will run before any detections by default eloquent filter.

## Configuring

You can generate a config file to configure Eloquent Filter.

### Publish Config

    php artisan vendor:publish --provider="eloquentFilter\ServiceProvider"

### Config

- You can disable/enable Eloquent Filter in the config file (eloquentFilter.php).


        'enabled' => env('EloquentFilter_ENABLED', true),
    
- Eloquent Filter recognizes every param of the queries string. 
  Maybe you have a query string that you don't want to recognize by Eloquent Filter. You can use `ignoreRequest` for his purpose.
  But we have a clean solution to this problem. You can set param `request_filter_key` in the config file.
  Therefore every query string will recognize by the `request_filter_key` param.

    
        'request_filter_key' => '', // filter

For example, if you set `'request_filter_key' => 'filter',` that Eloquent Filter recognizes `filter` query string.

`
/users/list?filter[email]=mehdifathi.developer@gmail.com`


- You can disable/enable all of the custom detection of Eloquent Filter in the config file (eloquentFilter.php).

   
        'enabled_custom_detection' => env('EloquentFilter_Custom_Detection_ENABLED', true),

- You should set the index array in `ignore_request` to ignore by in all filters.


        'ignore_request' => [] //[ 'show_query','new_trend' ],
    

## Magic Methods

Magic methods are a collection of methods that you can use as a wrapper in the Eloquent Filter.
For example, serialize data before filtering or changing data in response and others.
Now Eloquent Filter have `serializeRequestFilter`,`ResponseFilter`.

### Request Filter

Eloquent Filter has a magic method for just change requests injected before handling by eloquent filter. This method is 
SerializeRequestFilter. You just implement `SerializeRequestFilter` method in your Model. For example


```php

class User extends Model
{
    use Filterable;
    public function serializeRequestFilter($request)
    {
       $request['username'] = trim($request['username']);
       return $request;
    }
}
```

As above code, you can modify every query params of the Model in the method `serializeRequestFilter` before running by Eloquent Filter. 
That is a practical method when you want to set user_id or convert date or remove space and others.

### Response Filter

Response Filter is a magic method for just changing response after handling by Eloquent Filter. The method called 
`ResponseFilter` You should implement the method `ResponseFilter` in your Model. For example

```php

class User extends Model
{
    use Filterable;
    public function ResponseFilter($response)
    {
        $data['data'] = $response;
        return $data;
    }
}
```

- If you have any idea about the Eloquent Filter, I will be glad to hear that.
