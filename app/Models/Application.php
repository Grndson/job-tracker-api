<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Application extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'company_name',
        'job_title',
        'job_url',
        'status',
        'priority',
        'applied_date',
        'follow_up_date',
        'salary_min',
        'salary_max',
        'location',
        'notes',
    ];

    protected $casts = [
        'applied_date'   => 'date',
        'follow_up_date' => 'date',
        'salary_min'     => 'integer',
        'salary_max'     => 'integer',
    ];

    public const STATUSES = ['wishlist', 'applied', 'interview', 'offer', 'rejected'];
    public const PRIORITIES = ['low', 'medium', 'high'];

    // ─── Relationships ────────────────────────────────────────────────

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // ─── Scopes ───────────────────────────────────────────────────────

    public function scopeForUser(Builder $query, int $userId): Builder
    {
        return $query->where('user_id', $userId);
    }

    public function scopeByStatus(Builder $query, ?string $status): Builder
    {
        if (! $status || ! in_array($status, self::STATUSES)) {
            return $query;
        }

        return $query->where('status', $status);
    }

    public function scopeByPriority(Builder $query, ?string $priority): Builder
    {
        if (! $priority || ! in_array($priority, self::PRIORITIES)) {
            return $query;
        }

        return $query->where('priority', $priority);
    }

    public function scopeSearch(Builder $query, ?string $search): Builder
    {
        if (! $search) {
            return $query;
        }

        return $query->where(function (Builder $q) use ($search) {
            $q->where('company_name', 'like', "%{$search}%")
              ->orWhere('job_title', 'like', "%{$search}%");
        });
    }

    public function scopeSortBy(Builder $query, ?string $sort): Builder
    {
        $allowed = ['applied_date', 'follow_up_date', 'company_name', 'created_at'];

        if (! $sort || ! in_array($sort, $allowed)) {
            return $query->orderBy('created_at', 'desc');
        }

        return $query->orderBy($sort, 'asc');
    }
}