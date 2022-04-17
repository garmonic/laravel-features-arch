<?php

namespace Garmonic\FeaturesArch\Commands;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Str;

class FeatureMakeCommand extends Command
{
    protected const BINDINGS = [
        'Feature.stub' => 'Feature.php',
        'config.stub' => 'config/main.php',
        'MainController.stub' => 'Core/Http/Controllers/MainController.php',
        'MainServiceProvider.stub' => 'Core/Providers/MainServiceProvider.php',
        'RouteServiceProvider.stub' => 'Core/Providers/RouteServiceProvider.php',
        'blade.stub' => 'resources/views/home.blade.php',
        'routes_api.stub' => 'routes/api.php',
        'routes_web.stub' => 'routes/web.php'
    ];

    /**
     * The filesystem instance.
     *
     * @var \Illuminate\Filesystem\Filesystem
     */
    protected $files;

    /**
     * Stub files dir
     *
     * @var string
     */
    protected string $stubsDir = __DIR__.'/../../stubs/';

    /**
     * Feature files path
     *
     * @var string
     */
    protected string $featurePath;

    /**
     * Values for replacement in stubs
     *
     * @var array
     */
    protected array $replace;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:feature {name} {--route=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Creates basic code for new feature';

    /**
     * Create a new controller creator command instance.
     *
     * @param  \Illuminate\Filesystem\Filesystem  $files
     * @return void
     */
    public function __construct(Filesystem $files)
    {
        parent::__construct();

        $this->files = $files;
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $name = $this->argument('name');
        if (empty($name)) {
            $this->error('Feature name cannot be empty');
            return 1;
        }
        if ($this->files->isDirectory(base_path('features/'.$name))) {
            $this->error("Feature $name already exists!");
            return 1;
        }
        $this->info('Creating feature '.$name);
        $routeBase = $this->option('route');
        if ($routeBase !== null) {
            if (empty($routeBase)) {
                $this->error('Route base cannot be empty');
                return 1;
            }
            $this->info('Route base is /'.$routeBase);
        } else {
            $routeBase = Str::lower($name);
            $this->info('Route base is /'.$routeBase);
        }
        $this->featurePath = app()->basePath('/features/'.$name.'/');
        $this->replace = ['DummyFeature'=>$name, 'DummyRouteBase'=>$routeBase];
        foreach (static::BINDINGS as $stub => $target) {
            $this->makeFile($stub, $target);
        }
        $this->info('Feature created successfully');
        $this->line('TIP: to enable new feature add Features\\'.$name.'\\Feature::class to config/features.php (section "list")');

        return 0;
    }

    /**
     * Replacing placeholders in stub
     *
     * @param string $stub
     * @param array $replace
     * @return string
     */
    protected function replacePlaceholders(string $stub): string
    {
        return str_replace(array_keys($this->replace), $this->replace, $stub);
    }

    /**
     * Creates a file using stub
     *
     * @param string $stubFileName
     * @param string $targetFileName
     * @param array $replace
     * @return void
     */
    protected function makeFile(string $stubFileName, string $targetFileName): void
    {
        $stubContent = $this->files->get($this->stubsDir.$stubFileName);
        $content = $this->replacePlaceholders($stubContent);
        $this->makeDirectory($this->featurePath.$targetFileName);
        $this->files->put($this->featurePath.$targetFileName, $content);
    }

    /**
     * Build the directory for file if necessary.
     *
     * @param  string  $path
     * @return string
     */
    protected function makeDirectory($path)
    {
        if (! $this->files->isDirectory(dirname($path))) {
            $this->files->makeDirectory(dirname($path), 0777, true, true);
        }

        return $path;
    }
}
