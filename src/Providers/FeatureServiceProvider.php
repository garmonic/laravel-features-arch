<?php

namespace Garmonic\FeaturesArch\Providers;

use Garmonic\FeaturesArch\Commands\FeatureMakeCommand;
use Illuminate\Support\Facades\App;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;

class FeatureServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $featuresList = config('features.list', []);
        foreach ($featuresList as $alias => $feature) {
            App::singleton($alias, function () use ($feature) {
                return new $feature;
            });
            View::addNamespace($alias, base_path('features/'.$alias.'/resources/views'));
        }
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $features = array_keys(config('features.list', []));
        App::singleton('features', function ($features) {
            return $features;
        });
        foreach ($features as $feature) {
            App::make($feature);
        }
        $this->publishes([
            __DIR__.'/../config/features.php' => config_path('features.php'),
        ]);
        if ($this->app->runningInConsole()) {
            $this->commands([
                FeatureMakeCommand::class
            ]);
        }
    }
}
