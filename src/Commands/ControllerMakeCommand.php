<?php namespace Remoblaser\Resourceful\Commands;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Foundation\Composer;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Illuminate\Console\AppNamespaceDetectorTrait;


class ControllerMakeCommand extends Command {
    use AppNamespaceDetectorTrait;
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = "make:controller:resourceful";

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = "Create a resourceful Controller";


    protected $files;

    protected $composer;

    function __construct(Filesystem $files, Composer $composer)
    {
        parent::__construct();
        $this->files = $files;
        $this->composer = $composer;
    }

    public function fire()
    {
        $name = $this->argument('name');
        if ($this->files->exists($path = $this->getPath($name)))
        {
            return $this->error($this->type.' already exists!');
        }
        $this->makeDirectory($path);

        $this->createController($path, $name);

        $this->composer->dumpAutoloads();

        $this->info('Controller created successfully.');

    }

    protected function createController($path, $controllerName)
    {
        $stub = $this->files->get(__DIR__.'/../stubs/controller.stub');

        $model = $this->argument('model');

        $filledStub = str_replace('{{resource}}', $model, $stub);
        $filledStub = str_replace('{{model}}', ucfirst($model), $filledStub);
        $filledStub = str_replace('{{className}}', $controllerName, $filledStub);
        $filledStub = str_replace('{{rootNamespace}}', $this->getAppNamespace() , $filledStub);
        $filledStub = str_replace('{{namespace}}', $this->getDefaultNamespace() , $filledStub);

        $this->files->put($path, $filledStub);
    }

    protected function getDefaultNamespace()
    {
        return $this->getAppNamespace().'Http\Controllers';
    }


    protected function getPath($name)
    {
        return './app/Http/Controllers/' . $name . '.php';
    }

    protected function makeDirectory($path)
    {
        if ( ! $this->files->isDirectory(dirname($path)))
        {
            $this->files->makeDirectory(dirname($path), 0777, true, true);
        }
    }

    protected function getArguments()
    {
        return [
            ['name', InputArgument::REQUIRED, 'The name of the Controller'],
            ['model', InputArgument::REQUIRED, 'The name of the Model'],
        ];
    }
    /**
     * Get the console command options.
     *
     * @return array
     */
    protected function getOptions()
    {
        return [
            ['actions', 'a', InputOption::VALUE_OPTIONAL, 'Optional actions', null]
        ];
    }


}