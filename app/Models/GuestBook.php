<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GuestBook extends Model
{
    use HasFactory;

    protected $table = 'guestbook';

    protected $fillable = [
        'name',
        'review'
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
