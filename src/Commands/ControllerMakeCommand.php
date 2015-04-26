<?php namespace Remoblaser\Resourceful\Commands;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Foundation\Composer;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Illuminate\Console\AppNamespaceDetectorTrait;
use Remoblaser\Resourceful\Traits\SelectableCommandsTrait;


class ControllerMakeCommand extends Command {
    use AppNamespaceDetectorTrait;
    use SelectableCommandsTrait;
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = "make:resource:controller";

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
        $name = $this->parseControllerName();
        if ($this->files->exists($path = $this->getPath($name)))
        {
            return $this->error($name.' already exists!');
        }
        $this->makeDirectory($path);

        $this->createController($path, $name);

        $this->composer->dumpAutoloads();

        $this->info('Controller created successfully.');

    }

    protected function parseControllerName()
    {
        $name = strtolower($this->argument('name'));

        if(ends_with($name, 'controller'))
            return ucwords($name);

        return ucwords($name) . "Controller";
    }

    protected function createController($path, $controllerName)
    {
        $filledCommands = "";

        $commands = $this->parseCommands($this->option('commands'));

        $model = $this->parseModelName();

        foreach($commands as $command) {
            $filledCommands .= $this->createCommand($command, $model);
        }


        $stub = $this->files->get(__DIR__.'/../stubs/controller/controller.stub');

        $filledStub = str_replace('{{className}}', $controllerName, $stub);
        $filledStub = str_replace('{{rootNamespace}}', $this->getAppNamespace() , $filledStub);
        $filledStub = str_replace('{{namespace}}', $this->getDefaultNamespace() , $filledStub);
        $filledStub = str_replace('{{model}}', ucfirst($model), $filledStub);
        $filledStub = str_replace('{{commands}}', $filledCommands, $filledStub);


        $this->files->put($path, $filledStub);
    }

    protected function createCommand($commandName, $model)
    {
        $stubPath = $this->getStubRoot();
        $stub = $this->files->get($stubPath . $commandName . '.stub');

        $filledStub = str_replace('{{resource}}', $model, $stub);
        $filledStub = str_replace('{{resource_plural}}', str_plural($model), $filledStub);
        $filledStub = str_replace('{{model}}', ucfirst($model), $filledStub);

        return $filledStub;
    }

    private function getStubRoot()
    {
        if($this->option('bind'))
        {
            return __DIR__.'/../stubs/controller/binded-commands/';
        }
        return __DIR__.'/../stubs/controller/commands/';
    }

    protected function parseModelName()
    {
        $model = strtolower($this->argument('name'));

        if(!ends_with($model, 'controller'))
            return $model;

        return str_replace('controller', '', $model);
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

    /**
     * Get the console command options.
     *
     * @return array
     */
    protected function getOptions()
    {
        return [
            ['bind', 'b', InputOption::VALUE_NONE, 'Bind model to route', null],
            ['commands', 'c', InputOption::VALUE_OPTIONAL, 'Optional commands (CRUD)', null],
        ];
    }

    protected function getArguments()
    {
        return [
            ['name', InputArgument::REQUIRED, 'The name of the Controller']
        ];
    }



}