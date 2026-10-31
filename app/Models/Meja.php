<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Meja extends Model
{
    protected $table = 'tables';

    protected $fillable = [
        'table_number',
        'qr_token',
        'is_active',
    ];

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function tableSessions()
    {
        return $this->hasMany(TableSession::class);
    }
}
