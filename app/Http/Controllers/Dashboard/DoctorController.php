<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\DoctorRequest;
use App\Models\Clinic;
use App\Models\Doctor;
use App\Models\Procedure;
use Illuminate\Http\Request;

class DoctorController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $doctors = Doctor::filter(request()->all())->with('clinic')->paginate(10);
        $procedures = Procedure::all();
        return view('dashboard.doctors.index', compact('doctors','procedures'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $doctor = new Doctor();
        $clinics = Clinic::all();
        return view('dashboard.doctors.create', compact('doctor','clinics'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(DoctorRequest $request)
    {
        // dd($request->all());
        // $request->validate(Doctor::rules());
        $doctor = Doctor::create($request->all());
        return redirect()->route('dashboard.doctors.index')->with('success', 'Doctor added successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $doctor = Doctor::with('clinic')->findOrFail($id);
        // dd($doctor);
        return view('dashboard.doctors.show', compact('doctor'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Doctor $doctor)
    {
        $clinics = Clinic::all();
        return view('dashboard.doctors.edit', compact('doctor','clinics'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(DoctorRequest $request, Doctor $doctor)
    {
       
        // $request->validate(Doctor::rules($doctor->id));
        $doctor->update($request->all());
        return redirect()->route('dashboard.doctors.index')->with('success', 'Doctor updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Doctor $doctor)
    {
        $doctor->delete();
        return redirect()->route('dashboard.doctors.index')->with('success', 'Doctor deleted successfully.');
    }
}
