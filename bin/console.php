<?php

use ZF\Console\Application;

chdir(dirname(__DIR__));
require 'vendor/autoload.php';

/**
 * Self-called anonymous function that creates its own scope and keep the global namespace clean.
 */
call_user_func(function () {
    /** @var \Interop\Container\ContainerInterface $container */
    $container = require 'config/container.php';

    $dispatcher = new \ZF\Console\Dispatcher($container);

    $app = new Application(
        $container->get('config')['dot_console']['name'],
        '1.0',
        $container->get('config')['dot_console']['commands'],
        \Zend\Console\Console::getInstance(),
        $dispatcher
    );

    $app->setDebug($container->get('config')['debug']);
    $exit = $app->run();
    exit($exit);
});
