<?php namespace Remoblaser\Resourceful\Commands;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Symfony\Component\Console\Input\InputArgument;
use Illuminate\Console\AppNamespaceDetectorTrait;

class ExtendRoutesWithResourceCommand extends Command{
    use AppNamespaceDetectorTrait;

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = "route:extend";

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = "Extend your routes with a resource";


    protected $files;

    function __construct(Filesystem $files)
    {
        parent::__construct();
        $this->files = $files;
    }

    public function fire()
    {
        $name = $this->argument('name');

        $routes = $this->files->get($this->getPath());

        $stub = $this->files->get(__DIR__.'/../stubs/route.stub');

        $filledStub = str_replace('{{resource}}', strtolower($name),  $stub);
        $filledStub = str_replace('{{controller}}', $this->getControllerName($name), $filledStub);

        $routes .= $filledStub;

        $this->files->put($this->getPath(), $routes);

        $this->info('Routes successfully extended.');

    }

    protected function getControllerName($resource)
    {
        return ucfirst($resource) . 'Controller';
    }

    protected function getPath()
    {
        return './app/Http/routes.php';
    }


    protected function getArguments()
    {
        return [
            ['name', InputArgument::REQUIRED, 'The name of the Resource']
        ];
    }
} 