<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Ramsey\Uuid\Uuid;

class Order extends Model
{
    protected $fillable = [
        'client_id',
        'total',
        'discount',
        'billing_type',
        'asaas_id',
    ];
    
    protected static function boot()
    {
        parent::boot();
        parent::creating(function ($model) {
            $model->uuid = Uuid::uuid4()->toString();
        });
    }
}
