<?php
namespace App\UseCases\Holiday;
use App\Models\Holiday;
use App\UseCases\Holiday\Request\HolidayRequest;

class StoreHolidayInteractors
{
    public function execute(HolidayRequest $holidayRequest)
    {
        $holiday = $holidayRequest->validated();

        return Holiday::create($holiday);
    }
}
