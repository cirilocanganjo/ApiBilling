<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Marcas extends Model
{
    protected $table = 'marcas';
    protected $fillable = [
        'empresa_id',
        'nome',
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
