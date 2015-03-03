<?php namespace Remoblaser\Resourceful;

use Illuminate\Support\ServiceProvider;

class ResourcefulServiceProvider extends ServiceProvider {

	/**
	 * Indicates if loading of the provider is deferred.
	 *
	 * @var bool
	 */
	protected $defer = false;

	/**
	 * Register the service provider.
	 *
	 * @return void
	 */
	public function register()
	{


		$this->registerGeneratorCommand();
        $this->registerViewsCommand();
        $this->registerControllerCommand();
    }

    private function registerGeneratorCommand()
    {
        $this->app->singleton('command.remoblaser.resource', function($app) {
            return $app['Remoblaser\Resourceful\Commands\ResourceMakeCommand'];
        });

        $this->commands('command.remoblaser.resource');
    }

    private function registerViewsCommand()
    {
        $this->app->singleton('command.remoblaser.views', function($app) {
            return $app['Remoblaser\Resourceful\Commands\ViewsMakeCommand'];
        });

        $this->commands('command.remoblaser.views');
    }

    private function registerControllerCommand()
    {
        $this->app->singleton('command.remoblaser.controller', function($app) {
            return $app['Remoblaser\Resourceful\Commands\ControllerMakeCommand'];
        });

        $this->commands('command.remoblaser.controller');
    }

}
