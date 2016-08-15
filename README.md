laravel-lang-import-export
==========================

This package provides artisan commands to import and export language files from and to CSV. This can be used to send translations to agencies that normally work with Excel-like files.

It turns some navigation.php file...

```php
<?php

return [
  'commands' => [
    'next' => 'Next',
    'prev' => 'Previous',
    'play' => 'Play',
  ],
  'tips' => [
    'next' => 'Navigate to the next item',
    'prev' => 'Navigate to the previous item',
    'play' => 'Autoplay the slide show',
  ],
];
```
...to the following CSV...

```CSV
navigation.commands.next,Next
navigation.commands.prev,Previous
navigation.commands.play,Play
navigation.tips.next,"Navigate to the next item"
navigation.tips.prev,"Navigate to the previous item"
navigation.tips.play,"Autoplay the slide show"

```
...and vice versa.

Installation
------------

Add the following line to the `require` section of your Laravel webapp's `composer.json` file:

```javascript
    "require": {
        "ufirst/lang-import-export": "dev-master"
    }
```


Run `composer update` to install the package.


Finally add the following line to the `providers` array of your `app/config/app.php` file:

```php
    'providers' => [
        /* ... */
        UFirst\LangImportExport\LangImportExportServiceProvider::class
    ]
```

Usage
-----

The package currently provides two commands, one for exporting the files and one for importing them back:

### Export

```bash
php artisan lang-export:csv en navigation
php artisan lang-export:csv --mirror=sv,es,ch en navigation
php artisan lang-export:csv --output /some/file en navigation
php artisan lang-export:csv --delimiter=";" --enclosure='"' --output=/some/file en navigation
```

You have to pass the __locale__ and the __group__ as arguments. The group is the name of the langauge file without its extension. You may define options for your desired CSV format.

You can pass comma separated locales with the --mirror argument to get matching phrases for that locale in the same output.

### Import


```
php artisan lang-import:csv en navigation /some/file
php artisan lang-import:csv en all /some/file
php artisan lang-import:csv --delimiter=";" --enclosure='"' --escape='\\' en navigation /some/file
```

You have to pass  the __locale__, the __group__ and the __path to the CSV file__ as arguments. The group is the name of the langauge file without its extension. You may define options to match the CSV format of your input file.

If you pass __all__ as group it will loop the given file and find all available groups.
