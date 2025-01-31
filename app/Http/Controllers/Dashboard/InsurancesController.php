<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Insurance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;

class InsurancesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $insurances = Insurance::filter(request()->all())->paginate(10);
        return view('dashboard.insurances.index', compact('insurances'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $insurance = new Insurance();
        // dd($insurance);
        return view('dashboard.insurances.create', compact('insurance'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // dd($request->all());
        $request->validate(Insurance::rules()); 
        $data = $request->except('logo');
        $data['logo'] = Insurance::uploadImage($request);
        $insurance = Insurance::create($data);
        return Redirect::route('dashboard.insurances.index')->with('success', 'Insurance add');
    }

    /**
     * Display the specified resource.
     */
    public function show(Insurance $insurance)
    {
        // dd($insurance);
        return view('dashboard.insurances.show',compact('insurance'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Insurance $insurance)
    {
        return view('dashboard.insurances.edit', compact('insurance'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $insurance = Insurance::findOrFail($id);
        $old_image = $insurance->logo;
        $data = $request->except('logo');
        $new_image = Insurance::uploadImage($request);
        if ($new_image) {
            $data['logo'] = $new_image;
        }
        $insurance->update($data);
        if ($old_image && $new_image) {
            Storage::disk('public')->delete($old_image);
        }


        return Redirect::route('dashboard.insurances.index')
            ->with('success', 'Insurance updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $insurance = Insurance::findOrFail($id);
        $old_image = $insurance->logo;
        $insurance->delete();
        if ($old_image) {
            Storage::disk('public')->delete($old_image);
        }
        return Redirect::route('dashboard.insurances.index')
            ->with('success', 'insurance deleted forever successfully');
    }
}
