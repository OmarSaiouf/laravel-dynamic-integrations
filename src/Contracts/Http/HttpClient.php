<?php

namespace Omarsaiouf\Integrations\Contracts\Http;

use Omarsaiouf\Integrations\DTOs\BuiltRequest;
use Omarsaiouf\Integrations\DTOs\HttpResponse;

interface HttpClient
{
    public function send(BuiltRequest $request): HttpResponse;
}
