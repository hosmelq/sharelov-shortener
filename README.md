## Sharelov-Shortener

# Installation

Add private repository to `composer.json` as we are using bitbucket you need to install an ssh key.


```json
"repositories":[
    {
        "type": "vcs",
        "url":  "git@bitbucket.org:chatagency/url_shortener.git"
    }
]
```

Require this package in your `composer.json` and update composer. This will download the package.

```json
"sharelov/sharelov-shortener":"dev-master"
```
After updating composer, add the ServiceProvider to the providers array in `config/app.php`

```php
Sharelov\Shortener\ShortenerServiceProvider::class,
```

You can use the facade for shorter code. Add this to your aliases:

```php
'Shortener'=>Sharelov\Shortener\Facades\Shortener::class,
```

Finally run `composer dump-autoload` to reload the files, `php artisan vendor:publish` to get the migration files,then run `php artisan migrate` to install links table.

# Usage

Use this route to decode a shortened URL, if the URL don't exist it will run `abort(404)` otherwise it will perform a redirect to the stored url asociated with that hash.
```php
Route::get('/sh/{hash}',[
    'as'=>'shortener.get',
    'uses'=>'\Sharelov\Shortener\Controllers\LinksController@translateHash'
]);
```

Use this route to short a URL, if the URL already exist on the database it will return the hash code otherwise it will create  a new URL|Hash tuple and it will return a json response with the hash and the url with the current domain indicating the status of the request.

The url recive the next as query parameters:

Url to process by the shortener:

    `url=urltoshorten`

Expiration date for the link (don't specify this in order to make the link not expire)

    `expires_at=Y-m-d`

You can have an example of the requests on this postman collection:
[PostMan Shortener Collection](https://www.getpostman.com/collections/ec779d63f1fe3af3bc6d)

```php
Route::post('/sh',[
    'as'=>'shortener.store',
    'uses'=>'\Sharelov\Shortener\Controllers\LinksController@store'
]);
```
Succesfull json response
```json
{
    "success": "true",
    "hash": "D7ZR6",
    "url": "http://sharelovdev.dev/sh/D7ZR6"
}
```
Unsuccessfull json response
```json
{
    "success": "false",
    "hash": "",
    "url": ""
}
```

For using the facade don't forguet to include it `use Shortener;` and then you can call the next methods:

Returns the hash for the url sended as a parameter and stores an object in the links table.
```php
Shortener::make($url)
```

Returns the url corresponding to a hash string in the database.
```php
Shortener::getUrlByHash($hash)
```

# Configuration

After you inovque `vendor:publish` a file by the name shortener.php will appear on the config folder, you can then specify there the domain name and the url to build the shortened urls. You could use these same configuration file for building the entry point for retrieving the urls.

```php
    'domain' =>'http://localhost:8000',
    'path'=>'sh',
```