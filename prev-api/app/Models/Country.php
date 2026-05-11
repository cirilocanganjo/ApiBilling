<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Country extends Model
{
    use SoftDeletes;
    protected $guarded = ['id', 'uuid'];
    protected $fillable = [
        'iso_code',
        'name',
        'created_by',
        'updated_by',
        'is_deleted',
        'deleted_by',
    ];

    public function stored_by_user() {
        return $this->belongsTo(User::class, 'created_by', 'id');
    }

    public function updated_by_user() {
        return $this->belongsTo(User::class, 'updated_by', 'id');
    }

    public function deleted_by_user() {
        return $this->belongsTo(User::class, 'deleted_by', 'id');
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
