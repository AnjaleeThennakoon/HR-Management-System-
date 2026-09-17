<?php


use App\Models\Designation;
use App\UseCases\Designation\Request\DesignationRequest;

class UpdateDesignationInteractor
{
    public function execute(
        DesignationRequest $request,
        string $id
    ): Designation {
        $designation = Designation::findOrFail($id);

        $designation->update([
            'name' => $request->name,
        ]);

        return $designation->refresh();
    }
}
