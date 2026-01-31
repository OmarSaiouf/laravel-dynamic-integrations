<?php

namespace Omarsaiouf\Integrations\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Omarsaiouf\Integrations\Enums\MappingMode;

class Mapping extends Model
{
    use HasUuids;
    protected $table = 'di_mappings';
    protected $fillable = [
        'endpoint_id',
        'type',
        'rules',
    ];

    protected $casts = [
        'type' => MappingMode::class,
        'rules' => 'array',
    ];

    public function endpoint()
    {
        return $this->belongsTo(Endpoint::class);
    }

}