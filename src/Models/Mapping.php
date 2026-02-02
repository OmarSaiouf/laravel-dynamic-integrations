<?php

namespace Omarsaiouf\Integrations\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Omarsaiouf\Integrations\Enums\MappingMode;

class Mapping extends Model
{
    use HasUuids;
    protected $table = 'di_mappings';

    /*
    |--------------------------------------------------------------------------
    | Fillable
    |--------------------------------------------------------------------------
    | endpoint_id: the owning endpoint ID
    | rules: JSON mapping rules used by DefaultResponseMapper
    |
    | Example rules:
    | [
    |   'id' => 'id',
    |   'user_id' => 'userId',
    |   '@each' => '.',
    |   'map' => ['id' => 'id']
    | ]
    */
    protected $fillable = [
        'endpoint_id',
        'rules',
    ];

    protected $casts = [
        'rules' => 'array',
    ];

    public function endpoint()
    {
        return $this->belongsTo(Endpoint::class);
    }

}
