<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;

class MeetingController extends Controller
{
    public function quick(Request $request)
    {
        if (! config('app.jitsi_enabled', env('JITSI_ENABLED', true))) {
            abort(404);
        }

        $room = $request->query('room');
        if (! $room) {
            $room = 'MIO-ETUDE-' . Str::upper(Str::random(8));
            return redirect()->route('meeting.quick', ['room' => $room]);
        }

        $domain = config('services.jitsi.domain', env('JITSI_DOMAIN', 'meet.jit.si'));
        $user = $request->user();
        $shareUrl = route('meeting.quick', ['room' => $room]);
        $lobbyEnabled = config('services.jitsi.lobby_enabled', env('JITSI_LOBBY_ENABLED', false));

        return view('meetings.quick', [
            'room' => $room,
            'domain' => $domain,
            'user' => $user,
            'shareUrl' => $shareUrl,
            'lobbyEnabled' => $lobbyEnabled,
        ]);
    }
}
