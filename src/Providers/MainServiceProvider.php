<?php

namespace Garmonic\FeaturesArch\Providers;

use Garmonic\FeaturesArch\Feature;
use Illuminate\Support\ServiceProvider;

class MainServiceProvider extends ServiceProvider
{
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
}
