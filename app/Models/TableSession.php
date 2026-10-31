<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TableSession extends Model
{
    protected $fillable = [
        'table_id',
        'token',
        'is_active',
        'started_at',
        'ended_at'
    ];

    public function table()
    {
        return $this->belongsTo(Meja::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }
}
