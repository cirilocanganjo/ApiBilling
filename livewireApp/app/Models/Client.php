<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Client extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'clients';

    protected $fillable = [
        'company_id',
        'name',
        'tax_id',
        'country_id',
        'province_id',
        'city_id',
        'address',
        'complement',
        'neighborhood',
        'postal_code',
        'recipient',
        'notes',
        'phone',
        'email',
        'created_by',
        'updated_by'
    ];

    
    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at'
    ];

     // Relacionamento com Country
     public function country()
     {
         return $this->belongsTo(Country::class);
     }
 
     // Relacionamento com Province
     public function province()
     {
         return $this->belongsTo(Province::class);
     }
 
     // Relacionamento com City
     public function city()
     {
         return $this->belongsTo(City::class);
     }
     
    // Relacionamentos
    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function sales()
    {
        return $this->hasMany(Sale::class);
    }
}
