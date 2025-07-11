# Eloquent Filter [![Tweet](https://img.shields.io/twitter/url/http/shields.io.svg?style=social)](https://twitter.com/intent/tweet?text=Eloquent%20Filter%20adds%20custom%20filters%20to%20your%20Eloquent%20Models%20in%20Laravel%26url%3Dhttps%3A%2F%2Fgithub.com%2Fmehdi-fathi%2Feloquent-filter%26via%3Dmehdi-fathi%26hashtags%3Dlaravel%2Celoquent%2Cphp%2Cmysql%2Cdatabase%2Celoquentfilter)

![alt text](./eloquent-filter.png "eloquent-filter")

[![Latest Stable Version](https://img.shields.io/packagist/v/mehdi-fathi/eloquent-filter)](https://packagist.org/packages/mehdi-fathi/eloquent-filter)
![Run tests](https://github.com/mehdi-fathi/eloquent-filter/workflows/Run%20tests/badge.svg?branch=master)
[![License](https://poser.pugx.org/mehdi-fathi/eloquent-filter/license)](https://packagist.org/packages/mehdi-fathi/eloquent-filter)
[![GitHub stars](https://img.shields.io/github/stars/mehdi-fathi/eloquent-filter)](https://github.com/mehdi-fathi/eloquent-filter/stargazers)
[![Monthly Downloads](https://img.shields.io/packagist/dm/mehdi-fathi/eloquent-filter?color=blue)](https://packagist.org/packages/mehdi-fathi/eloquent-filter)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/mehdi-fathi/eloquent-filter/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/mehdi-fathi/eloquent-filter/?branch=master)
[![codecov](https://codecov.io/gh/mehdi-fathi/eloquent-filter/branch/develop/graph/badge.svg?token=QY9HA6NXFH)](https://codecov.io/gh/mehdi-fathi/eloquent-filter)

Eloquent Filter is a robust Laravel package providing an intuitive way to filter your Eloquent models using query
strings.
Perfect for crafting responsive APIs and complex data sets, this package seamlessly integrates with Laravel's existing
Eloquent models,
adding powerful and dynamic filtering capabilities with minimal setup.

Features:

- Support for complex query structures
- Easy to override conditions for custom behavior
- Harmonious integration with Laravel and Eloquent, including query builders
- Full control over filter execution and customization
- Support rate limit feature

We've tailored Eloquent Filter to be as flexible as you need—whether your queries are straightforward or complex.
With an extensive feature set, you can implement specific functionalities unique to your application with ease.

**Note** We considered what predictable features you wanted to implement no matter simple or complex,
although we have a lot of features to make able you to implement your specific something's else.

## Table of content

- [Introduction](#microphone-introduction)
- [Installation](#electric_plug-installation)
- [Basic Usage](#book-basic-usage)
    - [Config Model](#config-model-and-set-whitelist)
    - [Use in Controller](#use-in-controller)
    - [Conditions Guidance Table](#conditions-guidance-table )
    - [Simple Examples](#simple-examples)
    - [Custom Query Filter](#custom-query-filter)
    - [Request encoded](#request-encoded)
    - [Custom Detection Condition](#custom-detection-condition)
- [Configuring](#configuring)
    - [Publish Config](#publish-config)
    - [Config](#config)
    - [Alias](#alias)
- [Query Builder](#query-builder-introduction)
- [Magic Methods](#magic-methods)
    - [Request Filter](#request-filter)
    - [Request Field Cast Filter](#request-field-cast-filter)
    - [Response Filter](#response-filter)
    - [Black List Detections](#black-list-detections)
    - [Macro Methods](#macro-Methods)
- [Rate Limiting](#rate-limiting)
    - [Configuration](#configuration)
    - [Using Rate Limiting](#using-rate-limiting)
- [Fuzzy Search](#fuzzy-search)
    - [Using Fuzzy Search](#using-fuzzy-search)
    - [How It Works](#how-it-works)
    - [Supported Character Variations](#supported-character-variations)
    - [Examples](#examples)
    - [Performance Considerations](#performance-considerations)

## Requirements

- PHP 8.0 - 8.1 - 8.2 - 8.3 - 8.4
- Laravel 8.x - 9.x - 10.x - 11.x - 12.x (New)

### Versions Information

| Major Version | Version       | Status         | PHP Version | Laravel Version |
|---------------|---------------|----------------|-------------|-----------------|
| ^4.0          | 4.5.0 - 4.x.x | Active support | >= 8.2      | >= 12.x         |
| ^4.0          | 4.2.0 - 4.4.9 | Active support | >= 8.2      | > 11.0  <= 11.x |
| ^4.0          | 4.0.x - 4.1.5 | Active support | >= 8.0      | >= 9.x <= 10.x  |
| ^3.0          | 3.2.x - 3.4.x | End of life    | >= 8.0      | >= 9.x          |
| ^3.0          | 3.0.0 - 3.0.5 | End of life    | >= 7.4.0    | >= 5.6.x <= 8.x |
| ^2.0          | 2.0.0 - 2.6.7 | End of life    | <= 7.4.0    | >= 5.x  <= 5.4  |

## :microphone: Introduction

Conceivably, you would face challenges if you've done a task as an end-point in which there are some queries with many
advanced options.

Let's say we want to make an advanced search page with multiple filter options.

![](https://raw.githubusercontent.com/mehdi-fathi/eloquent-filter/master/Aban-21-1402%2014-27-04.gif)

### A simple implementation without Eloquent Filter

The Resource URI would be look like:

    /users/index?age_more_than=25&gender=male&created_at=25-09-2019

And a simple implementation in the Controller would look like :

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

        return json_encode($users->get());
    }
}
```

This solution is simple and that works well but not for an enterprise project.
But you'd have to add a condition for each filter you need.
Especially if you would make more complex filtering, your code can become a monster quickly! :boom:

Hence, Eloquent Filter is ready for to you get rid of complexity in addition to saving time.

### A simple implementation with Eloquent Filter

Eloquent Filter can help you to manage these features. Just you will set query string to work with that.
It would make your own query automatically and systematically while you can control them.

Right after installing Eloquent Filter, the request URI would be like this:

    /users/list?age_more_than[operator]=>&age[value]=35&gender=male&created_at[operator]==>&created_at[value]=25-09-2019

And in the Controller, You just need that one line:

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

By Eloquent filter implementation, you can use all the documented filters!

## :electric_plug: Installation

1- Run the following command in your project directory to add the Eloquent Filter as a dependency

        $ composer require mehdi-fathi/eloquent-filter

- **Note** We support auto-discovery but you can check them.

2- Add `eloquentFilter\ServiceProvider::class` to provider `app.php`

   ```php
   'providers' => [
     /*
      * Package Service Providers...
      */
      eloquentFilter\ServiceProvider::class
   ]
   ```
- In latest Laravel version add it to `providers.php`:

   ```php
    return [
        App\Providers\AppServiceProvider::class,
        eloquentFilter\ServiceProvider::class
    ];
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

- There is no need any change for Laravel 12. 

That's it enjoy! :boom:

## :book: Basic Usage

### Config Model and set whitelist

Add the `Filterable` trait to yourself models and set fields in the whitelist array in which you will want to use of
filter .
You can override this method in your models as well.

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

- You can set `*` char for that filter in all fields alike below example:

```php
private static $whiteListFilter = ['*'];
```

You able add or set `$whiteListFilter` on the fly in your method. For example:

***Set array to WhiteListFilter***

- **Note** This method override `$whiteListFilter` array

```php
User::setWhiteListFilter(['name']); 
```

***Add new field to WhiteListFilter***

```php
User::addWhiteListFilter('name'); 
```

-**Note** Just in case, you must set `$whiteListFilter` in Models. Aim of the method avert to manipulation query string
by a bad user.

### Conditions Guidance Table

- To better understand this, I provided a table of all conditions and samples. It represents how eloquent filter
  detect params and each param what query would make.

| Condition Name           | Eloquent Method | Param                                                        | Example                                                                 | Eloquent  | DB   |
|--------------------------|-----------------|--------------------------------------------------------------|-------------------------------------------------------------------------|-----------|------|
| WhereCustomCondition     |                 |                                                              | Declared custom<br/> method of Model                                    | ✅         | ❌  |
| SpecialCondition         |                 | f_params[limit]=10                                           | support f_params, e.g:<br/> limit and order                             | ✅         | ✅    |
| WhereBetweenCondition    | whereBetween    | created_at[start]=2016/05/01<br/>&created_at[end]=2017/10/01 | whereBetween(<br/>'created_at',<br/> [{start},{end}])                   | ✅         | ✅    |
| WhereByOptCondition      | where           | count_posts[operator]=>&<br/>count_posts[value]=35           | where('count_posts',<br/> ">", $value)                                       | ✅         | ✅    |
| WhereLikeCondition       | where           | first_name[like]=John                                        | where('first_name',<br/> 'like', $value)                                    | ✅         | ✅    |
| WhereInCondition         | whereIn         | username[]=David&<br/>username[]=John12                      | whereIn('username', $value)                                               | ✅         | ✅    |
| WhereOrCondition         | orWhere         | username=Bill&<br/>or[username]=James                        | orWhere('username', $value)                                               | ✅         | ✅    |
| WhereHas                 | WhereHas        | posts[title]=sport one                                       | whereHas('posts',<br/>function ($q) <br/>{$q->where('title', $value)}); | ✅         | ✅    |
| WhereDoesntHaveCondition | whereDoesntHave | doesnt_have=category                                         | doesntHave($value)                                                      | ✅         | ✅    |
| WhereDateCondition       | whereDate       | created_at=2024-09-01                                        | whereDate('created_at', $value)                                             | ✅         | ✅    |
| WhereYearCondition       | whereYear       | created_at[year]=2024                                        | whereYear('created_at', $value)                                            | ✅         | ✅    |
| WhereMonthCondition      | whereMonth      | created_at[month]=3                                          | whereMonth('created_at', $value)                                           | ✅         | ✅    |
| WhereDayCondition        | whereDay        | created_at[day]=15                                           | whereDay('created_at', $value)                                             | ✅         | ✅    |
| WhereNullCondition       | whereNull       | username[null]=true                                          | whereNull('username')                                                    | ✅         | ✅    |
| WhereNotNullCondition    | whereNotNull    | username[not_null]=true                                      | whereNotNull('username')                                                  | ✅         | ✅    |
| WhereCondition           | where           | username=Mehdi                                               | where('username', $value)                                                 | ✅         | ✅    |

### Simple Examples

You just pass data form as query string. For example:

**Simple Where**

```
/users/list?email=mehdifathi.developer@gmail.com

SELECT ... WHERE ... email = 'mehdifathi.developer@gmail.com'
```

```
/users/list?first_name=mehdi&last_name=fathi

SELECT ... WHERE ... first_name = 'mehdi' AND last_name = 'fathi'
```

- If you send date format `Y-m-d` we will work like `WhereDate()` method Laravel.

```
/users/list?created_at=2024-09-01

SELECT ... WHERE ... strftime('%Y-%m-%d', "created_at") = cast(2024-09-01 as text)
```

***Where In***

This example make method `whereIn`.

```
/users/list?username[]=ali&username[]=ali22&family=ahmadi

SELECT ... WHERE ... username in ('ali','ali22') AND family = 'ahmadi'
```

***OrWhere***

This example make method `orWhere()`.

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

**Where the nested relations Model**

You can set all nested relations in the query string just via the array of query string. Imagine, the user model has a
relation with posts. And posts table has a relation with orders table.

You can make query conditions by set `posts[count_post]` and `posts[orders][name]` in the query string.

- Just be careful you must set `posts.count_post` and `posts.orders.name` in the User model.

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

- The above example is the same code that you used without the eloquent filter. Check it under code. It's not amazing?

```php
$builder = (new User())->with('posts');
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

**Doesnthave Where (new feature)**

```
/tags/list?doesnt_have=category

select * from "tags" where not exists (select * from "categories" where "tags"."foo_id" = "categories"."id")'
```

- To fetching those data that doesn't have any relationship with the model as the same `Doesnthave` method worked.

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

Therefore, fields of query string are same rows table database in `$whiteListFilter` in your model or declare the method in your model as override method.
The overridden method can be considered a custom query filter.

### Custom Query Filter

Eloquent Filter doesn't support all the conditions by default. For this situation, you can make an overridden method.
If you are going to make yourself a query filter, you can do it easily.
You should take care of using `filterCustom` before method name in new version.

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
    public function filterCustomSample_like(Builder $builder, $value)
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

### Some Filter methods

```php
User::filter()->paginate();
```

- `EloquentFilter::filterRequests()` : get all params that used by the Eloquent Filter.
  You can set key to get specific index.
  For example `EloquentFilter::filterRequests('username')` it's getting username index.

- `EloquentFilter::getAcceptedRequest()` : get all params that set by the AcceptRequest method.

- `EloquentFilter::getIgnoredRequest()` : get all ignored params that set by the getIgnoreRequest method.


### Request encoded

In particular projects, We don't want to share our request filters with all users. 
It means every single user should have a unique valid url for duplicated search
then It works just for the same user.

- `EloquentFilter::getRequestEncoded()` : get passed request as encoded request based on default.
- `EloquentFilter::setRequestEncoded()` : set request encoded with a selective salt.

You just need to pass `hashed_filters` as key to detect hashed_filter and encode them in the core. Every single user has a unique url per user. In addition, nobody can manipulate parameters in order to get specific data.

```
/users/list?hashed_filters=MXsidGl0bGUiOiJzcG9ydCJ9

SELECT ... WHERE ... name = 'mehdi'
```
- You make sure adjust `request_salt` in config.php. You'd better set a unique value like user_id or ip.

For this purpose, You had better have an end-point to set and get request encoded then pass it to your main end-point for result.

### Custom Detection Conditions

Sometimes you want to make your custom condition to make a new query that Eloquent Filter doesn't support by default.
The good news is you would make a custom condition in the eloquent filter from now on.

You can make conditions to generate a new query after checking by that. For example:

We must have two classes. The First detects conditions second class generates the query.

- Step 1: Create a class to detect some conditions

```php

use eloquentFilter\QueryFilter\Detection\Contract\ConditionsContract;

/**``
 * Class WhereRelationLikeCondition.
 */
class WhereRelationLikeCondition implements ConditionsContract
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

- Step 2:Right after, create a class to generate a query. In this example we make `WhereRelationLikeConditionQuery`
  class:

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

- Each of the query params is used to detect in `WhereRelationLikeCondition` for the first time after that check by
  default detection eloquent filter.

Make method `EloquentFilterCustomDetection` in the above example and return array conditions class.

```
/users/list?username[value]=mehdi&username[limit]=10&username[email]=mehdifathi&username[like_relation_value]=mehdi&count_posts=10

select * from "users"
 where exists (select * from "posts" where 
"users"."post_id" = "posts"."id" 
and "comment" like ?) and "username" <> ? and "email" like ? and "count_posts" = ? limit 10
```

You just run code ` User::filter();` for see result.

- `Model::setLoadInjectedDetection(false)` : You can deactivate custom detection conditions on the fly.

-**Note** as well, you can set custom detection on the fly by use of the method `SetCustomDetection`. For example :

```php
$users = User::SetCustomDetection([WhereRelationLikeCondition::class])->filter();
```

-**Note** You can disable `EloquentFilterCustomDetection` on the fly by this code :

```php
 User::SetLoadDefaultDetection(false)->filter();
```

-**Note** You can set many detection conditions. e.g:

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

You can publish the configuration file to customize the package further:

### Publish Config

    php artisan vendor:publish --provider="eloquentFilter\ServiceProvider"

### Config

- You can disable/enable Eloquent Filter in the config file (eloquentFilter.php).

        'enabled' => env('EloquentFilter_ENABLED', true),

- Eloquent Filter recognizes every param of the queries string.
  Maybe you have a query string that you don't want to recognize by Eloquent Filter. You can use `ignoreRequest` for his
  purpose.
  But we have a clean solution to this problem. You can set param `request_filter_key` in the config file.
  Therefore, every query string will recognize by the `request_filter_key` param.

        'request_filter_key' => '', // filter

For example, if you set `'request_filter_key' => 'filter',` that Eloquent Filter recognizes `filter` query string.

`
/users/list?filter[email]=mehdifathi.developer@gmail.com`

- You can disable/enable all the custom detection of Eloquent Filter in the config file (eloquentFilter.php).

        'enabled_custom_detection' => env('EloquentFilter_Custom_Detection_ENABLED', true),

- You should set an index array `ignore_request` to ignore all filters.

        'ignore_request' => [] //[ 'show_query','new_trend' ],

- You had better keep `max_limit`. It's a limitation for preventing making awful queries mistakenly by the developer or
  intentionally by a villain user.

        'max_limit' => 20

- With `filtering_keys` ,You have a place to declare some provided key and use it in filtering.

        'filtering_keys'=>[
          'title_sport_advanced' => [
              'title' => 'sport',
              'created_at' => [
                  'start' => '2019-01-01 17:11:46',
                  'end' => '2019-02-06 10:11:46',
              ],
              'sub_cat' => [
                  'news 1', 'news 2'
              ],
          ]
        ]

  Then you just need to pass `config('eloquentFilter.filtering_keys.title_sport_advanced')` to filter method.

- From now on , we have the ability to record logs by logger instance. Since queries is made dynamically somehow , the
  need of feature keeping queries with their time is required.
  So we added it in this version with some other options to better management.

        'log' => [
        'has_keeping_query' => false,
        'max_time_query' => null,
        'type' => 'eloquentFilter.query'
        ]

It's disable by default you enable by `has_keeping_query`, `type` is type log ,and `max_time_query` is a value for
keeping queries with high time-executed.

### Alias

Sometimes you may want to change some parameters in the URL while those mention a field of the model.
e.g. name of the input form is not similar to the model ,or you want to change them for other reasons so the alias as a
new feature can be useful.

```php

    class Stat extends Model
    {
        use Filterable;
        /**
         * @var array
         */
        private static $whiteListFilter = [
            'type',
            'national_code',
        ];

        /**
         * @var array
         */
        private $aliasListFilter = [
            'national_code' => 'code',
        ];
    }

```

Then you should send the `code` param in the URL for making a query with the national code field of the model readily.

# Query Builder Introduction

Great news!

Some people asked me a lot to add new feature to support Laravel query builder.
It needed a lot of energy and devoting time , so I decided to implement it.
It's quite tough however finally it's almost done now.

We are supporting query builder along with eloquent from now on. Not only you would use query builder ,but also you can
use eloquent at the same time.

It's a new feature ,and I'm snowed under the code to fix issues. Anyway this feature is up right now with just some
limitation.
We don't support `WhereCustomCondition` for query builder at the moment but other conditions were ready
to use.
in addition, we don't have any kind of `whitelist` , `blacklist` , `custom detectioon` or `alias`.
currently , It's just a simple feature.

- Usage of them is just extremely like model just you need use filter as a method. Obviously, there's no need any change
  like use trait or etc.

```php
 DB::table('users')->filter();
```

## Magic Methods

Magic methods are a collection of methods that you can use as a wrapper in the Eloquent Filter.
For example, serialize data before filtering or changing data in response and others.
Now Eloquent Filter have `serializeRequestFilter`,`ResponseFilter` and , etc.

### Request Methods

Call `ignoreRequest` (static scope) or `ignoreRequestFilter` will ignore some requests that you don't want to use in
conditions of eloquent filter.

Change your code the controller of the laravel project as like below example:

```php
          
  $users = User::ignoreRequest(['name'])
            ->filter()
            ->with('posts')
            ->orderByDesc('id')
            ->paginate(request()->get('perpage'),['*'],'page');

```

```php
  $user = new User();
  $users = $user->ignoreRequestFilter(['name','family'])
            ->filter()
            ->with('posts')
            ->orderByDesc('id')
            ->paginate(request()->get('perpage'),['*'],'page');

```

-**Note** The Eloquent Filter config by default uses the query string to make queries in Laravel.
Although, you can set the collection data in the `filter` method Model for making your own custom condition without
query string.

-**Note**  Therefore you must unset yourself param as perpage. Just you can set page param for paginate this param
ignore from the filter.

- You can ignore some request params via use of the bellow code.

```php

User::ignoreRequest(['perpage'])
            ->filter()
            ->paginate(request()->get('perpage'), ['*'], 'page');
```

e.g: the `perpage` param will never be in the conditions eloquent filter.
It has to do with to the paginate method. `page` param ignore by default in Eloquent Filter of Laravel.

- You are able to filter some request params as acceptable filter`.

Calling `AcceptRequest` (static scope) or `acceptRequestFilter` will accept requests in which you want to use in
conditions Eloquent Filter.
e.g: `username` and `id` key will be in the conditions eloquent filter.

```php
``
User::AcceptRequest(['username','id'])
            ->filter()
            ->paginate(request()->get('perpage'), ['*'], 'page');
```

```php

$user = new User();
$user->acceptRequestFilter(['username','id'])
            ->filter()
            ->paginate(request()->get('perpage'), ['*'], 'page');
```

### Request Filter

Eloquent Filter has a magic method for just change requests injected before handling by eloquent filter. This method is
SerializeRequestFilter. You just implement `SerializeRequestFilter` method in your Model. For example

```php

class User extends Model
{
    use Filterable;
    
    private static $whiteListFilter =[
        'username'
    ];
    
    public function serializeRequestFilter($request)
    {
       $request['username'] = trim($request['username']);
       return $request;
    }
}
```

As above code, you can modify every query params of the Model in the method `serializeRequestFilter` before running by
Eloquent Filter.
That is a practical method when you want to set user_id or convert date or remove space and others.

### Request Field Cast Filter

Eloquent Filter requires a bunch of specific methods for each of the fields before going on filter process.
This feature has been implemented recently. By this `filterSet` + `field` method in your model, You
will be able to add some change for that particular field.

```php

class Category extends Model
{
    use Filterable;
    
    private static $whiteListFilter =[
        'desc'
    ];
    
    public function filterSetDesc($value)
    {
        return trim($value);
    }
}
```

### Response Filter

Response Filter is an overriding method for changing response right after handle by Eloquent Filter. The method called
`getResponseFilter` and You could implement the method `getResponseFilter` in your Model. e.g:

```php

class User extends Model
{
    use Filterable;
    public function getResponseFilter($response)
    {
        $data['data'] = $response;
        return $data;
    }
}
```

- You are capable of passing a callback function to `getResponseFilter` method to change the response. We only have this
  feature in query builder DB.

```php

$categories = DB::table('categories')->filter()->getResponseFilter(function ($out) {

    $data['data'] = $out;

    return $data;
});

```

### Black List Detections

Obviously, you never want all users who are able to get data by manipulating requests. As a result, we'd better have
an eloquent control feature.
Although we have this ability on request side, we need this feature on Eloquent side as well.

We would set a blacklist detection to prevent making conditions by using it. Therefore, that list has been disabled in
making conditions. for example:

```php

namespace App\Http\Controllers;

/**
 * Class UsersController.
 */
class UsersController
{

    public function list()
    {
              $users = User::setBlackListDetection(
                  [
                      'WhereCondition',
                  ]
                )->filter()
                ->orderByDesc('id')
                ->paginate();
    }
}
```

- You are able to set on Model layer as well. `black_list_detections` array is used for this purpose.

```php
<?php

namespace Tests\Models;

use eloquentFilter\QueryFilter\ModelFilters\Filterable;
use Illuminate\Database\Eloquent\Model;

class Car extends Model
{
    use Filterable;
    
    private static $whiteListFilter = '*';

    protected $black_list_detections = [
        'WhereCondition',
    ];
}
```

### Macro Methods

-`isUsedEloquentFilter` is a macro method for builder/DB to check either query used eloquent-filter.

-`getDetectionsInjected` is a macro method to get list array of injected objects.

e.g:

```php
    $users = User::SetCustomDetection([WhereRelationLikeCondition::class])->filter();
    echo $users->isUsedEloquentFilter(); // will true
    echo $users->getDetectionsInjected(); // will showing a list array of injected objects
    $categories = DB::table('categories')->filter();
    echo $categories->isUsedEloquentFilter(); // will true
    
```

## Rate Limiting

Eloquent Filter includes a built-in rate limiting feature to protect your application from excessive filter requests. This feature helps prevent abuse and ensures optimal performance.

### Configuration

First, publish the configuration file if you haven't already:

```bash
php artisan vendor:publish --provider="eloquentFilter\ServiceProvider"
```

In your `config/eloquent-filter.php` file, you can configure rate limiting:

```php
return [
    // ... existing config ...

    'rate_limit' => [
        // Enable or disable rate limiting
        'enabled' => true,

        // Maximum number of attempts within the decay time
        'max_attempts' => 60,

        // Decay time in minutes
        'decay_minutes' => 1,

        // Whether to include rate limit headers in the response
        'include_headers' => true,

        // Custom error message for rate limit exceeded
        'error_message' => 'Too many filter requests. Please try again later.',
    ],
];
```

### Using Rate Limiting

Rate limiting is automatically applied when you use the `filter()` method on your models. No additional configuration is required in your models.

```php
use App\Models\User;

// This query will be rate limited according to your configuration
$users = User::filter()->get();
```

### Rate Limit Headers

When rate limiting is enabled and `include_headers` is set to true, the following information is available in the request attributes:

- `X-RateLimit-Limit`: Maximum number of requests allowed
- `X-RateLimit-Remaining`: Number of requests remaining in the current window
- `X-RateLimit-Reset`: Time in seconds until the rate limit resets

### Handling Rate Limit Exceeded

When the rate limit is exceeded, a `ThrottleRequestsException` will be thrown with the configured error message. You can catch this exception in your exception handler:

```php
use Illuminate\Http\Exceptions\ThrottleRequestsException;

public function render($request, Throwable $exception)
{
    if ($exception instanceof ThrottleRequestsException) {
        return response()->json([
            'message' => $exception->getMessage(),
        ], 429);
    }

    return parent::render($request, $exception);
}
```

### Custom Rate Limiting

You can customize the rate limiting behavior by modifying the configuration or extending the `RateLimiting` trait in your own implementation.

## Fuzzy Search

Eloquent Filter now supports fuzzy searching, which helps find results even when there are typos or variations in the search term. This is particularly useful for user input where exact matches might not be possible.

### Using Fuzzy Search

To use fuzzy search, add the `fuzzy` parameter to your filter:

```php
// URL: /users/list?name[fuzzy]=jhon
// Will match: "John", "Jhon", "J0hn", etc.

$users = User::filter()->get();
```

### How It Works

The fuzzy search feature:

1. Converts the search term into a pattern that accounts for common variations
2. Handles common character substitutions (e.g., '0' for 'o', '1' for 'l')
3. Is case-insensitive by default
4. Uses SQL LIKE queries with wildcards for flexible matching

### Supported Character Variations

The fuzzy search supports common character variations:

| Original | Variations |
|----------|------------|
| a        | a, @, 4    |
| e        | e, 3       |
| i        | i, 1, !    |
| o        | o, 0       |
| s        | s, 5, $    |
| t        | t, 7       |
| g        | g, 9       |
| l        | l, 1       |
| z        | z, 2       |
| b        | b, 8       |

- You are able to edit this list on `config.php`.

### Examples

```php
// Will match "John Smith"
/users/list?name[fuzzy]=j0hn

// Will match "test@example.com"
/users/list?email[fuzzy]=test@example.c0m

// Will match "user_name"
/users/list?username[fuzzy]=us3r_n4m3
```

### Performance Considerations

- Fuzzy search uses SQL LIKE queries with wildcards, which can be slower than exact matches
- Consider adding appropriate indexes to columns that will be frequently searched
- For large datasets, consider implementing a more sophisticated search solution (e.g., Elasticsearch)

## Contributing

If you'd like to contribute to Eloquent Filter, please fork the repository and create a pull request. We welcome
contributions of all kinds, including bug fixes, new features, and documentation improvements.

## Proposed Features (Under Consideration)

We are constantly working to improve our package and have planned the following features for upcoming releases:

- Configurable Filter Presets: Implement the ability to define and save filter presets.
  This feature would allow users to quickly apply common sets of filters without having to specify them each time.

Your contributions are always welcome! If you would like to help with the development of these features.

## License

Eloquent Filter is open-source software licensed under the [MIT license](https://opensource.org/licenses/MIT).

## Contact

If you have any questions or feedback about Eloquent Filter, please feel free to contact us at
mehdifathi.developer@gmail.com. We'd love to hear from you!

## Acknowledgements

We'd like to thank the Laravel community for their support and contributions to this project.

