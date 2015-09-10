<?php namespace Remoblaser\Resourceful\Traits;

trait GeneratorTrait
{

    protected function generateMigration()
    {
        if($this->schema()) {
            $this->call('make:migration:schema', [
                'name' => $this->name(),
                '--schema' => $this->schema()
            ]);
        }
        else {
            $this->call('make:migration', [
                'name' => $this->migrationName(),
            ]);
        }
    }

    protected function generateSeed()
    {
        $this->call('make:seed', [
            'name' => $this->name()
        ]);
    }

    protected function generateModel()
    {
        $this->call('make:model', [
            'name' => ucfirst($this->name()),
        ]);
    }


    protected function generateController()
    {
        $name = $this->argument('name');
        $commands = $this->option('commands');

        $this->call('make:request', [
            'name' => $this->requestName($name)
        ]);

        $bind = $this->option('bind');

        $this->call('make:resource:controller', [
            'name' => $name,
            '--commands' => $commands,
            '--bind' => $bind,
        ]);
    }

    protected function generateViews()
    {
        $name = $this->argument('name');
        $commands = $this->option('commands');

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




    protected function requestName($name)
    {
        return ucfirst($name) . "Request";
    }
}