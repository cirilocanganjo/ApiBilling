<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Category extends Model
{
    use SoftDeletes;

    protected $guarded = ['id', 'uuid'];

    protected $fillable = [      
        'name',
        'description',
        'company_id',
        'created_by',
        'updated_by',
        'is_deleted',
        'deleted_by',
    ];

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

    // Relacionamentos (mantém como tens)
    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function stored_by_user()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updated_by_user()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function deleted_by_user()
    {
        return $this->belongsTo(User::class, 'deleted_by');
    }
}