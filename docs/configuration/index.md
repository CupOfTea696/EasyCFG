---
layout: default
---

# Configuration
<!-- [[TOC]] -->

## Publishing the Configuration

To publish the EasyCFG configuration file in your Application, run the following command

```bash
$ php artisan vendor:publish --provider="cupoftea/easycfg" --tag="config"
```

Once you have done that, you can find the EasyCFG configuration inside `config/easycfg.php`. This step is optional, and if you know which settings you want to change, you can just create the `easycfg/twostream.php` file yourself and place those settings inside.

## Configuring EasyCFG

You only need to overwrite the settings you want to change. You can remove any items where you want to use the default settings from your Application's EasyCFG Configuration file. Below is a list of all avialable settings.

 - `autosave`: Automatically save Config data on models. When turned off, Config data will only be saved when you call `$model->save()`.
 - `table`: The Table name EasyCFG should use in your database.
