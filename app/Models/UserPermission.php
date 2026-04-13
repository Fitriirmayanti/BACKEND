<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserPermission extends Model
{
    protected $table = 'user_permission';
    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'menu_permission_id',
    ];

    public function menuPermission()
    {
        return $this->belongsTo(MenuPermission::class);
    }
}
