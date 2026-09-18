<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class RoomController extends Controller
{
    public function home(): View
    {
        $featuredRooms = DB::table('rooms')->where('featured', true)
            ->orderBy('room_number')->get();

        foreach ($featuredRooms as $room) {
            $room->facilities = json_decode($room->facilities, true, 512, JSON_THROW_ON_ERROR);
        }

        return view('home', compact('featuredRooms'));
    }

    public function index(): View
    {
        $rooms = DB::table('rooms')->orderBy('room_number')->get();

        foreach ($rooms as $room) {
            $room->facilities = json_decode($room->facilities, true, 512, JSON_THROW_ON_ERROR);
        }

        return view('rooms.index', compact('rooms'));
    }

    public function show(string $room): View
    {
        $room = DB::table('rooms')->where('slug', $room)->first();

        abort_if($room === null, 404);

        $room->facilities = json_decode($room->facilities, true, 512, JSON_THROW_ON_ERROR);

        return view('rooms.show', compact('room'));
    }
}
