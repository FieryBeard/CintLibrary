# CintLibrary
Library for using Cint api.

####Installation:

Require this package with composer using the following command:

```bash
composer require opinodo/cint-library
```
In Laravel, instead of adding the service provider in the `config/app.php` file, you can add the following code to your `app/Providers/AppServiceProvider.php` file, within the `register()` method:

```php
public function register()
{
    $this->app->register(\Opinodo\CintLibrary\CintServiceProvider::class);
    // ...
}
```

You can also publish the config file to change configuration.

```bash
php artisan vendor:publish --provider="Opinodo\CintLibrary\CintServiceProvider" --tag=config
```