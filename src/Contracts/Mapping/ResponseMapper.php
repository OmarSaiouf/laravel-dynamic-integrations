<?php

namespace Omarsaiouf\Integrations\Contracts\Mapping;

use Omarsaiouf\Integrations\DTOs\HttpResponse;
use Omarsaiouf\Integrations\DTOs\MappedResult;
use Omarsaiouf\Integrations\Enums\MappingMode;

interface ResponseMapper
{
    public function map(array $endpoint, MappingMode $mode, HttpResponse $response): MappedResult;
}