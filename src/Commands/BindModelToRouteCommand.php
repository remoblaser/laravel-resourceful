<?php namespace Remoblaser\Resourceful\Commands;

use Illuminate\Console\DetectsApplicationNamespace;
use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Symfony\Component\Console\Input\InputArgument;

class BindModelToRouteCommand extends Command {
    use DetectsApplicationNamespace;

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = "route:bind";

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = "Bind a route to a Model";


    protected $files;

    function __construct(Filesystem $files)
    {
        parent::__construct();
        $this->files = $files;
    }

    public function fire()
    {
        $name = $this->argument('name');

        $this->bindModelToRoute($name);

        $this->info('Route successfully binded to Model.');
    }

    protected function bindModelToRoute($name)
    {
        $routeServiceProvider = "";

        $routeServiceProviderPath =  base_path() . "/app/Providers/RouteServiceProvider.php";
        foreach(file($routeServiceProviderPath) as $line) {
            if(trim(preg_replace('/\t+/', '', $line)) == 'parent::boot($router);') {
                $line .= $this->getBindCommand($name);
            }

            $routeServiceProvider .= $line;
        }

        $this->files->put($routeServiceProviderPath, $routeServiceProvider);
    }

    private function getBindCommand($name)
    {
        $name = ucfirst($name);
        return "\t\t" . '$router->model(' . "'" . $name . "'" . ", '" . $this->getAppNamespace() . '\\' . $name . "');". PHP_EOL;
    }

    protected function getArguments()
    {
        return [
            ['name', InputArgument::REQUIRED, 'The name of the Resource to bind']
        ];
    }
} 