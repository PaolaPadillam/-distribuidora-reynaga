<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pago extends Model
{
    use HasFactory;

    protected $primaryKey = 'id_pago';

    protected $fillable = [
        'id_credito',
        'fecha_pago',
        'monto_pago',
        'metodo_pago',
        'observaciones',
    ];

    public function credito()
    {
        return $this->belongsTo(Credito::class, 'id_credito');
    }
}

