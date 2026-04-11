<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\TeamEvent;

class EventController extends Controller
{
    public function index(Request $request)
    {
        $query = TeamEvent::query();

        if ($request->search) {
            $query->where(function($q) use ($request) {
                $q->where('nazwa', 'LIKE', '%' . $request->search . '%')
                    ->orWhere('miejsce', 'LIKE', '%' . $request->search . '%');
            });
        }

        if ($request->status) {
            $query->where('status', $request->status);
        }

        if ($request->data_od) {
            $query->whereDate('data_i_czas_start', '>=', $request->data_od);
        }

        if ($request->data_do) {
            $query->whereDate('data_i_czas_start', '<=', $request->data_do);
        }

        $events = $query->orderBy('data_i_czas_start', 'desc')->paginate(10);

        // Jeśli żądanie AJAX — zwróć JSON
        if ($request->ajax()) {
            return response()->json([
                'events' => $events->map(function($event) {
                    return [
                        'idEvent'           => $event->idEvent,
                        'nazwa'             => $event->nazwa,
                        'miejsce'           => $event->miejsce,
                        'status'            => $event->status,
                        'data_i_czas_start' => $event->data_i_czas_start->format('d.m.Y H:i'),
                    ];
                }),
                'total' => $events->total(),
            ]);
        }

        return view('events.index', compact('events'));
    }

    public function show($id)
    {
        $event    = TeamEvent::findOrFail($id);
        $rsvp     = DB::table('event_rsvp')
            ->where('idEvent', $id)
            ->where('idUser', auth()->id())
            ->first();
        $statuses = DB::table('rsvp_statuses')->get();

        return view('events.show', compact('event', 'rsvp', 'statuses'));
    }
}
