# Relating to Objects
<!-- [[TOC]] -->

## Configurable Trait

To easily relate Configuration to Models or other Objects, EasyCFG provides you with a Configurable Trait. This Trait will either use the `primaryKey` for models or the `id` property for other Objects to save the data. This trait uses the PHP Magic Methods `__get`, `__set` and `__unset` so any properties your Object may have must be defined. If you use this on a Model, you must add a `$fields` array containing all the Database Fields for the Model.

```php

use CupOfTea\EasyCfg\Configurable

class User extends Model
{
    use Configurable;
    
    $fields = ['username', 'password', 'email', 'language'];
}

$user->language = 'en_UK'; // Saved on Model table
$user->landing_page = 'favourites'; // Saved as Configuration data
```

When calling the `delete` Method on a configurable Model, all related Configuration Data will also be removed from the Database.

## Manually Using Data

If you don't want to use the Configurable Trait, you can still use the EasyCFG Facade to use Releted Data.

```php
EasyCfg::set('landing_page', 'favourites', $user);
EasyCfg::get('landing_page', User::class, 5);
EasyCfg::all($user);
EacyCfg::delete('landing_page', $user);
EasyCfg::deleteAll(User::class, 2);
```