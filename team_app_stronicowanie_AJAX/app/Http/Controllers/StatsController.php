<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;

class StatsController extends Controller
{
    public function myStats()
    {
        $userId = auth()->id();

        $stats = DB::table('player_stats')
            ->where('idUser', $userId)
            ->first();

        $rsvps = DB::table('event_rsvp')
            ->join('team_events', 'event_rsvp.idEvent', '=', 'team_events.idEvent')
            ->join('rsvp_statuses', 'event_rsvp.idStatus', '=', 'rsvp_statuses.idStatus')
            ->where('event_rsvp.idUser', $userId)
            ->select(
                'team_events.nazwa',
                'team_events.data_i_czas_start',
                'team_events.miejsce',
                'rsvp_statuses.nazwa as status_nazwa'
            )
            ->orderBy('team_events.data_i_czas_start', 'desc')
            ->paginate(10);

        return view('shared.stats', compact('stats', 'rsvps'));
    }
}
