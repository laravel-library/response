<?php

namespace Elephant\Response;

use Elephant\Response\Contacts\Factory;
use Elephant\Response\Contacts\Responsable;
use Elephant\Response\Middleware\JsonConvertProcess;
use Elephant\Response\Responses\ResponseFactory;
use Illuminate\Contracts\Container\Container;
use Illuminate\Support\ServiceProvider;

final class ElephantResponseService extends ServiceProvider
{
    public function register(): void
    {

        $this->app->singleton(
            Factory::class,
            fn(Container $container) => $container->make(ResponseFactory::class)
        );

        $this->app->singleton(Responsable::class, fn(Container $container) => $container->make(Responder::class));
    }

    public function boot(): void
    {
        $this->registerMiddleware('elephant.response', JsonConvertProcess::class);
    }

    protected function registerMiddleware(string $name, string $middleware): mixed
    {
        $route = $this->app['router'];

        if (method_exists($route, 'aliasMiddleware')) {
            return $route->aliasMiddleware($name, $middleware);
        }

        return $route->middleware($name, $middleware);
    }
}