<?php

namespace Garmonic\FeaturesArch;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\View;

class BaseController extends Controller
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    /**
     * Feature instance
     *
     * @var Feature
     */
    protected Feature $feature;

    public function __construct()
    {
        $this->feature = App::make($this->featureName);
        View::share('feature', $this->feature);
    }
}
