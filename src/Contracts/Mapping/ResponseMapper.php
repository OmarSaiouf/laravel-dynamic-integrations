<?php

namespace Omarsaiouf\Integrations\Contracts\Mapping;

use Omarsaiouf\Integrations\DTOs\HttpResponse;
use Omarsaiouf\Integrations\DTOs\MappedResult;

interface ResponseMapper
{
    public function map(array $endpoint, HttpResponse $response): MappedResult;
}