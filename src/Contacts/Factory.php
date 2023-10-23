<?php

namespace Elephant\Response\Contacts;

interface Factory extends Responsable
{
    public function toResponse(): Response;
}