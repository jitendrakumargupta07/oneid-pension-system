<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ElderlyProfile extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'one_id', 'full_name', 'age', 'date_of_birth', 'gender',
        'address', 'phone', 'aadhaar_number', 'bank_account_number',
        'bank_name', 'ifsc_code', 'profile_photo', 'government_id_photo',
        'is_verified', 'verified_at', 'verified_by', 'verification_remarks',
        // Advanced eligibility fields
        'employment_status', 'disability_percentage', 'is_widow',
        'income_level', 'marital_status', 'caste_category',
    ];

    protected $casts = [
        'is_verified'          => 'boolean',
        'is_widow'             => 'boolean',
        'verified_at'          => 'datetime',
        'date_of_birth'        => 'date',
        'disability_percentage'=> 'integer',
        'income_level'         => 'float',
    ];

    public static array $employmentLabels = [
        'unemployed'       => 'Unemployed / Not Working',
        'retired'          => 'Retired (Private Sector)',
        'retired_govt'     => 'Retired Government Employee',
        'farmer'           => 'Farmer / Agricultural Worker',
        'self_employed'    => 'Self-employed / Small Business',
        'employed'         => 'Currently Employed',
    ];

    public static array $casteLabels = [
        'general' => 'General',
        'obc'     => 'OBC',
        'sc'      => 'SC (Scheduled Caste)',
        'st'      => 'ST (Scheduled Tribe)',
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

    public function fraudAlerts()
    {
        return $this->hasMany(FraudAlert::class);
    }

    public function openFraudAlerts()
    {
        return $this->hasMany(FraudAlert::class)->where('status', 'open');
    }

    // Generate a unique OneID
    public static function generateOneId(): string
    {
        do {
            $id = 'OID-' . date('Y') . '-' . str_pad(random_int(100000, 999999), 6, '0', STR_PAD_LEFT);
        } while (self::where('one_id', $id)->exists());

        return $id;
    }

    // Accessors
    public function getEmploymentLabelAttribute(): string
    {
        return self::$employmentLabels[$this->employment_status] ?? ucfirst($this->employment_status ?? '');
    }

    public function getCasteLabelAttribute(): string
    {
        return self::$casteLabels[$this->caste_category] ?? ucfirst($this->caste_category ?? '');
    }

    // Scopes
    public function scopeVerified($query)   { return $query->where('is_verified', true); }
    public function scopePending($query)    { return $query->where('is_verified', false); }
}
