<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    use HasFactory;

    // Campos permitidos para mass assignment
    protected $fillable = [
        'name',
        'email',
        'phone',
        'address',
        'complement',
        'neighborhood',
        'postal_code',
        'city_id',
        'province_id',
        'country_id',
        'natural_person', // nova coluna
    ];

    /**
     * Relacionamentos
     */

    // Cidade
    public function city()
    {
        return $this->belongsTo(City::class);
    }

    // Província
    public function province()
    {
        return $this->belongsTo(Province::class);
    }

    // País
    public function country()
    {
        return $this->belongsTo(Country::class);
    }
}
