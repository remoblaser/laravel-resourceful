<?php namespace Remoblaser\Resourceful\Commands;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Remoblaser\Resourceful\Traits\SelectableCommandsTrait;

class ViewsMakeCommand extends Command {
    use SelectableCommandsTrait;

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
            return $this->error($name .' views already exist!');
        }
        $this->makeDirectory($path);

        $commands = $this->option('commands');
        $views = $this->parseCommands($commands);

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
            ['commands', 'c', InputOption::VALUE_OPTIONAL, 'Optional commands (CRUD)', null]
        ];
    }



}