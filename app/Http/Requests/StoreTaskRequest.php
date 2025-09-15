<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreTaskRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => 'required|string|min:5|max:100',
            'description' => 'nullable|string',
            'user_id' => 'nullable|integer|exists:users,id',
            'priority' => 'nullable|string|in:high,normal,low',
        ];
    }
}
