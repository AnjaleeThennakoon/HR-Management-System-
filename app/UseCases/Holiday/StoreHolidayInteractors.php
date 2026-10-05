<?php

namespace App\UseCases\Holiday;

use App\Models\Leave;
use App\UseCases\Holiday\Request\HolidayRequest;

class StoreHolidayInteractors
{
    public function execute(HolidayRequest $holidayRequest)
    {
        $holiday = $holidayRequest->validated();

        return Leave::create($holiday);
    }
}
