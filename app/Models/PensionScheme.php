<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PensionScheme extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'description', 'monthly_amount', 'eligibility_age', 'status',
        'scheme_code', 'type', 'min_age', 'max_income',
        'requires_disability', 'requires_widow_status',
        'requires_govt_employment', 'requires_farmer_status',
        'required_documents', 'eligibility_description',
    ];

    protected $casts = [
        'monthly_amount'           => 'decimal:2',
        'max_income'               => 'decimal:2',
        'requires_disability'      => 'boolean',
        'requires_widow_status'    => 'boolean',
        'requires_govt_employment' => 'boolean',
        'requires_farmer_status'   => 'boolean',
        'required_documents'       => 'array',
    ];

    public static array $typeLabels = [
        'old_age'       => 'Old Age Pension',
        'govt_employee' => 'Government Employee Pension',
        'widow'         => 'Widow Pension',
        'disability'    => 'Disability Pension',
        'farmer'        => 'Farmer Pension',
        'family'        => 'Family Pension',
    ];

    public static array $typeIcons = [
        'old_age'       => 'fa-user-clock',
        'govt_employee' => 'fa-landmark',
        'widow'         => 'fa-heart-broken',
        'disability'    => 'fa-wheelchair',
        'farmer'        => 'fa-seedling',
        'family'        => 'fa-users',
    ];

    // Relationships
    public function applications()
    {
        return $this->hasMany(PensionApplication::class, 'scheme_id');
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    // Helpers
    public function getTypeLabelAttribute(): string
    {
        return self::$typeLabels[$this->type] ?? ucfirst($this->type ?? '');
    }

    public function getTypeIconAttribute(): string
    {
        return self::$typeIcons[$this->type] ?? 'fa-file-alt';
    }
}
