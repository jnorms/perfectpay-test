<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Client extends Model
{
    protected $fillable = [
        'addressable_type',
        'addressable_id',
        'name',
        'document',
        'email',
        'mobile_phone',
        'asaas_id'
    ];
    
    public function addresses(): MorphMany
    {
        return $this->morphMany(Address::class, 'addressable', 'addressable_type', 'addressable_id');
    }
}
