<?php

declare(strict_types=1);

namespace Elephant\Response\Converter;

final readonly class AnonymousValue
{

  private mixed $value;

  public function __construct(mixed $value)
  {
    $this->value = $value;
  }

  public function getValue(): mixed
  {
    return $this->value;
  }
}
