<?php

namespace App\UseCases\Holiday\Request;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class HolidayRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $id = $this->route('holiday') ?? $this->route('id');

        return [
            'name' => ['required', 'string', 'max:255', 'unique:holidays,name,'.$id],
            'date' => ['required', 'date'],
            'type' => ['required', 'string', Rule::in(['public', 'special'])],
        ];
    }
}
