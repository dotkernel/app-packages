<?php

use Frontend\Console\Command\UpdatePackagesCommand;

return [
    'dot_console' => [
        'name' => 'DotKernel Console',

        'commands' => [
            [
                'name' => 'updatepackages',
                'description' => 'Update DotKernel Packages',
                'handler' => UpdatePackagesCommand::class
            ],
        ]
    ]
];
