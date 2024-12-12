<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Http\Controllers\PermissionRequestController;
class Violation extends Model
{
    protected $fillable = [
        'permission_id',
        'user_id',
        'reason',
        'manager_mistake'
    ];

    protected $casts = [
        'manager_mistake' => 'boolean',
    ];

    public function permission()
    {
        return $this->belongsTo(PermissionRequestController::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
