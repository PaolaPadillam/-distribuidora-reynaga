<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cliente extends Model
{
    protected $table = 'clientes';

    protected $fillable = [
        'nombre',
        'direccion',
        'telefono',
        'email',
        'tipo_cliente',
        'maneja_credito',
        'limite_credito',
        'saldo_actual'
    ];

    public function rutas()
    {
        return $this->belongsToMany(Ruta::class, 'ruta_clientes', 'cliente_id', 'ruta_id');
    }

    public function ventas(){ return $this->hasMany(Venta::class, 'id_cliente', 'id'); }
    public function creditos(){ return $this->hasMany(Credito::class, 'id_cliente', 'id'); }


}
