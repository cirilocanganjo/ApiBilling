<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Unidades extends Model
{
    protected $table = 'unidades';
    protected $fillable = [
        'empresa_id',
        'nome',
        'sigla',
        'is_deleted',
        'created_by',
        'updated_by',
        'deleted_by',
        'deleted_at'
    ];

    public function empresa()
    {
        return $this->belongsTo(Empresas::class, 'empresa_id');
    }
}
