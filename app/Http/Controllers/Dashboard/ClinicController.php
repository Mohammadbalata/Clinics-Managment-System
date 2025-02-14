<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\ClinicRequest;
use App\Models\BusinessHour;
use App\Models\Clinic;
use App\Services\ClinicService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

class ClinicController extends Controller
{
    private ClinicService $clinicService;

    public function __construct(ClinicService $clinicService)
    {
        $this->clinicService = $clinicService;
    }
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
    public function store(ClinicRequest $request)
    {
        $clinic = Clinic::create($request->all());
        $this->clinicService->syncBusinessHours($clinic, $request->business_hours);
        return Redirect::route('dashboard.clinics.index')->with('success', 'Clinic added successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Clinic $clinic)
    {
        $business_hours = $this->clinicService->getBusinessHoursForClinic($clinic);
        $insurances = $clinic->insurances;
        return view('dashboard.clinics.show', compact('clinic', 'business_hours', 'insurances'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Clinic $clinic)
    {
        $business_hours = $this->clinicService->getBusinessHoursForClinic($clinic);
        return view('dashboard.clinics.edit', compact('clinic', 'business_hours'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ClinicRequest $request, Clinic $clinic)
    {
        $clinic->update($request->all());
        $this->clinicService->syncBusinessHours($clinic, $request->business_hours);
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
}
