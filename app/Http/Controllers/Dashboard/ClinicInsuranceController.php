<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Clinic;
use App\Models\Insurance;
use Illuminate\Http\Request;

class ClinicInsuranceController extends Controller
{

    /**
     * Show the form for creating a new resource.
     */
    public function create(Clinic $clinic)
    {
        $selectedInsuranceIds = $clinic->insurances()->pluck('insurances.id');
        $insurances = Insurance::whereNotIn('id', $selectedInsuranceIds)->get();

        return view('dashboard.clinics-insurances.create', [
            'insurances' => $insurances,
            'clinicId' => $clinic->id
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, Clinic $clinic)
    {
        // dd($request->all()['insurances']);
        $request->validate([
            'insurances' => ['required', 'array', 'min:1'], 
            'insurances.*' => ['exists:insurances,id'], 
        ], [
            'insurances.required' => 'You must select at least one insurance.',
        ]);
        $clinic->insurances()->attach($request->all()['insurances']);
        return redirect()->route('dashboard.clinics.show', $clinic->id)->with('success', 'Insurance(s) assigned successfully');
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy($clinicId,$insuranceId)
    {
        $clinic = Clinic::find($clinicId);
        $clinic->insurances()->detach($insuranceId);
        return redirect()->route('dashboard.clinics.show', $clinic->id)->with('success', 'Insurance removed successfully');
       
    }
}
