<?php

namespace App\Http\Controllers;

use App\Models\Holiday;
use App\UseCases\Holiday\DeleteHolidayInteractor;
use App\UseCases\Holiday\ListHolidayInteractor;
use App\UseCases\Holiday\Request\HolidayRequest;
use App\UseCases\Holiday\StoreHolidayInteractor;
use App\UseCases\Holiday\UpdateHolidayInteractor;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;

class HolidayController extends Controller
{
    public function index(ListHolidayInteractor $listHolidayInteractor): View
    {
        $holidays = $listHolidayInteractor->execute(
            request('search'),
            request('per_page')
        );

        return view('Holiday.HolidayDashBord', compact('holidays'));
    }

    public function store(HolidayRequest $holidayRequest, StoreHolidayInteractor $storeHolidayInteractor): RedirectResponse
    {
        $storeHolidayInteractor->execute($holidayRequest);

        return redirect()->route('holidays.index')
            ->with('success', 'Holiday has been successfully created.');
    }

    public function update(string $id, HolidayRequest $holidayRequest, UpdateHolidayInteractor $updateHolidayInteractor): RedirectResponse
    {
        $holiday = Holiday::findOrFail($id);

        $updateHolidayInteractor->execute($holidayRequest, $holiday);

        return redirect()
            ->route('holidays.index')
            ->with('success', 'Holiday has been successfully updated.');
    }

    public function destroy(string $id, DeleteHolidayInteractor $deleteHolidayInteractor): RedirectResponse
    {
        $deleteHolidayInteractor->execute($id);

        return redirect()->route('holidays.index')
            ->with('success', 'Holiday has been successfully deleted.');
    }
}
