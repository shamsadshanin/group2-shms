<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AuditLog extends Model
{
    use HasFactory;

    protected $primaryKey = 'LogID';

    protected $fillable = [
        'user_id', 'action', 'description', 'old_values',
        'new_values', 'ip_address', 'user_agent', 'performed_at'
    ];

    protected $casts = [
        'old_values' => 'array',
        'new_values' => 'array',
        'performed_at' => 'datetime',
    ];

    // Relationship with User
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Scope for recent logs
    public function scopeRecent($query, $days = 7)
    {
        return $query->where('performed_at', '>=', now()->subDays($days));
    }

    // Scope by action type
    public function scopeByAction($query, $action)
    {
        return $query->where('action', $action);
    }

    // Log an action
    public static function logAction($user, $action, $description, $oldValues = null, $newValues = null)
    {

        return self::create([
            'user_id' => $user->id,
            'action' => $action,
            'description' => $description,
            'old_values' => $oldValues,
            'new_values' => $newValues,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'performed_at' => now(),
        ]);
    }
}
