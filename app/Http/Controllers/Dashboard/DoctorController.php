<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Clinic;
use App\Models\Doctor;
use Illuminate\Http\Request;

class DoctorController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $doctors = Doctor::filter(request()->all())->with('clinic')->paginate(10);
        return view('dashboard.doctors.index', compact('doctors'));
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
    public function store(Request $request)
    {
        // dd($request->all());
        $request->validate(Doctor::rules());
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
        return view('dashboard.doctors.edit', compact('doctor'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Doctor $doctor)
    {
       
        $request->validate(Doctor::rules($doctor->id));
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
