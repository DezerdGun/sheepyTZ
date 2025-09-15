<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TaskIndexRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; 
    }

    public function rules(): array
    {
        return [
            'status'   => 'sometimes|string|in:new,in_progress,completed,cancelled',
            'priority' => 'sometimes|string|in:high,normal,low',
            'user_id'  => 'sometimes|integer|exists:users,id',
        ];
    }
}
