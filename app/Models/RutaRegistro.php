<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RutaRegistro extends Model
{
    use HasFactory;

    protected $fillable = [
        'ruta_id',
        'fecha',
        'estado',
        'observaciones'
    ];

    protected $dates = ['fecha'];

    public function ruta()
    {
        return $this->belongsTo(Ruta::class);
    }
}
