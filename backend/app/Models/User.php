<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class User extends Model
{
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id',
        'username',
        'email',
        'password',
        'birth_date',
        'is_admin',
        'forgot_password_token',
        'forgot_password_expired'
    ];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            $model->id = (string) Str::uuid();
        });
    }

    public function orderDetails()
    {
        return $this->hasMany(OrderDetail::class);
    }
}
