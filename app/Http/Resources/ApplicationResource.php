<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ApplicationResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'             => $this->id,
            'company_name'   => $this->company_name,
            'job_title'      => $this->job_title,
            'job_url'        => $this->job_url,
            'status'         => $this->status,
            'priority'       => $this->priority,
            'applied_date'   => $this->applied_date?->toDateString(),
            'follow_up_date' => $this->follow_up_date?->toDateString(),
            'salary_min'     => $this->salary_min,
            'salary_max'     => $this->salary_max,
            'location'       => $this->location,
            'notes'          => $this->notes,
            'created_at'     => $this->created_at->toDateTimeString(),
            'updated_at'     => $this->updated_at->toDateTimeString(),
        ];
    }
}