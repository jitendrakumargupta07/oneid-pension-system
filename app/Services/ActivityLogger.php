<?php

namespace App\Services;

use App\Models\ActivityLog;
use Illuminate\Database\Eloquent\Model;

/**
 * ActivityLogger — records admin/user actions for the audit trail.
 */
class ActivityLogger
{
    public static function log(
        string $action,
        string $description,
        ?Model $subject = null,
        ?int $userId = null
    ): ActivityLog {
        return ActivityLog::create([
            'user_id'     => $userId ?? auth()->id(),
            'action'      => $action,
            'model_type'  => $subject ? class_basename($subject) : null,
            'model_id'    => $subject?->id,
            'description' => $description,
            'ip_address'  => request()->ip(),
            'user_agent'  => request()->userAgent(),
        ]);
    }
}
