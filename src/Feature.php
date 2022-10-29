<?php

namespace Garmonic\FeaturesArch;

use Illuminate\Config\Repository;
use Illuminate\Container\Container;
use Illuminate\Support\Facades\App;
use Illuminate\View\View;
use SplFileInfo;
use Symfony\Component\Finder\Finder;

abstract class Feature
{
    /**
     * Feature name
     *
     * @var string
     */
    protected string $name = '';

    /**
     * Feature route prefix
     *
     * @var string
     */
    protected string $routeBase = '/';

    /**
     * Services to load
     *
     * @var array
     */
    protected array $services = ['MainServiceProvider', 'RouteServiceProvider'];

    /**
     * Feature configuration storage
     *
     * @var Repository
     */
    protected Repository $configuration;

    /**
     * Feature container
     *
     * @var Container
     */
    protected Container $container;

    public function __construct()
    {
        $this->loadConfig();
        $this->container = new Container();
        $this->loadServices();
    }

    /**
     * Returns name of the feature
     *
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * Returns feature route prefix
     *
     * @return string
     */
    public function getRouteBase(): string
    {
        return $this->routeBase;
    }

    /**
     * Gets or sets configuration setting
     *
     * @param string|null $key
     * @param mixed $value
     * @return void
     */
    public function config(?string $key = null, $value = null)
    {
        if (is_null($key)) {
            return $this->configuration;
        }
        if (is_null($value)) {
            return $this->configuration->get($key);
        }
        $this->configuration->set($key, $value);
    }

    /**
     * Gets feature view content
     *
     * @param string $name
     * @param array $parameters
     * @return View
     */
    public function view(string $name, array $parameters = []): View
    {
        return view($this->name.'::'.$name, $parameters);
    }

    /**
     * Generate the URL to a named feature route.
     *
     * @param string $name
     * @param array $parameters
     * @param boolean $absolute
     * @return string
     */
    public function route(string $name, array $parameters = [], $absolute = true): string
    {
        $fullName = $this->name.'.'.$name;

        return route($fullName, $parameters, $absolute);
    }

    /**
     * Register a shared binding in the container.
     *
     * @param  string  $abstract
     * @param  \Closure|string|null  $concrete
     * @return void
     */
    public function singleton($abstract, $concrete = null)
    {
        $this->container->bind($abstract, $concrete, true);
    }

    /**
     * Binds to feature container
     *
     * @param string $abstract
     * @param \Closure|string|null $concrete
     * @return void
     */
    public function bind(string $abstract, $concrete)
    {
        $this->container->bind($abstract, $concrete);
    }

    /**
     * Gets the value from feature container
     *
     * @param string $abstract
     * @param array $parameters
     * @return void
     */
    public function make(string $abstract, array $parameters = [])
    {
        return $this->container->make($abstract, $parameters);
    }

    /**
     * Loads feature configuration
     *
     * @return void
     */
    protected function loadConfig()
    {
        $this->configuration = new Repository();
        $configPath = realpath(app()->basePath().'/features/'.$this->name.'/config');

        foreach (Finder::create()->files()->name('*.php')->in($configPath) as $file) {
            $directory = $this->getNestedDirectory($file, $configPath);

            $files[$directory.basename($file->getRealPath(), '.php')] = $file->getRealPath();
        }

        ksort($files, SORT_NATURAL);
        foreach ($files as $key => $path) {
            $this->configuration->set($key, require $path);
        }
    }

    /**
     * Loads feature service providers
     *
     * @return void
     */
    protected function loadServices()
    {
        $providersRoot = 'Features\\'.$this->name.'\\Core\\Providers\\';
        foreach ($this->services as $service) {
            $className = $providersRoot.$service;
            $serviceInstance = new $className(app());
            $serviceInstance->setFeature($this);
            App::register($serviceInstance);
        }
    }

    /**
     * Get the configuration file nesting path.
     *
     * @param  \SplFileInfo  $file
     * @param  string  $configPath
     * @return string
     */
    protected function getNestedDirectory(SplFileInfo $file, $configPath)
    {
        $directory = $file->getPath();

        if ($nested = trim(str_replace($configPath, '', $directory), DIRECTORY_SEPARATOR)) {
            $nested = str_replace(DIRECTORY_SEPARATOR, '.', $nested).'.';
        }

        return $nested;
    }
}
