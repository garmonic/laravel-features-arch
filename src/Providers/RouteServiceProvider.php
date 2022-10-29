<?php

namespace Garmonic\FeaturesArch\Providers;

use Garmonic\FeaturesArch\Providers\Contracts\SettingFeatureContract;
use Garmonic\FeaturesArch\Providers\Traits\SetsFeature;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;

class RouteServiceProvider extends ServiceProvider implements SettingFeatureContract
{
    use SetsFeature;

    /**
     * Returns the base path for specified path
     *
     * @param string $path
     * @return string
     */
    protected function getBasePath(string $path = ''): string
    {
        $featureName = $this->feature->getName();
        $mainPath = 'features/'.$featureName;
        if ($path !== '') {
            $mainPath.='/'.$path;
        }
        return base_path($mainPath);
    }
}
