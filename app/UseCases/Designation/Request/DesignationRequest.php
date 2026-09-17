<?php

namespace App\UseCases\Designation\Request;

use Dflydev\DotAccessData\Data;
use Illuminate\Validation\Rule;

class DesignationRequest extends Data
{
    public ?string $id = null;

    public string $name;

    public static function rules(): array
    {
        return [
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('designations', 'name')
                    ->ignore(request()->input('id')),
            ],
        ];
    }
}
