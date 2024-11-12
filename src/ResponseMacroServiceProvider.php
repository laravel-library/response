<?php

namespace Elephant\Response;

use Illuminate\Http\Response;
use Illuminate\Support\ServiceProvider;
use Throwable;

final class ResponseMacroServiceProvider extends ServiceProvider
{
  public function boot(): void
  {
    Response::macro('hasException', function(): bool {
      return $this->exception instanceof Throwable;
    });

    Response::macro('isArrayResponse', fn(): bool => json_validate($this->getContent()));

    Response::macro('isNormalStringResponse', fn(): bool => is_string($this->getContent()) && !json_validate($this->getContent()));
  }
}
