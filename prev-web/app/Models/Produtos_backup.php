<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Produtos extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'produtos';

    protected $fillable = [
        'nome',
        'preco',
        'quantidade',
        'categoria_id',
        'marca_id',
        'unidade_id',
        'empresa_id',
    ];

    public function categoria()
    {
        return $this->belongsTo(Categorias::class, 'categoria_id');
    }

    public function marca()
    {
        return $this->belongsTo(Marcas::class, 'marca_id');
    }

    public function unidade()
    {
        return $this->belongsTo(Unidades::class, 'unidade_id');
    }

    public function empresa()
    {
        return $this->belongsTo(Empresas::class, 'empresa_id');
    }
}
