<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Enums\PaymentStatus;

class Payment extends Model
{
    protected $fillable = [
        'order_id',
        'payment_method',
        'qr_token',
        'provider',
        'reference_id',
        'external_id',
        'amount',
        'status',
        'paid_at',
        'payload',
    ];

    protected $casts = [
        'status' => PaymentStatus::class,
        'paid_at' => 'datetime',
        'payload' => 'array',
    ];

    // Relasi ke order
    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
