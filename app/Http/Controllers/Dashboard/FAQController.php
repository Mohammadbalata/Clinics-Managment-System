<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Clinic;
use App\Models\FAQ;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

class FAQController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Clinic $clinic)
    {
        return view('dashboard.faq.index', [
            'faqs' => $clinic->faqs,
            'clinic' => $clinic
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Clinic $clinic)
    {
        return view('dashboard.faq.create', [
            'faq' => new FAQ(),
            'clinicId' => $clinic->id
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, Clinic $clinic)
    {
        $request->validate(FAQ::rules());
        $clinic->faqs()->create($request->only('question', 'answer'));
        return redirect()->route('dashboard.faq.index', $clinic)
            ->with('success', 'FAQ created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Clinic $clinic, FAQ $faq)
    {
        return view('dashboard.faq.edit', compact('faq'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Clinic $clinic, FAQ $faq)
    {
        $request->validate(FAQ::rules());
        $faq->update($request->only('question', 'answer'));
        return redirect()->route('dashboard.faq.index', $clinic)
            ->with('success', 'FAQ updated successfully.');

    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Clinic $clinic, FAQ $faq)
    {
        $faq->delete();
        return redirect()->route('dashboard.faq.index', $clinic)
            ->with('success', 'FAQ deleted successfully.');
    }
}
