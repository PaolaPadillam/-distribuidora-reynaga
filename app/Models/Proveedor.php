<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Proveedor extends Model
{
    protected $table = 'proveedores';
    protected $primaryKey = 'id_proveedor'; // 👈 agrega esta línea si tu campo se llama así
    public $incrementing = true; // asegura que sea autoincremental
    protected $keyType = 'int';  // define tipo entero

    protected $fillable = [
        'nombre',
        'direccion',
        'telefono',
        'email'
    ];
    public function productos()
    {
        return $this->hasMany(Producto::class, 'proveedor_id', 'id_proveedor');
    }

}
