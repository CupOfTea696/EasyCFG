---
layout: default
---

# Helper Function
<!-- [[TOC]] -->

If you prefer it, you can also use EasyCfg as a helper method. It is a shortcut for the `get` method, or when called without any arguments, it returns the EasyCFG Class, so you can chain any of the other methods.

```php
$foo = cfg('foo');
$login_action = cfg('login.redirect', UserController::class);
$landing_page = cfg('landing_page', $user);

$all = cfg()->all();
cfg()->delete('foo');
```
