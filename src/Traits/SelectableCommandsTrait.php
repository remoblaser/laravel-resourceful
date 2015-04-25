<?php namespace Remoblaser\Resourceful\Traits;

use Symfony\Component\Console\Input\InputOption;

trait SelectableCommandsTrait {
    protected function parseCommands($commands)
    {
        if(isset($commands))
            return explode(',', $commands);
        return ['index', 'show', 'create', 'edit'];
    }


} 