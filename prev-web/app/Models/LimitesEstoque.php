<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class LimitesEstoque extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'limites_estoque';

    protected $fillable = [
        'empresa_id',
        'descricao',
        'quantidade_min',
        'quantidade_max',
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

    public function produtos()
    {
        return $this->hasMany(Produtos::class, 'limite_estoque_id');
    }
}
