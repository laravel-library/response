<?php

namespace Elephant\Response\Contacts;

interface Reportable
{
    public function report(mixed $content): array;
}