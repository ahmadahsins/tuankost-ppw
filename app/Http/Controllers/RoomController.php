<?php

namespace App\Http\Controllers;

use App\Models\Room;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class RoomController extends Controller
{
    public function home(): View
    {
        $featuredRooms = Room::where('featured', true)
            ->orderBy('room_number')
            ->get();
        $latestRooms = Room::orderByDesc('id')->take(5)->get();

        return view('home', compact('featuredRooms', 'latestRooms'));
    }

    public function index(Request $request): View
    {
        $requestedType = $request->query('type', '');
        $requestedSearch = $request->query('search', '');
        $type = is_string($requestedType) ? $requestedType : '';
        $search = is_string($requestedSearch) ? trim($requestedSearch) : '';
        $types = Room::select('type')->distinct()->orderBy('type')->pluck('type');

        $rooms = Room::query()
            ->when($type !== '' && $types->contains($type), function ($query) use ($type) {
                $query->where('type', $type);
            })
            ->when($search !== '', function ($query) use ($search) {
                $query->where('room_number', 'like', '%'.$search.'%');
            })
            ->orderBy('room_number')
            ->get();

        $totalRooms = Room::count();
        $totalPrice = Room::sum('monthly_price');
        $highestPrice = Room::max('monthly_price');
        $lowestPrice = Room::min('monthly_price');

        return view('rooms.index', compact(
            'rooms',
            'types',
            'type',
            'search',
            'totalRooms',
            'totalPrice',
            'highestPrice',
            'lowestPrice',
        ));
    }

    public function show(string $room): View
    {
        $room = Room::where('slug', $room)->firstOrFail();

        return view('rooms.show', compact('room'));
    }

    public function create(): View
    {
        return view('rooms.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validatedData($request);
        $data['slug'] = Str::slug('kamar-'.$data['room_number']);

        Room::create($data);

        return redirect()->route('rooms.index')->with('success', 'Kamar berhasil ditambahkan.');
    }

    public function edit(Room $room): View
    {
        return view('rooms.edit', compact('room'));
    }

    public function update(Request $request, Room $room): RedirectResponse
    {
        $data = $this->validatedData($request, $room);
        $data['slug'] = Str::slug('kamar-'.$data['room_number']);

        $room->update($data);

        return redirect()->route('rooms.index')->with('success', 'Kamar berhasil diperbarui.');
    }

    public function destroy(Room $room): RedirectResponse
    {
        $room->delete();

        return redirect()->route('rooms.index')->with('success', 'Kamar berhasil dihapus.');
    }

    private function validatedData(Request $request, ?Room $room = null): array
    {
        if (is_string($request->input('room_number'))) {
            $request->merge(['room_number' => strtoupper(trim($request->input('room_number')))]);
        }

        $data = $request->validate([
            'room_number' => [
                'required', 'string', 'max:20', 'regex:/^[A-Za-z0-9]+$/',
                Rule::unique('rooms', 'room_number')->ignore($room?->id),
            ],
            'type' => ['required', Rule::in(['Standard', 'Deluxe'])],
            'monthly_price' => ['required', 'numeric', 'min:0', 'max:9999999999.99'],
            'floor' => ['required', 'integer', 'between:1,255'],
            'capacity' => ['required', 'integer', 'between:1,255'],
            'status' => ['required', Rule::in(['Tersedia', 'Ditempati'])],
            'facilities' => ['required', 'string', 'max:1000'],
            'description' => ['required', 'string', 'max:5000'],
            'featured' => ['nullable', 'boolean'],
        ]);

        $data['facilities'] = array_values(array_filter(array_map(
            trim(...),
            explode(',', $data['facilities']),
        ), fn ($facility) => $facility !== ''));
        $data['featured'] = $request->boolean('featured');

        return $data;
    }
}
