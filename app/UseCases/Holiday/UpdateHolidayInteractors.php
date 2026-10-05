<?php

namespace App\UseCases\Holiday;


use App\Models\Holiday;
use App\UseCases\Holiday\Request\HolidayRequest;

class UpdateHolidayInteractors
{
    public function execute(HolidayRequest $holidayRequest, Holiday $holiday): Holiday
    {

        $holiday->update($holidayRequest->validated());

        return $holiday->refresh();
    }
}
