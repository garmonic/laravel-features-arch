# Laravel Features Architecture

## Description

This package helps you to divide you Laravel project code by features, encapsulated in separate folders. Each feature has its own container, configuration, routes, views and providers. You can enable and disable your features in general config file.

## Installation

1. Use following composer command in your terminal:

`composer require garmonic/laravel-features-arch`

If file `config/features.php` has not been created, run such command:

`php artisan vendor:publish --provider='Garmonic\FeaturesArch\Providers\FeatureServiceProvider'`

2. Add to `composer.json` file (section `"psr-4"` in `"autoload"`) following string:

`"Features\\": "features/",`

## Usage

### Creating new feature

To create new feature, run console command:

`php artisan make:feature ` with name of your feature, for example:

`php artisan make:feature Warehouse`

If you wish to specify routes beginning for feature, use option `route` with its value, example for beginning `/wh/`:

`php artisan make:feature Warehouse --route=wh`

Route base should not start with slash, but can contain it inside. By default, route base will be made from feature name converted to lower case.All routes will be automatically prefixed by specified or generated beginning.

Finally, new folder `/features/{your-feature-name}` will appear in your project. It will be place of new feature.

### Enabling and disabling features

To **enable** feature, add its name and main class name (`Features\\{name}\\Feature::class`) to `config/features.php` (section `"list"`).

To **disable** it, comment its string in the same config file.

### Feature folder files structure

-   `config/main.php` — empty **config file** for feature.

You can read config from it using `Feature::config()`.
In controller:
`$this->feature->config('main.config_key', 'default');`

Also you can add your own config files to feature `config` folder and use them in the same way.

-   `Core/Http/Controllers/MainController` — empty **controller** with one demo method.

You can create your own controllers for feature, each of them should extend `Garmonic\FeaturesArch\BaseController` and contain correct `featureName` property.

-   `Core/Providers/MainServiceProvider.php` — empty **service provider**, you are welcome to add there some bindings (as you do in `AppServiceProvider`), that will be actual for this feature.

You can use global container (`App::singleton()` or `App::bind()`) or **feature container** (`$this->feature->bind()`).

-   `Core/Providers/RouteServiceProvider.php` — this service provider is responsible for loading feature routes. Be careful while changing its code.
-   `resources/views/home.blade.php` — sample **view**.

You can add your own views and use them in controllers like this:
`return $this->feature->view('view_name', ['data'=>'some_data'])`;
Also, each view has `$feature` variable to have access to feature instance.

-   `routes/api.php` — empty API routes file.
-   `routes/web.php` — web **routes** file with demo route.

Links to feature routes can be generated using `Feature::route()` method.

In controller:
`$this->feature->route('route_name', ['data'=>'some_data']);`

In view:
`{{ $feature->route('route_name', ['data'=>'some_data']) }}`

## About author

Alexey Vasilyev, https://github.com/garmonic
