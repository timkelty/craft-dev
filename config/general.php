<?php
use craft\config\GeneralConfig;
use mikehaertl\shellcommand\Command as ShellCommand;

return GeneralConfig::create()
    ->restoreCommand([
        'archiveFormat' => true, // pgsql-only

        // example: add an arg
        'callback' => fn(ShellCommand $command) => $command->addArg('--verbose'),
    ])
    ->backupCommand([
        'ignoreTables' => ['foo', 'bar'],
        'archiveFormat' => true, // pgsql-only

        // example: remove an arg
        'callback' => function (ShellCommand $command) {
            $command->setArgs(str_replace('--dump-date', '', $command->getArgs()));

            return $command;
        },
    ]);
