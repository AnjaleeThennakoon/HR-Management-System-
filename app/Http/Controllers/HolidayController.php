<?php

namespace App\Http\Controllers;

use App\Models\Holiday;
use App\UseCases\Holiday\DeleteHolidayInteractors;
use App\UseCases\Holiday\ListHolidayInteractors;
use App\UseCases\Holiday\Request\HolidayRequest;
use App\UseCases\Holiday\StoreHolidayInteractors;
use App\UseCases\Holiday\UpdateHolidayInteractors;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;

class HolidayController extends Controller
{

    public function index(ListHolidayInteractors $listHolidayInteractor): View
    {
        $holidays = $listHolidayInteractor->execute(
            request('search'),
            request('per_page')
        );
        return view('Holiday.HolidayDashBord', compact('holidays'));
    }

    public function store(HolidayRequest $holidayRequest, StoreHolidayInteractors $storeHolidayInteractor):RedirectResponse
    {
        $storeHolidayInteractor->execute($holidayRequest);

        return redirect()->route('holidays.index')
            ->with('success', 'Holiday has been successfully created.');
    }

    public function update(HolidayRequest $holidayRequest, UpdateHolidayInteractors $updateHolidayInteractors, string $id): RedirectResponse
    {
        $updateHolidayInteractors->execute(
            $id,
            $holidayRequest->validated()
        );
        return redirect()
            ->route('holidays.index')
            ->with('success', 'Holiday has been successfully updated.');
    }

    public function destroy(string $id, DeleteHolidayInteractors $deleteHolidayInteractors): RedirectResponse
    {
        $deleteHolidayInteractors->execute($id);
        return redirect()->route('holidays.index')
            ->with('success', 'Holiday has been successfully deleted.');
    }
}
