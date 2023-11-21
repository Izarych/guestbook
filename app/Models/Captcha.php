<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Captcha extends Model
{
    use HasFactory;

    protected $fillable = [
        'answer',
        'key'
    ];

    public static function boot(): void
    {
        parent::boot();

        static::creating(function ($model) {
            $model->timestamps = false;
            $model->created_at= now();
        });
    }
}
