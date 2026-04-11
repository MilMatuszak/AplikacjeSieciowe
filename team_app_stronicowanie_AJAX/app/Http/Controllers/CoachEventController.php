<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TeamEvent;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;


class CoachEventController extends Controller
{
    // LISTA TRENINGÓW TRENERA
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

        return view('coach.events.index', compact('events'));
    }

    // FORMULARZ NOWEGO TRENINGU
    public function create()
    {
        $teams      = DB::table('teams')->get();
        $eventTypes = DB::table('event_types')->get();
        return view('coach.events.create', compact('teams', 'eventTypes'));
    }

    // ZAPIS NOWEGO TRENINGU
    public function store(Request $request)
    {
        $request->validate([
            'nazwa'              => 'required|string|max:150',
            'data_i_czas_start'  => 'required|date|after:now',
            'data_i_czas_koniec' => 'required|date|after:data_i_czas_start',
            'miejsce'            => 'nullable|string|max:200',
            'opis'               => 'nullable|string',
            'idTeam'             => 'nullable|exists:teams,idTeam',
            'idTyp'              => 'nullable|exists:event_types,idTyp',
        ], [
            'data_i_czas_start.after'  => 'Data rozpoczęcia nie może być w przeszłości.',
            'data_i_czas_koniec.after' => 'Data zakończenia musi być późniejsza niż rozpoczęcia.',
        ]);

        TeamEvent::create([
            'nazwa'              => $request->nazwa,
            'opis'               => $request->opis,
            'data_i_czas_start'  => $request->data_i_czas_start,
            'data_i_czas_koniec' => $request->data_i_czas_koniec,
            'miejsce'            => $request->miejsce,
            'status'             => 'zaplanowane',
            'idTeam'             => $request->idTeam,
            'idTyp'              => $request->idTyp,
            'kto_utworzyl_id'    => Auth::id(),
        ]);

        return redirect('/coach/events')
            ->with('success', 'Trening został dodany!');
    }


    // FORMULARZ EDYCJI
    public function edit($id)
    {
        $event      = TeamEvent::findOrFail($id);
        $teams      = DB::table('teams')->get();
        $eventTypes = DB::table('event_types')->get();

        if ($event->kto_utworzyl_id !== Auth::id()) {
            return redirect('/coach/events')
                ->with('error', 'Nie masz uprawnień do edycji tego treningu.');
        }

        return view('coach.events.edit', compact('event', 'teams', 'eventTypes'));
    }

// AKTUALIZACJA TRENINGU
    public function update(Request $request, $id)
    {
        $event = TeamEvent::findOrFail($id);

        if ($event->kto_utworzyl_id !== Auth::id()) {
            return redirect('/coach/events')
                ->with('error', 'Nie masz uprawnień.');
        }

        $request->validate([
            'nazwa'              => 'required|string|max:150',
            'data_i_czas_start'  => 'required|date',
            'data_i_czas_koniec' => 'required|date|after:data_i_czas_start',
            'miejsce'            => 'nullable|string|max:200',
            'opis'               => 'nullable|string',
            'status'             => 'required|in:zaplanowane,trwa,zakonczone,odwolane',
            'idTeam'             => 'nullable|exists:teams,idTeam',
            'idTyp'              => 'nullable|exists:event_types,idTyp',
        ]);

        $event->update([
            'nazwa'               => $request->nazwa,
            'opis'                => $request->opis,
            'data_i_czas_start'   => $request->data_i_czas_start,
            'data_i_czas_koniec'  => $request->data_i_czas_koniec,
            'miejsce'             => $request->miejsce,
            'status'              => $request->status,
            'idTeam'              => $request->idTeam,
            'idTyp'               => $request->idTyp,
            'kto_aktualizowal_id' => Auth::id(),
        ]);

        return redirect('/coach/events')
            ->with('success', 'Trening został zaktualizowany!');
    }

    // USUNIĘCIE TRENINGU
    public function destroy($id)
    {
        $event = TeamEvent::findOrFail($id);

        if ($event->kto_utworzyl_id !== Auth::id()) {
            return redirect('/coach/events')
                ->with('error', 'Nie masz uprawnień.');
        }

        $event->delete();

        return redirect('/coach/events')
            ->with('success', 'Trening został usunięty!');
    }

    // LISTA OBECNOŚCI DLA TRENINGU
    public function attendance($id)
    {
        $event = TeamEvent::findOrFail($id);

        $attendees = DB::table('event_rsvp')
            ->join('users', 'event_rsvp.idUser', '=', 'users.id')
            ->join('rsvp_statuses', 'event_rsvp.idStatus', '=', 'rsvp_statuses.idStatus')
            ->where('event_rsvp.idEvent', $id)
            ->select(
                'users.id as idUser',
                'users.imie',
                'users.nazwisko',
                'users.login',
                'rsvp_statuses.nazwa as status_nazwa',
                'event_rsvp.idStatus',
                'event_rsvp.czas_zgloszenia',
                'event_rsvp.uwagi'
            )
            ->orderBy('users.nazwisko')
            ->paginate(15);

        $statuses = DB::table('rsvp_statuses')->get();

        return view('coach.events.attendance', compact('event', 'attendees', 'statuses'));
    }

// ZMIANA STATUSU OBECNOŚCI
    public function updateAttendance(Request $request, $eventId, $userId)
    {
        $request->validate([
            'idStatus' => 'required|exists:rsvp_statuses,idStatus',
        ]);

        DB::table('event_rsvp')
            ->where('idEvent', $eventId)
            ->where('idUser', $userId)
            ->update(['idStatus' => $request->idStatus]);

        // Aktualizuj statystyki zawodnika
        $total = DB::table('event_rsvp')
            ->where('idUser', $userId)
            ->count();

        $attended = DB::table('event_rsvp')
            ->join('rsvp_statuses', 'event_rsvp.idStatus', '=', 'rsvp_statuses.idStatus')
            ->where('event_rsvp.idUser', $userId)
            ->where('rsvp_statuses.nazwa', 'potwierdzona')
            ->count();

        $rate = $total > 0 ? round(($attended / $total) * 100, 2) : 0;

        DB::table('player_stats')->updateOrInsert(
            ['idUser' => $userId],
            [
                'treningi_lacznie'    => $total,
                'treningi_odbyte'     => $attended,
                'wskaznik_frekwencji' => $rate,
            ]
        );

        return back()->with('success', 'Status obecności zaktualizowany!');
    }
}
