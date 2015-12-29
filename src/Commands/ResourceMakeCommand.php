<?php namespace Remoblaser\Resourceful\Commands;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Composer;
use Remoblaser\Resourceful\Traits\GeneratorTrait;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

class ResourceMakeCommand extends Command {
    use GeneratorTrait;

    protected $name = "make:resource";

    protected $description = "Create a new resource including model, migration, seed, controller, views";

    protected $generators = ['migration', 'seed', 'model', 'controller', 'views'];

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
        $this->makeResource();

        if($this->option('bind')) {
            $this->bindModelToRoute();
        }

    }

    protected function makeResource()
    {
        $includes = $this->includes();

        foreach($includes as $include)
        {
            $callableMethod = "generate" . ucfirst($include);
            call_user_func([$this, $callableMethod]);
        }

        $this->extendRoutes();
    }

    protected function extendRoutes()
    {
        $this->call('route:extend', [
            'name' => $this->name()
        ]);
    }


    protected function bindModelToRoute()
    {
        $name = $this->argument('name');

        $this->call('route:bind', [
            'name' => $name
        ]);
    }

    protected function excludes()
    {
        $excludes = $this->option('exclude');

        return explode(',', $excludes);
    }

    protected function includes()
    {
        $includes = [];
        foreach($this->generators as $generator)
        {
            if(!in_array($generator, $this->excludes()))
                $includes[] = $generator;
        }

        return $includes;
    }

    protected function schema()
    {
        $schema = $this->option('schema');

        return $schema ? $schema : false;
    }

    protected function name()
    {
        return $this->argument('name');
    }

    protected function migrationName()
    {
        $name = $this->name();
        return "create_{$name}_table";
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
            ['exclude', 'e', InputOption::VALUE_OPTIONAL, 'Exclude Controller/Migration/Model/Views/Seed (comma seperated)', null],
            ['schema', 's', InputOption::VALUE_OPTIONAL, 'Optional schema to be attached to the migration', null],
            ['commands', 'c', InputOption::VALUE_OPTIONAL, 'Optional commands (CRUD) for views and controller actions', null]
        ];
    }
}