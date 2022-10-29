<?php

namespace Garmonic\FeaturesArch\Providers\Contracts;

use Garmonic\FeaturesArch\Feature;

interface SettingFeatureContract
{
    /**
     * Binds feature instance
     *
     * @param Feature $feature
     * @return void
     */
    public function setFeature(Feature $feature): void;
}
