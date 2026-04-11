<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TeamEvent extends Model
{
    public $timestamps = false;

    protected $primaryKey = 'idEvent';

    protected $fillable = [
        'nazwa',
        'opis',
        'data_i_czas_start',
        'data_i_czas_koniec',
        'miejsce',
        'status',
        'kto_utworzyl_id',
        'kto_aktualizowal_id',
    ];

    protected $casts = [
        'data_i_czas_start'  => 'datetime',
        'data_i_czas_koniec' => 'datetime',
    ];

    public function utworzyl()
    {
        return $this->belongsTo(User::class, 'kto_utworzyl_id', 'id');
    }
}
