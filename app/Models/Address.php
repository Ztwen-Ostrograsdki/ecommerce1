<?php

namespace App\Models;

use App\Models\Order;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Address extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'first_name',
        'last_name',
        'phone',
        'images',
        'image',
        'street_address',
        'city',
        'state',
        'zip_code',
        

    ];

    protected $casts = [
        'images' => 'array',

    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function getFullName()
    {
        return self::getFullNameAttribute();
    }

    public function getFullNameAttribute()
    {
        return "{$this->first_name} {$this->last_name}";
    }

   
}
