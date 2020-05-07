<h1>Base Entity for Laravel models</h1>

This package provides the base model for laravel models with 6 key fields:
* id ( non numeric / non incremental ) 
* active ( boolean )
* created_by_id ( the id of the creator )
* created_at ( the timestamp of the creating )
* updated_by_id ( the id of the updater )
* updated_at ( the timestamp of the updating )

## Installation

Via Composer

```bash
$ composer require sysbox/laravel-base-entity
```

## Setup

###step 1
Publish the config file
```bash
$ php artisan vendor:publish --provider='Sysbox\LaravelBaseEntity\LaravelBaseEntityServiceProvider'
```
###step 2
Setup your laravel's interface model, like below: 
edit `<laravel-root-dir>/app/User.php`
```php
class User extend BaseModel implements UserReferable <<<<<<<<< implement this interface
{

    /**
     * @return mixed <<<<<<<<< add this function
     */
    public function getUserId()
    {
        return $this->id;
    }

    /**
     * @return \Illuminate\Contracts\Auth\Authenticatable|UserReferable|null
     */
    public static function getCurrentUser() <<<<<<<<< add this function
    {
        return Auth::user();
    }
}
```

###step 3
Setup your config file:
edit `<laravel-root-dir>/config/laravelBaseEntity.php`, you will see something like:
```php
return [


    // this is the user class that will be refer to for fields: created_by_id and updated_by_id
    'user_class' => User::class, <<<<<<<<< add user class here.

    // this is the system user's id,
    // leave it BLANK for auto generation.
    // or specifiy in env, ie: 'system_user_id' => env('SYSTEM_USER_ID'),
    'system_user_id' => '',
];
```


## Usage
Now you should be able to create any Laravel Model and just extend from BaseModel, like this
```php
use Sysbox\LaravelBaseEntity\BaseModel;
class Person extend BaseModel <<<<<<<<< extend your BaseModel
{
    // all other laravel function for the model..
}
```

And you can also create columns in migration, like this:
```php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Sysbox\LaravelBaseEntity\Facades\LaravelBaseEntity;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            LaravelBaseEntity::addBasicLaravelBaseEntityColumns($table);
            $table->string('name');
            //or add column
            // LaravelBaseEntity::addHashIdColumn($table, 'another_user_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}

```