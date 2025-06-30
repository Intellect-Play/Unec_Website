<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SocialNetwork;
use Illuminate\Http\Request;

class SocialNetworkController extends Controller
{
    //

    public function index()
    {
        $networks = SocialNetwork::all();
        return response()->json($networks);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:social_networks,name',
            'icon_path' => 'nullable|string',
        ]);

        $network = SocialNetwork::create([
            'name' => $request->name,
            'icon_path' => $request->icon_path,
        ]);

        return response()->json(['message' => 'Sosyal ağ eklendi', 'network' => $network]);
    }

    public function destroy($id)
    {
        $network = SocialNetwork::findOrFail($id);
        $network->delete();

        return response()->json(['message' => 'Sosyal ağ silindi']);
    }
}
