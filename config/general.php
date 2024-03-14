<?php
use craft\config\GeneralConfig;
use mikehaertl\shellcommand\Command as ShellCommand;

return GeneralConfig::create()
    ->restoreCommand([
        'archiveFormat' => true,
        'callback' => fn(ShellCommand $command) => $command->addArg('--verbose'),
    ])
    ->backupCommand([
        'ignoreTables' => ['foo', 'bar'],
        'archiveFormat' => true,
        'callback' => function (ShellCommand $command) {
            return $command->addArg('--verbose');
        },
    ]);
