<?php

declare(strict_types=1);

namespace Elephant\Response;

use Elephant\Response\Contacts\Factory;
use Elephant\Response\Converter\Contacts\HttpMessageConverterBuilder;
use Elephant\Response\Converter\HttpMessageConverterFactory;
use Elephant\Response\Middleware\FormatResponseBodyAdvice;
use Illuminate\Contracts\Container\Container;
use Illuminate\Routing\Router;
use Illuminate\Support\ServiceProvider;

final class ElephantResponseServiceProvider extends ServiceProvider
{
    public function register(): void
    {

        $this->app->singleton(
            Factory::class,
            fn(Container $app) => new ResponderFactory($app->make('request'),$app)
        );

        $this->app->singleton(
            HttpMessageConverterBuilder::class,
            fn(Container $app) => new HttpMessageConverterFactory($app)
        );
    }

    public function boot(Router $router): void
    {
        $this->registerMiddleware('http.message.converter', $router);
    }

    protected function registerMiddleware(string $name, Router $router): void
    {
        $router->aliasMiddleware($name, FormatResponseBodyAdvice::class)
            ->pushMiddlewareToGroup('api', $name);
    }
}