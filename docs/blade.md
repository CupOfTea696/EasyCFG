# Blade Directive
<!-- [[TOC]] -->

EasyCFG also includes a Blade Directive to use simple key / value pairs in your Blade Templates. These settings are not saved to your Database.

## Usage

You can use the `@cfg` directive both to set or retrieve data as follows.

```php
// Setting data
@cfg('foo', 'bar')

// Outputting data
@cfg('foo')
```

## Example

#### app.blade.php

```php
<div class="content @cfg('scheme')-scheme">
    @yield('content')
</div>
```

#### page.blade.php
```php
@extends('app')

@cfg('scheme', 'dark')
@section('content')
    ...
@endsection
```

#### Rendered HTML
```html
<div class="content dark-scheme">
    ...
</div>
```
