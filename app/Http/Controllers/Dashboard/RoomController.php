<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Room;
use Illuminate\Http\Request;

class RoomController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $query = Room::query();

        if (request()->has('type') && request('type') !== null) {
            $query->byType(request('type'));
        }

        if (request()->has('status') && request('status') !== null) {
            $query->byStatus(request('status'));
        }

        $rooms = $query->with('clinic')->latest()->paginate(10);
        // dd($rooms);
        return view('dashboard.rooms.index', compact('rooms'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $room = new Room();
        return view('dashboard.rooms.create', compact('room'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        // $request->validate(Room::rules());

        // dd($request->all());
        Room::create($request->all());

        return redirect()->route('dashboard.rooms.index')->with('success', 'Room added successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id) {}

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Room $room)
    {
        return view('dashboard.rooms.edit', compact('room'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Room $room)
    {
        // $request->validate(Room::rules());
        $room->update($request->all());
        return redirect()->route('dashboard.rooms.index')->with('success', 'Room updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Room $room)
    {
        $room->delete();
        return redirect()->route('dashboard.rooms.index')->with('success', 'Room deleted successfully.');
    }
}
