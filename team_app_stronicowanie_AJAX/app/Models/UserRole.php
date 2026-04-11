<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserRole extends Model
{
    public $timestamps = false;
    protected $table = 'user_roles';

    protected $fillable = [
        'idUser',
        'idRola',
        'data_nadania',
        'kto_nadan_id',
        'data_odebrania',
        'kto_odbral_id',
        'jest_aktywna',
    ];

    public function role()
    {
        return $this->belongsTo(Role::class, 'idRola', 'idRola');
    }
}
