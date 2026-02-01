<?php

namespace Omarsaiouf\Integrations\Mapping;

use Omarsaiouf\Integrations\Contracts\Mapping\ResponseMapper;

class ResponseMapperFactory
{
    public static function make(string $mapper): ResponseMapper
    {
        return match ($mapper) {

            default => new DefaultResponseMapper()
        };
    }
}