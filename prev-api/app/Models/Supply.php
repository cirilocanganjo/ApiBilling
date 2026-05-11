<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Supply extends Model
{
    use SoftDeletes;
    protected $guarded = ['id', 'uuid'];
    protected $fillable  = [
        'company_id',
        'name',
        'natural_person',
        'tax_id',
        'country_id',
        'province_id',
        'city_id',
        'address',
        'complement',
        'neighborhood',
        'postal_code',
        'contact_person',
        'notes',
        'phone',
        'email',
        'created_at',
        'updated_at',
        'created_by',
        'updated_by',
        'is_deleted',
        'deleted_by',
    ];


    public function city () {
        return $this->belongsto(City::class);
    } 

    public function province () {
        return $this->belongsto(Province::class);

    }

     public function country () {
        return $this->belongsto(Country::class);
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

  public function stored_by_user() {
        return $this->belongsTo(User::class, 'created_by', 'id');
    }

    public function updated_by_user() {
        return $this->belongsTo(User::class, 'updated_by', 'id');
    }

    // Gera automaticamente o UUID ao criar um novo registo
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->uuid)) {
                $model->uuid = (string) Str::uuid();
            }
        });
    }
}
