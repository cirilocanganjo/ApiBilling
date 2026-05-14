<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Province extends Model
{
    protected $table = 'provinces';
    protected $fillable = ['country_id', 'name', 'iso_code'];

    // Relacionamentos
    public function country()
    {
        return $this->belongsTo(Country::class);
    }

    public function cities()
    {
        return $this->hasMany(City::class);
    }

    public function clients()
    {
        return $this->hasMany(Client::class);
    }

    public function suppliers()
    {
        return $this->hasMany(Supplier::class);
    }
}
