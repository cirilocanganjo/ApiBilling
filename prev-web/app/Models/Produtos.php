<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Produtos extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'produtos';

    // Campos permitidos para mass assignment
    protected $fillable = [
        'codigo',
        'codigo_extra',
        'ean_gtin',
        'nome',
        'empresa_id',
        'categoria_id',
        'subcategoria_id',
        'marca_id',
        'localizacao',
        'preco_venda',
        'preco_custo',
        'markup_percent',
        'preco_alteravel',
        'seguir_markup',
        'controlar_estoque',
        'estoque_atual',
        'limite_estoque_id',
        'unidade_id',
        'permite_fracionamento',
        'is_inativo',
        'imagem', // <- adicionar aqui
        'is_deleted',
        'created_by',
        'updated_by',
        'deleted_by'
    ];
    

    protected $dates = ['created_at', 'updated_at', 'deleted_at'];

    // RELACIONAMENTOS

    public function empresa()
    {
        return $this->belongsTo(Empresas::class, 'empresa_id');
    }

    public function categoria()
    {
        return $this->belongsTo(Categorias::class, 'categoria_id');
    }

    public function subcategoria()
    {
        return $this->belongsTo(Categorias::class, 'subcategoria_id');
    }

    public function marca()
    {
        return $this->belongsTo(Marcas::class, 'marca_id');
    }

    public function unidade()
    {
        return $this->belongsTo(Unidades::class, 'unidade_id');
    }

    public function limiteEstoque()
    {
        return $this->belongsTo(LimitesEstoque::class, 'limite_estoque_id');
    }
}
