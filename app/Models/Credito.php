<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Credito extends Model
{
    use HasFactory;

    protected $primaryKey = 'id_credito';

    protected $fillable = [
        'id_cliente',
        'id_venta',
        'monto_total',
        'saldo_pendiente',
        'fecha_inicio',
        'fecha_vencimiento',
        'estado',
    ];

    // 🔗 Relaciones
    public function cliente()
    {
        return $this->belongsTo(Cliente::class, 'id_cliente');
    }

    public function venta()
    {
        return $this->belongsTo(Venta::class, 'id_venta');
    }

    public function pagos()
    {
        return $this->hasMany(Pago::class, 'id_credito');
    }
}

