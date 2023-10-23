<?php

namespace Elephant\Response\Contacts;

interface Response
{
    public function message(): string;

    public function code(): int;

    public function setResponse(\Symfony\Component\HttpFoundation\Response $response): Responsable;
}