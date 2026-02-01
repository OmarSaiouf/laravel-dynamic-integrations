<?php

namespace Omarsaiouf\Integrations\Mapping;

use Omarsaiouf\Integrations\Contracts\Mapping\ResponseMapper;
use Omarsaiouf\Integrations\DTOs\HttpResponse;
use Omarsaiouf\Integrations\DTOs\MappedResult;
use Omarsaiouf\Integrations\Enums\MappingMode;
use Omarsaiouf\Integrations\Exceptions\MappingException;

class DefaultResponseMapper implements ResponseMapper
{
    /**
     * $rules = output shape (dynamic)
     */
    public function map(array $rules, MappingMode $mode, HttpResponse $response): MappedResult
    {
        $payload = $response->json();

        $data = $this->resolveNode($rules, $payload);

        // مبدئيًا نخلي extra فاضي.
        // إذا بدك extra منفصل، خلي rules يحتوي "extra" و"data" (اختياري)
        return new MappedResult(
            data: is_array($data) ? $data : ['value' => $data],
            extra: []
        );
    }

    private function resolveNode(mixed $node, mixed $ctx): mixed
    {
        // 1) string => path
        if (is_string($node)) {
            return $this->getByPath($ctx, $node);
        }

        // 2) scalar constants (int/bool/null/float)
        if (!is_array($node)) {
            return $node;
        }

        // 3) Operator: @each
        if (array_key_exists('@each', $node)) {
            $path = $node['@each'];
            $list = $this->getByPath($ctx, $path);

            if (!is_array($list)) {
                throw new MappingException("@each path '{$path}' did not return an array");
            }

            $map = $node['map'] ?? null;
            if (!is_array($map)) {
                throw new MappingException("@each requires 'map' as array");
            }

            $out = [];
            foreach ($list as $item) {
                $out[] = $this->resolveNode($map, $item);
            }
            return $out;
        }

        // 4) Normal object/array: resolve recursively
        $out = [];
        foreach ($node as $key => $value) {
            $out[$key] = $this->resolveNode($value, $ctx);
        }

        return $out;
    }

    private function getByPath(mixed $data, string $path): mixed
    {
        if ($path === '' || $path === '.')
            return $data;

        $segments = explode('.', $path);

        foreach ($segments as $seg) {
            if (is_array($data) && array_key_exists($seg, $data)) {
                $data = $data[$seg];
                continue;
            }

            // دعم indexes: "items.0.id"
            if (is_array($data) && ctype_digit($seg)) {
                $idx = (int) $seg;
                $data = $data[$idx] ?? null;
                continue;
            }

            return null;
        }

        return $data;
    }
}
