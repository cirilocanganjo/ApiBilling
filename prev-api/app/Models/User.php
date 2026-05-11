<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use \Illuminate\Support\Str;

class User extends Authenticatable
{
       
    use HasApiTokens,SoftDeletes,HasFactory, Notifiable;   
    const TYPE_SUPER_ADMIN = 'SUPER_ADMIN';
    const TYPE_ADMIN  = 'ADMIN';
    const TYPE_USER = 'USER';

    protected $guarded = ['id', 'uuid'];
    protected $fillable = [       
        'name',        
        'email',
        'password',
        'company_id',
        'type',
        'status',
        'last_login_at',
        'created_by',
        'updated_by',
        'is_deleted',
        'deleted_by',
    ];

    
    protected $hidden = [
        'password',
        'remember_token',
    ];

  
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
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

    public function isAdmin(): bool
    {
        return in_array($this->type, [self::TYPE_ADMIN]);
    }


    public function isSuperAdmin(): bool
    {
        return in_array($this->type, [self::TYPE_SUPER_ADMIN]);
    }

    public function deleted_by_user() {
        return $this->belongsTo(User::class, 'deleted_by', 'id');
    }

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
