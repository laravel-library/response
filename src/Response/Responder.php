<?php

declare(strict_types=1);

namespace Elephant\Response\Response;

use Elephant\Response\Converter\AnonymousValue;
use JetBrains\PhpStorm\ArrayShape;
use Override;

trait  Responder
{

  readonly mixed $data;

  readonly int $code;

  public function __construct(mixed $data, int $code)
  {
    $this->data = $data;
    $this->code = $code;
  }

  #[ArrayShape(['msg' => 'string', 'code' => 'integer', 'data' => 'mixed'])]
  #[Override]
  public function toResponse(): array
  {
    $response = ['msg' => $this->message(), 'code' => $this->code(), 'data' => null];

    if (is_string($this->data)) {
      $response['msg'] = $this->data;
    } elseif ($this->data instanceof AnonymousValue) {
      $response['data'] = $this->data->getValue();
    } elseif (is_array($this->data)) {
      $response['data'] = $this->data;
    }

    if ($this->code > 0) {
      $response['code'] = $this->code;
    }

    return $response;
  }
}
