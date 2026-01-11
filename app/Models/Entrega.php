<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Entrega extends Model
{
    use HasFactory;

    protected $table = 'entregas';

    protected $fillable = [
        'id_ruta',
        'id_cliente',
        'id_venta',
        'fecha',
        'estado',
    ];

    /** 🔗 Relación: la entrega pertenece a una ruta */
    public function ruta()
    {
        return $this->belongsTo(Ruta::class, 'id_ruta');
    }

    /** 🔗 Relación: la entrega pertenece a un cliente */
    public function cliente()
    {
        return $this->belongsTo(Cliente::class, 'id_cliente');
    }

    /** 🔗 Relación: la entrega pertenece a una venta */
    public function venta()
    {
        return $this->belongsTo(Venta::class, 'id_venta');
    }
}
