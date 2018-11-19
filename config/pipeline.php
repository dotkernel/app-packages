<?php


use Dot\Navigation\NavigationMiddleware;
use Dot\Session\SessionMiddleware;
use Zend\Expressive\Helper\ServerUrlMiddleware;
use Zend\Expressive\Helper\UrlHelperMiddleware;
use Zend\Expressive\Middleware\ImplicitHeadMiddleware;
use Zend\Expressive\Middleware\ImplicitOptionsMiddleware;
use Zend\Expressive\Middleware\NotFoundHandler;
use Zend\Stratigility\Middleware\ErrorHandler;
use Psr\Container\ContainerInterface;
use Zend\Expressive\Application;
use Zend\Expressive\MiddlewareFactory;
use Zend\Expressive\Router\Middleware\RouteMiddleware;
use Zend\Expressive\Router\Middleware\DispatchMiddleware;

return function (Application $app, MiddlewareFactory $factory, ContainerInterface $container) : void {
    /**
     * Setup middleware pipeline:
     */

// The error handler should be the first (most outer) middleware to catch
// all Exceptions.
    /** @var \Zend\Expressive\Application $app */
    $app->pipe(ErrorHandler::class);
    $app->pipe(ServerUrlMiddleware::class);

// starts the session and tracks session activity
    $app->pipe(SessionMiddleware::class);

// Pipe more middleware here that you want to execute on every request:
// - bootstrapping
// - pre-conditions
// - modifications to outgoing responses

// Register the routing middleware in the middleware pipeline
    $app->pipe(RouteMiddleware::class);

// zend expressive middleware
    $app->pipe(ImplicitHeadMiddleware::class);
    $app->pipe(ImplicitOptionsMiddleware::class);
    $app->pipe(UrlHelperMiddleware::class);

// Add more middleware here that needs to introspect the routing results; this
// ...

// navigation middleware makes sure the navigation service is injected the RouteResult
    $app->pipe(NavigationMiddleware::class);

// Register the dispatch middleware in the middleware pipeline
    $app->pipe(DispatchMiddleware::class);

// At this point, if no Response is return by any middleware, the
// NotFoundHandler kicks in; alternately, you can provide other fallback
// middleware to execute.
    $app->pipe(NotFoundHandler::class);
};