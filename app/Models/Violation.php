<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Violation extends Model
{
    protected $fillable = [
        'user_id',
        'permission_id',
        'reason',
        'manager_mistake'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function permissionRequest()
    {
        return $this->belongsTo(PermissionRequest::class);
    }
}
