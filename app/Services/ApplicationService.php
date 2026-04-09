<?php

namespace App\Services;

use App\Models\Application;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

class ApplicationService
{
    /**
     * Return a filtered, sorted, paginated list of applications for the user.
     */
    public function list(User $user, array $filters): LengthAwarePaginator
    {
        return Application::query()
            ->forUser($user->id)
            ->byStatus($filters['status'] ?? null)
            ->byPriority($filters['priority'] ?? null)
            ->search($filters['search'] ?? null)
            ->sortBy($filters['sort'] ?? null)
            ->paginate(15)
            ->withQueryString();
    }

    /**
     * Create a new application for the user.
     */
    public function create(User $user, array $data): Application
    {
        return $user->applications()->create($data);
    }

    /**
     * Find an application that belongs to the user, or return null.
     */
    public function findForUser(User $user, int $id): ?Application
    {
        return Application::query()
            ->forUser($user->id)
            ->find($id);
    }

    /**
     * Update an existing application.
     */
    public function update(Application $application, array $data): Application
    {
        $application->update($data);

        return $application->fresh();
    }

    /**
     * Delete an application.
     */
    public function delete(Application $application): void
    {
        $application->delete();
    }

    /**
     * Calculate dashboard statistics for a user.
     */
    public function stats(User $user): array
    {
        $applications = Application::query()
            ->forUser($user->id)
            ->get();

        $total = $applications->count();

        $byStatus = collect(Application::STATUSES)
            ->mapWithKeys(fn ($s) => [$s => $applications->where('status', $s)->count()])
            ->toArray();

        $byPriority = collect(Application::PRIORITIES)
            ->mapWithKeys(fn ($p) => [$p => $applications->where('priority', $p)->count()])
            ->toArray();

        $thisMonth = $applications->filter(
            fn ($a) => $a->created_at->isCurrentMonth()
        )->count();

        $interviewCount = $byStatus['interview'] + $byStatus['offer'];
        $interviewRate  = $total > 0
            ? round(($interviewCount / $total) * 100) . '%'
            : '0%';

        return [
            'total'          => $total,
            'by_status'      => $byStatus,
            'by_priority'    => $byPriority,
            'this_month'     => $thisMonth,
            'interview_rate' => $interviewRate,
        ];
    }
}