<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MenuPermission extends Model
{
    protected $table = 'menu_permission';
    public $timestamps = false;

    protected $fillable = [
        'menu_id',
        'permission_id',
    ];

    public function menu()
    {
        return $this->belongsTo(Menu::class);
    }

    public function permission()
    {
        return $this->belongsTo(Permission::class);
    }
}
