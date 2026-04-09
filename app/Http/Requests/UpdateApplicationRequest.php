<?php

namespace App\Http\Requests;

use App\Models\Application;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateApplicationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'company_name'   => ['sometimes', 'required', 'string', 'max:255'],
            'job_title'      => ['sometimes', 'required', 'string', 'max:255'],
            'job_url'        => ['nullable', 'url', 'max:2048'],
            'status'         => ['sometimes', Rule::in(Application::STATUSES)],
            'priority'       => ['sometimes', Rule::in(Application::PRIORITIES)],
            'applied_date'   => ['nullable', 'date'],
            'follow_up_date' => ['nullable', 'date', 'after_or_equal:today'],
            'salary_min'     => ['nullable', 'integer', 'min:0'],
            'salary_max'     => ['nullable', 'integer', 'min:0', 'gte:salary_min'],
            'location'       => ['nullable', 'string', 'max:255'],
            'notes'          => ['nullable', 'string'],
        ];
    }

    public function messages(): array
    {
        return [
            'follow_up_date.after_or_equal' => 'The follow-up date must be today or a future date.',
            'salary_max.gte'                => 'The maximum salary must be greater than or equal to the minimum salary.',
        ];
    }
}