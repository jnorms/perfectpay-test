<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Ramsey\Uuid\Uuid;

class Product extends Model
{
    protected $fillable = [
        'name',
        'price',
    ];
    
    protected static function boot()
    {
        parent::boot();
        parent::creating(function ($model) {
            $model->uuid = Uuid::uuid4()->toString();
        });
    }
}
