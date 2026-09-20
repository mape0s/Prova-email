<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
    public $timestamps = false;

    protected $fillable = ['role_id', 'resource_id', 'permission'];

    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    public function resource()
    {
        return $this->belongsTo(Resource::class);
    }
}