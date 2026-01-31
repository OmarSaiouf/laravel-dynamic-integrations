<?php

namespace Omarsaiouf\Integrations\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Omarsaiouf\Integrations\Enums\HttpMethod;
use Omarsaiouf\Integrations\Enums\RunStatus;

class Run extends Model
{
    use HasUuids;
    protected $table = 'di_runs';
    protected $fillable = [
        'provider_key',
        'endpoint_key',
        'status',
        'http_status',
        'duration_ms',
        'request_snapshot',
        'response_snapshot',
        'error'
    ];

    protected $casts = [
        'status' => RunStatus::class
    ];

    public function provider()
    {
        return $this->hasMany(Provider::class, 'provider_key');
    }

}