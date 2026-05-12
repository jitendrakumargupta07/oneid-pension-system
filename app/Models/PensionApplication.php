<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PensionApplication extends Model
{
    use HasFactory;

    protected $fillable = [
        'elderly_profile_id', 'scheme_id', 'status', 'applied_at',
        'reviewed_at', 'reviewed_by', 'remarks', 'application_number',
        'fraud_flagged', 'eligibility_score',
    ];

    protected $casts = [
        'applied_at'    => 'datetime',
        'reviewed_at'   => 'datetime',
        'fraud_flagged' => 'boolean',
    ];

    // Relationships
    public function elderlyProfile()
    {
        return $this->belongsTo(ElderlyProfile::class);
    }

    public function scheme()
    {
        return $this->belongsTo(PensionScheme::class);
    }

    public function reviewedBy()
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }

    public function payments()
    {
        return $this->hasMany(PensionPayment::class);
    }

    public function documents()
    {
        return $this->hasMany(ApplicationDocument::class);
    }

    // Generate unique application number
    public static function generateApplicationNumber(): string
    {
        do {
            $number = 'APP-' . date('Ymd') . '-' . str_pad(random_int(1000, 9999), 4, '0', STR_PAD_LEFT);
        } while (self::where('application_number', $number)->exists());

        return $number;
    }

    // Helpers
    public function isPending(): bool  { return $this->status === 'pending'; }
    public function isApproved(): bool { return $this->status === 'approved'; }
    public function isRejected(): bool { return $this->status === 'rejected'; }

    public function pendingDocuments(): int
    {
        return $this->documents()->where('status', 'pending')->count();
    }
}
