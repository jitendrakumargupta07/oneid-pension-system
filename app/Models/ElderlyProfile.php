<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ElderlyProfile extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'one_id', 'full_name', 'age', 'gender', 'address',
        'phone', 'aadhaar_number', 'bank_account_number', 'bank_name',
        'ifsc_code', 'profile_photo', 'government_id_photo',
        'is_verified', 'verified_at', 'verified_by', 'verification_remarks',
    ];

    protected $casts = [
        'is_verified' => 'boolean',
        'verified_at' => 'datetime',
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function verifiedBy()
    {
        return $this->belongsTo(User::class, 'verified_by');
    }

    public function pensionApplications()
    {
        return $this->hasMany(PensionApplication::class);
    }

    public function activeApplication()
    {
        return $this->hasOne(PensionApplication::class)->where('status', 'approved');
    }

    // Generate a unique OneID
    public static function generateOneId(): string
    {
        do {
            $id = 'OID-' . date('Y') . '-' . str_pad(random_int(100000, 999999), 6, '0', STR_PAD_LEFT);
        } while (self::where('one_id', $id)->exists());

        return $id;
    }

    // Scopes
    public function scopeVerified($query)
    {
        return $query->where('is_verified', true);
    }

    public function scopePending($query)
    {
        return $query->where('is_verified', false);
    }
}
