<?php

namespace App\UseCases\Designation\Request;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class DesignationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('designations', 'name')
                    ->ignore($this->route('id')),
            ],

        ];
    }
}
