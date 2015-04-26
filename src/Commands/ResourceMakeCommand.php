<?php namespace Remoblaser\Resourceful\Commands;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Foundation\Composer;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

class ResourceMakeCommand extends Command {

    protected $name = "make:resource";

    protected $description = "Create a new resource including model, migration, seed, controller, views";

    protected $files;

    private $composer;

    function __construct(Filesystem $files, Composer $composer)
    {
        parent::__construct();

        $this->files = $files;
        $this->composer = $composer;
    }

    public function fire()
    {
        $commands = $this->option('commands');
        $schema = $this->option('schema');

        $this->generatePersistencyFiles($schema);
        $this->generateController($commands);
        $this->generateViews($commands);
        $this->extendRoutes();
        if($this->option('bind')) {
            $this->bindModelToRoute();
        }

    }

    protected function extendRoutes()
    {
        $name = $this->argument('name');

        $this->call('route:extend', [
            'name' => $name
        ]);
    }


    protected function generatePersistencyFiles($schema)
    {
        $name = $this->argument('name');
        if($schema) {
            $this->call('make:migration:schema', [
                'name' => $name,
                '--schema' => $schema
            ]);
        }
        else {
            $this->call('make:model', [
                'name' => ucfirst($name),
            ]);
        }

        $this->call('make:seed', [
            'name' => $name
        ]);



    }

    protected function generateController($commands)
    {
        $name = $this->argument('name');

        $this->call('make:request', [
            'name' => $this->parseRequestName($name)
        ]);

        $bind = $this->option('bind');

        $this->call('make:resource:controller', [
            'name' => $name,
            '--commands' => $commands,
            '--bind' => $bind,
        ]);
    }

    protected function bindModelToRoute()
    {
        $name = $this->argument('name');

        $this->call('route:bind', [
            'name' => $name
        ]);
    }

    protected function generateViews($commands)
    {
        $name = $this->argument('name');

        if($commands) {
            $this->call('make:resource:views', [
                'name' => $name,
                '--commands' => $commands
            ]);
        }
        else {
            $this->call('make:resource:views', [
                'name' => $name
            ]);
        }
    }

    protected function parseRequestName($name)
    {
        return ucfirst($name) . "Request";
    }

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments()
    {
        return [
            ['name', InputArgument::REQUIRED, 'The name of the resource'],
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
            ['bind', 'b', InputOption::VALUE_NONE, 'Bind model to route', null],
            ['schema', 's', InputOption::VALUE_OPTIONAL, 'Optional schema to be attached to the migration', null],
            ['commands', 'c', InputOption::VALUE_OPTIONAL, 'Optional commands (CRUD) for views and controller actions', null]
        ];
    }
}