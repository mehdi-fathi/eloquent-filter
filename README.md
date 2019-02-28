# eloquentFilter
A package for filter data of models by query string.it uses simplicity.

## Installation

Run the Composer update comand

      $ composer require mehdi-fathi/eloquent-filter

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

Just fields of query string be same rows table database

### Date query filter

If you are going to make query whereBetween.you just send array as the value.you must fill keys from and to in array.
you can set it on query string as you know.

```php
$data = [
            'created_at' => [
                'from' => now()->subDays(10),
                'to' => now()->addDays(30),
            ],
            'updated_at' => [
                'from' => now()->subDays(10),
                'to' => now()->addDays(30),
            ],
            'email' => 'mehdifathi.developer@gmail.com'
        ];
```

### Custom query filter
If you are going to make yourself query filter you can do it easily.you just make a trait and use it on model:

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
    use usersFilter;

    protected $table = 'users';
    protected $guarded = [];

    public function scopeFilter($query, QueryFilter $filters)
    {
        return $filters->apply($query, $this->getTable());
    }
}
```
