<?php

namespace Garmonic\FeaturesArch\Providers\Traits;

use Garmonic\FeaturesArch\Feature;

trait SetsFeature
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
    public function setFeature(Feature $feature): void
    {
        $this->feature = $feature;
    }
}
