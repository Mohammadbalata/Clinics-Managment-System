<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\BusinessHour;
use App\Models\Clinic;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

class ClinicController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $clinics = Clinic::filter(request()->all())->paginate(10);
        return view('dashboard.clinics.index', compact('clinics'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $clinic = new Clinic();
        return view('dashboard.clinics.create', compact('clinic'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate(Clinic::rules());

        $clinic = Clinic::create($request->all());
        $this->syncBusinessHours($clinic, $request->business_hours);

        return Redirect::route('dashboard.clinics.index')->with('success', 'Clinic added successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Clinic $clinic)
    {
        $business_hours = $this->getBusinessHoursForClinic($clinic);
        // dd($business_hours);
        return view('dashboard.clinics.show', compact('clinic', 'business_hours'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Clinic $clinic)
    {
        $business_hours = $this->getBusinessHoursForClinic($clinic);
        return view('dashboard.clinics.edit', compact('clinic', 'business_hours'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Clinic $clinic)
    {
        $request->validate(Clinic::rules());

        $clinic->update($request->all());
        $this->syncBusinessHours($clinic, $request->business_hours);

        return Redirect::route('dashboard.clinics.index')
            ->with('success', 'Clinic updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Clinic $clinic)
    {
        $clinic->delete();
        return Redirect::route('dashboard.clinics.index')
            ->with('success', 'Clinic deleted successfully.');
    }

    /**
     * Sync business hours for a clinic.
     */
    private function syncBusinessHours(Clinic $clinic, array $businessHours): void
    {
        foreach ($businessHours as $day => $hour) {
            $clinic->businessHours()->updateOrCreate(
                ['day' => $day],
                [
                    'open_time' => $hour['open_time'],
                    'close_time' => $hour['close_time'],
                    'lunch_start' => $hour['lunch_start'] ?? null,
                    'lunch_end' => $hour['lunch_end'] ?? null,
                ]
            );
        }
    }

    /**
     * Get business hours for a clinic grouped by day.
     */
    private function getBusinessHoursForClinic(Clinic $clinic): array
    {
        return $clinic->businessHours
        ->groupBy('day')
        ->map(function ($hours) {
            return [
                'open_time' => substr($hours[0]->open_time, 0, -3),
                'close_time' => substr($hours[0]->close_time, 0, -3),
                'lunch_start' => $hours[0]->lunch_start ? substr($hours[0]->lunch_start, 0, -3) : null,
                'lunch_end' => $hours[0]->lunch_end ? substr($hours[0]->lunch_end, 0, -3) : null,
            ];
        })
        ->toArray();
    }
}
