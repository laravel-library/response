<?php

namespace Elephant\Response;

use Elephant\Response\Contacts\Factory;
use Elephant\Response\Middleware\FormatResponseBodyAdvice;
use Elephant\Response\Response\ResponderFactory;
use Illuminate\Contracts\Container\Container;
use Illuminate\Routing\Router;
use Illuminate\Support\ServiceProvider;

final class ElephantResponseServiceProvider extends ServiceProvider
{
    public function register(): void
    {

        $this->app->singleton(
            Factory::class,
            fn(Container $container) => $container->make(ResponderFactory::class)
        );
    }

    public function boot(Router $router): void
    {
        $this->registerMiddleware('elephant.response', $router);
    }

    protected function registerMiddleware(string $name, Router $router): void
    {
        $router->aliasMiddleware($name, FormatResponseBodyAdvice::class)
            ->pushMiddlewareToGroup('api', $name);
    }
}