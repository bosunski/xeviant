<?php


namespace App\Providers;


use Composer\Command\BaseCommand;
use Composer\Plugin\Capability\CommandProvider as CommandProviderCapability;
use App\Commands\ComposerUICommand;

class CommandProvider implements CommandProviderCapability
{

    /**
     * Retrieves an array of commands
     *
     * @return BaseCommand[]
     */
    public function getCommands()
    {
        return [
            new ComposerUICommand,
        ];
    }
}
