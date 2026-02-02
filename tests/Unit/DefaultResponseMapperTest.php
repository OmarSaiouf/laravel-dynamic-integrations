<?php

namespace Omarsaiouf\Integrations\Tests\Unit;

use Omarsaiouf\Integrations\DTOs\HttpResponse;
use Omarsaiouf\Integrations\Exceptions\MappingException;
use Omarsaiouf\Integrations\Mapping\DefaultResponseMapper;
use Omarsaiouf\Integrations\Tests\TestCase;

class DefaultResponseMapperTest extends TestCase
{
    public function test_maps_simple_object_fields(): void
    {
        $mapper = new DefaultResponseMapper();
        $response = new HttpResponse(200, [], ['id' => 10, 'title' => 'Hello'], '{}', true);

        $rules = [
            'id' => 'id',
            'name' => 'title',
        ];

        $result = $mapper->map($rules, $response);

        $this->assertSame(
            ['id' => 10, 'name' => 'Hello'],
            $result->data
        );
    }

    public function test_maps_each_operator(): void
    {
        $mapper = new DefaultResponseMapper();
        $response = new HttpResponse(200, [], [
            ['id' => 1, 'title' => 'A'],
            ['id' => 2, 'title' => 'B'],
        ], '[]', true);

        $rules = [
            '@each' => '.',
            'map' => [
                'id' => 'id',
                'title' => 'title',
            ],
        ];

        $result = $mapper->map($rules, $response);

        $this->assertSame(
            [
                ['id' => 1, 'title' => 'A'],
                ['id' => 2, 'title' => 'B'],
            ],
            $result->data
        );
    }

    public function test_each_requires_array(): void
    {
        $this->expectException(MappingException::class);

        $mapper = new DefaultResponseMapper();
        $response = new HttpResponse(200, [], ['id' => 1], '{}', true);

        $rules = [
            '@each' => 'id',
            'map' => ['id' => 'id'],
        ];

        $mapper->map($rules, $response);
    }
}
