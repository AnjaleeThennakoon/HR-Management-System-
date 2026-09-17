<?php


use App\Models\Designation;
use App\UseCases\Designation\Request\DesignationRequest;

class CreateDesignationInteractor
{
    public function execute(DesignationRequest $request): Designation
    {
        return Designation::create([
            'name' => $request->name,
        ]);
    }
}
