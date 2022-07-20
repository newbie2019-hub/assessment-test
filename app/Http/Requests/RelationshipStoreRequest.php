<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class RelationshipStoreRequest extends FormRequest
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
            'user_id' => ['required'],
            'related_user_id' => ['required'],
            'relationship_id' => ['required'],
        ];
    }

    public function messages()
    {
        return [
            'user_id.required' => 'A user is required to link with',
            'relationship_id.required' => 'A relationship is required for linking users',
        ];
    }
}
