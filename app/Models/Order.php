<?php

namespace App\Models;

use Carbon\Carbon;
use App\Enums\OrderStatus;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $table = 'orders';

    protected $fillable = [
        'order_number',
        'table_id',
        'table_session_id',
        'user_id',
        'subtotal',
        'pb1',
        'service_charge',
        'rounding',
        'total',
        'status',
        'print_status',
        'note',
    ];

    protected $casts = [
        'status' => OrderStatus::class,
    ];

    public const PRINT_PENDING = 'pending';
    public const PRINTED = 'printed';

    public function table()
    {
        return $this->belongsTo(Meja::class);
    }

    public function tableSession()
    {
        return $this->belongsTo(TableSession::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function payment()
    {
        return $this->hasOne(Payment::class);
    }

    public static function generateOrderCode(int $orderId, int $tableNumber, ?Carbon $date = null): string
    {
        $day = ($date ?? now())->format('d');

        return 'AJ'
            . $orderId
            . 'T'
            . 'D'
            . $tableNumber
            . $day;
    }
}
