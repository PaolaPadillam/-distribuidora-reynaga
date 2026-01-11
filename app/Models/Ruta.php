<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Ruta extends Model
{
    use HasFactory;

    protected $fillable = ['nombre_ruta', 'dia_semana', 'color'];

    public function clientes()
    {
        return $this->belongsToMany(Cliente::class, 'ruta_clientes', 'ruta_id', 'cliente_id');
    }

    public function registros()
    {
        return $this->hasMany(\App\Models\RutaRegistro::class, 'ruta_id');
    }

}
