---
layout: default
---

# Using EasyCFG
<!-- [[TOC]] -->

## Setting Data

You can use EasyCFG to set Configuration Data globally, or relate it to a specific Class from your Application. To relate Data to a Class, simply pass the full Class Name as a third parameter.

```php
EasyCfg::set('foo', 'bar');
EasyCfg::set('login.redirect', 'dashboard', UserController::class);
```

## Retrieving Data

Retrieving Data is just as easy as setting it. You can also retrieve an array of all stored Data. This will either retrieve all Gloabal Configuration Data or Data related to a Class.

```php
$foo = EasyCfg::get('foo');
$login_action = EasyCfg::get('login.redirect', UserController::class);

$all_global = EasyCfg::all(); // ['foo' => 'bar']
$all_class = EacyCfg::all(UserController::class); // ['login.redirect' => 'dashboard']
```

## Deleting Data

Lastly, you can delete Configuration Data with the `delete` Method, or delete all stored Data with the `deleteAll` method.

```php
EasyCfg::delete('foo');
EasyCfg::deleteAll();

EasyCfg::delete('login.redirect', UserController::class);
EasyCfg::deleteAll(UserController::class);
```
