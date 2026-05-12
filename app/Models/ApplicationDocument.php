<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ApplicationDocument extends Model
{
    use HasFactory;

    protected $fillable = [
        'pension_application_id', 'document_type', 'file_path',
        'original_name', 'status', 'admin_remarks', 'reviewed_by', 'reviewed_at',
    ];

    protected $casts = [
        'reviewed_at' => 'datetime',
    ];

    // Human-readable document type labels
    public static array $typeLabels = [
        'aadhaar_card'           => 'Aadhaar Card',
        'passport_photo'         => 'Passport Photograph',
        'income_certificate'     => 'Income Certificate',
        'retirement_certificate' => 'Retirement Certificate',
        'disability_certificate' => 'Disability Certificate',
        'death_certificate'      => 'Death Certificate (Spouse)',
        'bank_passbook'          => 'Bank Passbook / Statement',
        'age_proof'              => 'Age / DOB Proof',
        'residence_proof'        => 'Residence Proof',
        'farmer_certificate'     => 'Farmer / Land Certificate',
    ];

    public function getTypeLabelAttribute(): string
    {
        return self::$typeLabels[$this->document_type] ?? ucwords(str_replace('_', ' ', $this->document_type));
    }

    // Relationships
    public function application()
    {
        return $this->belongsTo(PensionApplication::class, 'pension_application_id');
    }

    public function reviewedBy()
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }

    // Helper
    public function isPending(): bool  { return $this->status === 'pending'; }
    public function isApproved(): bool { return $this->status === 'approved'; }
    public function isRejected(): bool { return $this->status === 'rejected'; }
}
