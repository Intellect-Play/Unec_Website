<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Connection;

class ConnectionController extends Controller
{
    //
    public function sendRequest(Request $request)
    {
        $request->validate([
            'connected_user_id' => 'required|exists:users,id',
        ]);

        if ($request->connected_user_id == auth()->id()) {
            return response()->json(['message' => 'Kendine arkadaşlık isteği gönderemezsin!'], 400);
        }

        $connection = Connection::create([
            'user_id' => auth()->id(),
            'connected_user_id' => $request->connected_user_id,
            'status' => 'pending'
        ]);

        return response()->json(['message' => 'Arkadaşlık isteği gönderildi', 'connection' => $connection]);
    }

    public function acceptRequest($id)
    {
        $connection = Connection::where('id', $id)
            ->where('connected_user_id', auth()->id())
            ->where('status', 'pending')
            ->firstOrFail();

        $connection->update(['status' => 'accepted']);

        return response()->json(['message' => 'İstek kabul edildi', 'connection' => $connection]);
    }

    public function rejectRequest($id)
    {
        $connection = Connection::where('id', $id)
            ->where('connected_user_id', auth()->id())
            ->where('status', 'pending')
            ->firstOrFail();

        $connection->update(['status' => 'rejected']);

        return response()->json(['message' => 'İstek reddedildi', 'connection' => $connection]);
    }

    public function myConnections()
    {
        $userId = auth()->id();

        $connections = Connection::where(function ($query) use ($userId) {
            $query->where('user_id', $userId)
                ->orWhere('connected_user_id', $userId);
        })
            ->where('status', 'accepted')
            ->with(['sender', 'receiver'])
            ->get();

        return response()->json($connections);
    }
}
