<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreApplicationRequest;
use App\Http\Requests\UpdateApplicationRequest;
use App\Http\Resources\ApplicationResource;
use App\Services\ApplicationService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class ApplicationController extends Controller
{
    public function __construct(
        private readonly ApplicationService $service
    ) {}

    /**
     * GET /api/applications
     */
    public function index(Request $request): AnonymousResourceCollection
    {
        $filters = $request->only(['status', 'priority', 'search', 'sort']);

        $paginated = $this->service->list($request->user(), $filters);

        return ApplicationResource::collection($paginated);
    }

    /**
     * POST /api/applications
     */
    public function store(StoreApplicationRequest $request): JsonResponse
    {
        $application = $this->service->create($request->user(), $request->validated());

        return (new ApplicationResource($application))
            ->response()
            ->setStatusCode(201);
    }

    /**
     * GET /api/applications/stats
     * NOTE: This route must be defined BEFORE the {id} route in api.php.
     */
    public function stats(Request $request): JsonResponse
    {
        $stats = $this->service->stats($request->user());

        return response()->json($stats);
    }

    /**
     * GET /api/applications/{id}
     */
    public function show(Request $request, int $id): JsonResponse
    {
        $application = $this->service->findForUser($request->user(), $id);

        if (! $application) {
            return response()->json(['message' => 'Application not found.'], 404);
        }

        return (new ApplicationResource($application))->response();
    }

    /**
     * PATCH /api/applications/{id}
     */
    public function update(UpdateApplicationRequest $request, int $id): JsonResponse
    {
        $application = $this->service->findForUser($request->user(), $id);

        if (! $application) {
            return response()->json(['message' => 'Application not found.'], 404);
        }

        $updated = $this->service->update($application, $request->validated());

        return (new ApplicationResource($updated))->response();
    }

    /**
     * DELETE /api/applications/{id}
     */
    public function destroy(Request $request, int $id): JsonResponse
    {
        $application = $this->service->findForUser($request->user(), $id);

        if (! $application) {
            return response()->json(['message' => 'Application not found.'], 404);
        }

        $this->service->delete($application);

        return response()->json(['message' => 'Application deleted.']);
    }
}