<?php

namespace Omarsaiouf\Integrations\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Omarsaiouf\Integrations\Enums\AuthType;

class Provider extends Model
{
    use HasUuids;
    protected $table = 'di_providers';
    protected $fillable = [
        'key',
        'url',
        'auth_type',
        'auth_meta'
    ];

    protected $casts = [
        'auth_type' => AuthType::class,
        'auth_meta' => 'array',
    ];

    public function endpoints()
    {
        return $this->hasMany(Endpoint::class);
    }

}