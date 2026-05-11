<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Empresas extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'empresas';

    protected $fillable = [
        'nome', 'nif', 'email', 'telefone', 'endereco', 'referencia', 'status', 
        'created_by', 'updated_by', 'deleted_by'
    ];

    protected $dates = ['created_at','updated_at','deleted_at'];

    public function usuarios()
    {
        return $this->hasMany(Usuarios::class);
    }

    public function clientes()
    {
        return $this->hasMany(Clientes::class);
    }

    public function produtos()
    {
        return $this->hasMany(Produtos::class);
    }

    public function categorias()
    {
        return $this->hasMany(Categorias::class);
    }

    public function marcas()
    {
        return $this->hasMany(Marcas::class);
    }

    public function unidades()
    {
        return $this->hasMany(Unidades::class);
    }

}
