<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateTaskStatusRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; 
    }

    public function rules(): array
    {
        return [
            'status' => 'required|string|in:new,in_progress,completed,cancelled',
            'user_id' => 'nullable|integer|exists:users,id',
        ];
    }
}
