<?php

namespace App\Services;

use App\Models\SecurityAuditLog;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SecurityAuditService
{
    public function log(
        string $module,
        string $eventType,
        string $action,
        ?Model $auditable = null,
        array $old = [],
        array $new = [],
        array $metadata = [],
        string $status = 'success',
        ?Request $request = null,
        ?int $userId = null
    ): SecurityAuditLog {
        $request ??= request();

        return SecurityAuditLog::query()->create([
            'module' => $module,
            'event_type' => $eventType,
            'action' => $action,
            'auditable_type' => $auditable ? $auditable::class : null,
            'auditable_id' => $auditable?->getKey(),
            'user_id' => $userId ?? Auth::id(),
            'user_type' => Auth::check() ? 'admin' : 'guest',
            'ip_address' => $request?->ip(),
            'user_agent' => $request?->userAgent(),
            'old_values' => $this->mask($old),
            'new_values' => $this->mask($new),
            'metadata' => $this->mask($metadata),
            'status' => $status,
            'created_at' => now(),
        ]);
    }

    public function mask(array $values): array
    {
        $sensitive = collect(config('security.audit_sensitive_keys', []))->map(fn ($key) => strtolower((string) $key))->all();

        return collect($values)->mapWithKeys(function ($value, $key) use ($sensitive): array {
            $lower = strtolower((string) $key);
            if (in_array($lower, $sensitive, true) || str_contains($lower, 'password') || str_contains($lower, 'secret')) {
                return [$key => '[masked]'];
            }

            if (is_array($value)) {
                return [$key => $this->mask($value)];
            }

            if (is_string($value) && strlen($value) > 180) {
                return [$key => substr($value, 0, 180).'...'];
            }

            return [$key => $value];
        })->all();
    }
}
