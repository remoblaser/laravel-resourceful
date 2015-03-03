<?php namespace Remoblaser\Resourceful\Commands;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

class ViewsMakeCommand extends Command {
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = "make:views";

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = "Generate default bootstrap views";

    protected $files;

    function __construct(Filesystem $files)
    {
        parent::__construct();
        $this->files = $files;
    }

    public function fire()
    {
        $name = strtolower($this->argument('name'));
        if ($this->files->exists($path = $this->getPath($name)))
        {
            return $this->error($this->type.' already exists!');
        }
        $this->makeDirectory($path);

        $views = $this->parseActions();

        foreach($views as $view) {
            $this->createView($view, $path, $name);
        }

        $partialsPath = $path . '/partials';
        $this->makeDirectory($partialsPath);
        $this->createView('form', $partialsPath, $name);

        $this->info('Views created successfully.');
    }

    protected function createView($viewName, $path, $resource)
    {
        $path .= '/' . $viewName . '.blade.php';

        $stub = $this->files->get(__DIR__.'/../stubs/views/'.$viewName.'.stub');
        $filledStub = str_replace('((resource))', $resource, $stub);
        $filledStub = str_replace('((resource_plural))', str_plural($resource), $filledStub);
        $this->makeDirectory($path);
        $this->files->put($path, $filledStub);
    }

    protected function parseActions()
    {

        $actions = $this->option('actions');
        if(isset($actions))
            return explode(',', $actions);
        return ['index', 'show', 'create', 'store', 'edit', 'update', 'destroy'];
    }

    protected function getPath($name)
    {
        return './resources/views/' . $name;
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
            ['actions', 'a', InputOption::VALUE_OPTIONAL, 'Optional actions, e.g. views to be generated', null]
        ];
    }


}