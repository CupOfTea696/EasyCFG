---
layout: default
---

# Installation
<!-- [[TOC]] -->

## Install Composer

To install TwoStream, you will first need to install [Composer](https://getcomposer.org/doc/00-intro.md) if you haven't already.


## Install EasyCFG

### Install via Composer

To install EasyCFG, simply require it with Composer.

```bash
$ composer require cupoftea/easycfg ^1.1
````

### Setting up EasyCFG

You will need to add the following service providers to your `config/app.php`:

```php
	'providers' => [
        
		/*
		 * Laravel Framework Service Providers...
		 */
        
        'Illuminate\Foundation\Providers\ArtisanServiceProvider',
        'Illuminate\Auth\AuthServiceProvider',
        'Illuminate\Bus\BusServiceProvider',
        
        ...
        
        'CupOfTea\EasyCFG\EasyCFGServiceProvider',
        
	],
```

Optionally you can also add the EasyCFG Facade if you wish to use it.

```php
    'aliases' => [
        
		'App'       => 'Illuminate\Support\Facades\App',
		'Artisan'   => 'Illuminate\Support\Facades\Artisan',
		'Auth'      => 'Illuminate\Support\Facades\Auth',
		
		...
		
        'EasyCFG' => 'CupOfTea\TwoStream\Facades\EasyCFG',
        
	],
```
### Database Migrations

Lastly you will need to run the EasyCFG migrations. First publish them with the following command:

```bash
$ php artisan vendor:publish --provider="cupoftea/easycfg" --tag="migrations"
```
Now just run `php artisan migrate` and you're all set!

_**Note:** If you wish to change the default Database Table name, you need to change this in the [Configuration](http://easycfg.cupoftea.io/docs/configuration) before running the migrations._
