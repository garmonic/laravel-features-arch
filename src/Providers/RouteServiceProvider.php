<?php

namespace Garmonic\FeaturesArch\Providers;

use Garmonic\FeaturesArch\Feature;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * Feature instance
     *
     * @var Feature
     */
    protected Feature $feature;

    /**
     * Binds feature instance
     *
     * @param Feature $feature
     * @return void
     */
    public function setFeature(Feature $feature)
    {
        $this->feature = $feature;
    }

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
