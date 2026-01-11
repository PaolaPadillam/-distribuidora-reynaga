<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Producto extends Model
{
    use HasFactory;

    protected $table = 'productos';
    protected $primaryKey = 'id_producto';
    protected $fillable = [
        'nombre_producto',
        'descripcion',
        'precio_mayoreo',
        'precio_menudeo',
        'stock',
        'unidad',
        'proveedor_id',
        'fecha_caducidad',
    ];

    // Convertimos fecha_caducidad en objeto Carbon
    protected $casts = [
        'fecha_caducidad' => 'date',
    ];

    public function proveedor()
    {
        return $this->belongsTo(Proveedor::class, 'proveedor_id', 'id_proveedor');
    }
}
