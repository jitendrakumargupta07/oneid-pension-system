<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FraudAlert extends Model
{
    use HasFactory;

    protected $fillable = [
        'elderly_profile_id', 'alert_type', 'description',
        'severity', 'status', 'resolved_by', 'resolved_at', 'resolution_notes',
    ];

    protected $casts = [
        'resolved_at' => 'datetime',
    ];

    public static array $typeLabels = [
        'duplicate_aadhaar'      => 'Duplicate Aadhaar Number',
        'duplicate_bank_account' => 'Duplicate Bank Account',
        'multiple_pensions'      => 'Multiple Pension Claims',
        'suspicious_age'         => 'Suspicious Age / DOB',
        'repeated_application'   => 'Repeated Application',
        'invalid_documents'      => 'Invalid / Fake Documents',
    ];

    public static array $typeIcons = [
        'duplicate_aadhaar'      => 'fa-id-card',
        'duplicate_bank_account' => 'fa-university',
        'multiple_pensions'      => 'fa-copy',
        'suspicious_age'         => 'fa-calendar-times',
        'repeated_application'   => 'fa-redo',
        'invalid_documents'      => 'fa-file-times',
    ];

    // Relationships
    public function elderlyProfile()
    {
        return $this->belongsTo(ElderlyProfile::class);
    }

    public function resolvedBy()
    {
        return $this->belongsTo(User::class, 'resolved_by');
    }

    // Helpers
    public function isOpen(): bool     { return $this->status === 'open'; }
    public function isResolved(): bool { return $this->status === 'resolved'; }

    public function getTypeLabelAttribute(): string
    {
        return self::$typeLabels[$this->alert_type] ?? ucwords(str_replace('_', ' ', $this->alert_type));
    }

    public function getSeverityColorAttribute(): string
    {
        return match($this->severity) {
            'high'   => 'danger',
            'medium' => 'warning',
            'low'    => 'info',
            default  => 'secondary',
        };
    }
}
