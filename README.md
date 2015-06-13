<!-- header start -->
[![Latest Stable Version](https://poser.pugx.org/cupoftea/easycfg/version.svg)](https://packagist.org/packages/cupoftea/easycfg) [![Total Downloads](https://poser.pugx.org/cupoftea/easycfg/d/total.svg)](https://packagist.org/packages/cupoftea/easycfg) [![Latest Unstable Version](https://poser.pugx.org/cupoftea/easycfg/v/unstable.svg)](https://packagist.org/packages/cupoftea/easycfg) [![License](https://poser.pugx.org/cupoftea/easycfg/license.svg)](https://packagist.org/packages/cupoftea/easycfg)

# EasyCFG
### Easily add configuration data to your Eloquent Models or Application in Laravel 5!
<!-- header end -->

EasyCFG is a Configuration Manager for Laravel 5. It provides an easy way to save Configuration and other Metadata.

With EasyCfg, saving data related to other things, wether it is on your Application, a Class or an Object, because a simple task. Some use cases are User Settings and dynamic Application Configuration (e.g. in an Admin Panel), but of course you can use this howevver you like.

 - Documentation (coming soon)
 - API Reference (coming soon)

<!-- (http://easycfg.cupoftea.io/docs/) -->
<!-- (http://easycfg.cupoftea.io/docs/api/) -->

## Quickstart

```bash
$ composer require cupoftea/easycfg ^1.1
```

```php
// Global data
Cfg::set('key', 'value');
$value = Cfg::get('key');

// Class data
cfg()->set('key', 'value', MyConfigurableCommand::class);
$value = cfg('key', MyConfigurableCommand::class);

// Object data (Class instance)
// where $myobject = {"id": 1, "property": "value"}
cfg()->set('key', 'value', $myObject);
cfg()->set('foo', 'bar', MyConfigurableClass::class, $myObject->id);
$cfg = cfg()->all($myObject);


// Settings in Blade partials

// app.blade.php
<div class="content @cfg('scheme')-scheme">
    @yield('content')
</div>

// page.blade.php
@cfg('scheme', 'dark')
@section('content')
    ...
@endsection

// Rendered HTML
<div class="content dark-scheme">
    ...
</div>
```

## Features

 - Simple access to Configuration Data via the Facade or Helper function.
 - Trait to ease setting data on Models or any other Class.
 - Configurable database table.

## TODO:

 - Write full Documentation
 - Generate API reference
