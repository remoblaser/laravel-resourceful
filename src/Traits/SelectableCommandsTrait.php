<?php namespace Remoblaser\Resourceful\Traits;

use Symfony\Component\Console\Input\InputOption;

trait SelectableCommandsTrait {
    protected function parseCommands()
    {

        $commands = $this->option('commands');
        if(isset($commands))
            return explode(',', $commands);
        return ['index', 'show', 'create', 'store', 'edit', 'update', 'destroy'];
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