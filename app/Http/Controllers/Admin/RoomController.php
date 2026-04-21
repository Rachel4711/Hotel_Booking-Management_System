<?php

namespace App\Http\Controllers\Admin;

use App\Models\Hotel;
use App\Models\Room;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class RoomController extends Controller
{
    /**
     * Display a listing of rooms for a hotel.
     */
    public function index($hotelId)
    {
        $hotel = Hotel::findOrFail($hotelId);
        $rooms = $hotel->rooms()->paginate(10);

        return view('Admin.Room.index', compact('hotel', 'rooms'));
    }

    /**
     * Show the form for creating a new room.
     */
    public function create($hotelId)
    {
        $hotel = Hotel::findOrFail($hotelId);
        return view('Admin.Room.create', compact('hotel'));
    }

    /**
     * Store a newly created room in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'hotel_id' => 'required|exists:hotels,id',
            'name' => 'required|string|max:255',
            'type' => 'required|in:single,double,queen,king',
            'capacity' => 'required|integer|min:1',
            'price' => 'required|numeric|min:0',
            'total_rooms' => 'required|integer|min:1',
        ]);

        Room::create($validated);

        return redirect()
            ->route('hotel.rooms.index', $validated['hotel_id'])
            ->with('success', 'Room created successfully.');
    }

    /**
     * Display the specified room.
     */
    public function show(Room $room)
    {
        return view('Admin.Room.show', compact('room'));
    }

    /**
     * Show the form for editing the specified room.
     */
    public function edit(Room $room)
    {
        return view('Admin.Room.edit', compact('room'));
    }

    /**
     * Update the specified room in storage.
     */
    public function update(Request $request, Room $room)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:single,double,queen,king',
            'capacity' => 'required|integer|min:1',
            'price' => 'required|numeric|min:0',
            'total_rooms' => 'required|integer|min:1',
        ]);

        $room->update($validated);

        return redirect()
            ->route('hotel.rooms.index', $room->hotel_id)
            ->with('success', 'Room updated successfully.');
    }

    /**
     * Remove the specified room from storage.
     */
    public function destroy(Room $room)
    {
        $hotelId = $room->hotel_id;
        $room->delete();

        return redirect()
            ->route('hotel.rooms.index', $hotelId)
            ->with('success', 'Room deleted successfully.');
    }
}

