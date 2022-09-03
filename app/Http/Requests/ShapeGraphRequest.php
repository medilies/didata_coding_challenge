<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ShapeGraphRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'deletable_nodes_ids' => ['required', 'array'],
            'deletable_nodes_ids.*' => ['nullable', 'integer'],
            'adjacency_list' => ['required', 'array'],
            'adjacency_list.*' => ['nullable', 'array'],
            'adjacency_list.*.*' => ['nullable', 'integer'],
        ];
    }
}
