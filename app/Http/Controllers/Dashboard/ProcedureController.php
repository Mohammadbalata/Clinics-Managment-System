<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Procedure;
use Illuminate\Http\Request;

class ProcedureController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {

        $sortBy = $request->query('sort_by', 'id');
        $sortDir = $request->query('sort_dir', 'asc');

        $allowedSorts = ['name', 'duration', 'coast'];

        if (!in_array($sortBy, $allowedSorts)) {
            $sortBy = 'id'; // default to id if invalid
        }

        $procedures = Procedure::with(['room','doctor'])
            ->orderBy($sortBy, $sortDir)
            ->latest()
            ->paginate();

        return view('dashboard.procedures.index', compact(['procedures', 'sortBy']));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $procedure = new Procedure();
        return view('dashboard.procedures.create', compact('procedure'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // dd($request->all());
        // $request->validate(Procedure::rules());
        Procedure::create($request->all());
        return redirect()->route('dashboard.procedures.index')->with('success', 'Procedure added successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id) {}

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Procedure $procedure)
    {
        return view('dashboard.procedures.edit', compact('procedure'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Procedure $procedure)
    {
        // $request->validate(Procedure::rules());
        $procedure->update($request->all());
        return redirect()->route('dashboard.procedures.index')->with('success', 'Procedure updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Procedure $procedure)
    {
        $procedure->delete();
        return redirect()->route('dashboard.procedures.index')->with('success', 'Procedure deleted successfully.');
    }
}
