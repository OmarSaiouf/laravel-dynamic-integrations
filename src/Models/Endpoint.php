<?php

namespace Omarsaiouf\Integrations\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Omarsaiouf\Integrations\Enums\HttpMethod;

class Endpoint extends Model
{
    use HasUuids;
    protected $table = 'di_endpoints';
    protected $fillable = [
        'key',
        'provider_id',
        'method',
        'path',
        'headers',
        'query',
        'body'
    ];

    protected $casts = [
        'method' => HttpMethod::class
    ];

    public function provider()
    {
        return $this->belongsTo(Provider::class);
    }

    public function mapping()
    {
        return $this->hasOne(Mapping::class);
    }

}