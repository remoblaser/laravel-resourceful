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
        $this->generatePersistencyFiles();
        $this->generateController();
        $this->generateViews();
        $this->expandRoutes();

    }

    protected function generatePersistencyFiles()
    {
        $name = $this->argument('name');

        $schema = $this->argument('schema');
        $this->call('make:migration:schema', [
            'name' => $name,
            'schema' => $schema
        ]);

        $this->call('make:seed', [
            'name' => $name
        ]);


    }

    protected function generateController()
    {
        $name = $this->argument('name');

        if(ends_with($name, 'Controller')) {
            $model = $name;
        }
        else {
            $model = str_replace('Controller', '', $name);
        }
        $this->call('make:controller:resourceful', [
            'name' => $name,
            'model' => $model
        ]);
    }

    protected function generateViews()
    {
        $name = $this->argument('name');
        $actions = $this->option('actions');

        $this->call('make:views', [
            'name' => $name,
            'actions' => $actions
        ]);
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
            ['schema', 's', InputOption::VALUE_OPTIONAL, 'Optional schema to be attached to the migration', null],
            ['actions', 'a', InputOption::VALUE_OPTIONAL, 'Optional actions, e.g. views to be generated', null]
        ];
    }
}