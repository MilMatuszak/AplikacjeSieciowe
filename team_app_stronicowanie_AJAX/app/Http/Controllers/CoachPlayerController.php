<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\User;

class CoachPlayerController extends Controller
{
    // LISTA ZAWODNIKÓW
    public function index(Request $request)
    {
        $query = User::whereHas('userRoles', function($q) {
            $q->join('roles', 'user_roles.idRola', '=', 'roles.idRola')
                ->where('roles.nazwa', 'player')
                ->where('user_roles.jest_aktywna', true);
        });

        // FILTROWANIE
        if ($request->search) {
            $query->where(function($q) use ($request) {
                $q->where('imie', 'LIKE', '%' . $request->search . '%')
                    ->orWhere('nazwisko', 'LIKE', '%' . $request->search . '%')
                    ->orWhere('login', 'LIKE', '%' . $request->search . '%');
            });
        }

        $players = $query->orderBy('nazwisko')->paginate(15);

        return view('coach.players.index', compact('players'));
    }

    // STATYSTYKI ZAWODNIKA
    public function stats($id)
    {
        $player = User::findOrFail($id);

        $stats = DB::table('player_stats')
            ->where('idUser', $id)
            ->first();

        $rsvps = DB::table('event_rsvp')
            ->join('team_events', 'event_rsvp.idEvent', '=', 'team_events.idEvent')
            ->join('rsvp_statuses', 'event_rsvp.idStatus', '=', 'rsvp_statuses.idStatus')
            ->where('event_rsvp.idUser', $id)
            ->select(
                'team_events.nazwa',
                'team_events.data_i_czas_start',
                'team_events.miejsce',
                'rsvp_statuses.nazwa as status_nazwa'
            )
            ->orderBy('team_events.data_i_czas_start', 'desc')
            ->paginate(10);

        return view('coach.players.stats', compact('player', 'stats', 'rsvps'));
    }
}
