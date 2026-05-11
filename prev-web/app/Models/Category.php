<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes; // Para soft delete

class Category extends Model
{
    use HasFactory, SoftDeletes;

    // Se a tabela tiver um nome diferente do padrão (Categorias)
    protected $table = 'categories';

    // Campos que podem ser preenchidos via Mass Assignment
    protected $fillable = [
        'name',
        'description',       
        'created_by',
        'updated_by',
    ];

    // Campos que são tratados como datas
    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    // Relacionamentos

}
