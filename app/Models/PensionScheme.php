<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PensionScheme extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'description', 'monthly_amount', 'eligibility_age', 'status', 'scheme_code',
    ];

    protected $casts = [
        'monthly_amount' => 'decimal:2',
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
}
