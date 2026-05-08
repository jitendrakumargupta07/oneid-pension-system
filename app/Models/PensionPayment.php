<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PensionPayment extends Model
{
    use HasFactory;

    protected $fillable = [
        'pension_application_id', 'amount', 'payment_date', 'month',
        'year', 'status', 'transaction_ref', 'receipt_number', 'remarks',
    ];

    protected $casts = [
        'payment_date' => 'date',
        'amount'       => 'decimal:2',
    ];

    // Relationships
    public function application()
    {
        return $this->belongsTo(PensionApplication::class, 'pension_application_id');
    }

    // Generate unique receipt number
    public static function generateReceiptNumber(): string
    {
        do {
            $number = 'RCP-' . date('Y') . '-' . str_pad(random_int(100000, 999999), 6, '0', STR_PAD_LEFT);
        } while (self::where('receipt_number', $number)->exists());

        return $number;
    }

    // Month name helper
    public function getMonthNameAttribute(): string
    {
        return date('F', mktime(0, 0, 0, $this->month, 1));
    }
}
