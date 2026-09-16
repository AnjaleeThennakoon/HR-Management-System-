<?php

namespace App\UseCases\Department\Requests;
use Dflydev\DotAccessData\Data;
use Illuminate\Validation\Rule;

class DepartmentRequest extends Data
{
    public ?string $id;

    public ?string $department;

    public static function rules(): array
    {
        return [
            'department' => [
                'required',
                'string',
                'max:255',
                Rule::unique('departments', 'department')->ignore(request()->input('id')),
            ],
        ];
    }

}
