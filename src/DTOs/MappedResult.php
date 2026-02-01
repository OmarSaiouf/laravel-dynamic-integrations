<?php

namespace Omarsaiouf\Integrations\DTOs;

class MappedResult
{
    public function __construct(
        public array $data,
        public array $extra = []
    ) {
    }

    public function toArray(): array
    {
        return [
            'data' => $this->data,
            'extra' => $this->extra,
        ];
    }
}
