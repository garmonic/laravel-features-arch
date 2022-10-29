<?php

namespace Garmonic\FeaturesArch\Providers;

use Garmonic\FeaturesArch\Providers\Contracts\SettingFeatureContract;
use Garmonic\FeaturesArch\Providers\Traits\SetsFeature;
use Illuminate\Support\ServiceProvider;

class MainServiceProvider extends ServiceProvider implements SettingFeatureContract
{
    use SetsFeature;
}
