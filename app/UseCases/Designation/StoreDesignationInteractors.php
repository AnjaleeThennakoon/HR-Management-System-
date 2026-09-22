<?php

namespace App\UseCases\Designation;

use App\Models\Designation;
use App\UseCases\Designation\Request\DesignationRequest;

class StoreDesignationInteractors
{
    private const LEVEL_MAP = [
        'Chief Executive Officer'      => 1,
        'Chief Technology Officer'     => 2,
        'General Manager'              => 3,
        'Human Resources Manager'      => 4,
        'Finance Manager'              => 5,
        'Project Manager'              => 6,
        'Senior Software Engineer'     => 7,
        'Software Engineer'            => 8,
        'Junior Software Engineer'     => 9,
        'Software Engineering Intern'  => 10,
    ];

    public function execute(DesignationRequest $designationRequest): Designation
    {
        $designation = $designationRequest->validated();

        if(empty($designation['level']) && isset(self::LEVEL_MAP[$designation['name']])) {
            $designation['level']=self::LEVEL_MAP[$designation['name']];
        }

        return Designation::create($designation);
    }
}
