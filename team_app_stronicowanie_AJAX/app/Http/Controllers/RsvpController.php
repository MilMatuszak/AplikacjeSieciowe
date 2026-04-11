<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\TeamEvent;

class RsvpController extends Controller
{
    // ZGŁOŚ OBECNOŚĆ
    public function store(Request $request, $id)
    {
        $event  = TeamEvent::findOrFail($id);
        $userId = auth()->id();

        $status = DB::table('rsvp_statuses')
            ->where('nazwa', 'zgloszona')
            ->first();

        // Sprawdź czy już zgłoszono
        $existing = DB::table('event_rsvp')
            ->where('idEvent', $id)
            ->where('idUser', $userId)
            ->first();

        if ($existing) {
            return back()->with('error', 'Już zgłosiłeś obecność na tym treningu.');
        }

        DB::table('event_rsvp')->insert([
            'idEvent'  => $id,
            'idUser'   => $userId,
            'idStatus' => $status->idStatus,
        ]);

        // Aktualizuj statystyki
        $this->updateStats($userId);

        return back()->with('success', 'Obecność zgłoszona!');
    }

    // AKTUALIZUJ ZGŁOSZENIE
    public function update(Request $request, $id)
    {
        $request->validate([
            'idStatus' => 'required|exists:rsvp_statuses,idStatus',
        ]);

        DB::table('event_rsvp')
            ->where('idEvent', $id)
            ->where('idUser', auth()->id())
            ->update(['idStatus' => $request->idStatus]);

        return back()->with('success', 'Zgłoszenie zaktualizowane!');
    }

    // AKTUALIZUJ STATYSTYKI ZAWODNIKA
    private function updateStats($userId)
    {
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
    }
}
