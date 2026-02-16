<?php

namespace App\Services;

use App\Models\ActivityLog;
use Illuminate\Database\Eloquent\Model;

class ActivityLogService
{
    /**
     * Record an activity log entry.
     *
     * @param string      $action      Short verb: login, created, updated, deleted …
     * @param string      $description Human-readable sentence
     * @param Model|null  $subject     The affected Eloquent model (optional)
     * @param array|null  $properties  Extra context data (optional)
     */
    public static function log(
        string $action,
        string $description,
        ?Model $subject = null,
        ?array $properties = null
    ): ActivityLog {
        return ActivityLog::create([
            'user_id'      => auth()->id(),
            'action'       => $action,
            'description'  => $description,
            'subject_type' => $subject ? get_class($subject) : null,
            'subject_id'   => $subject?->getKey(),
            'properties'   => $properties,
            'ip_address'   => request()->ip(),
            'created_at'   => now(),
        ]);
    }
}
