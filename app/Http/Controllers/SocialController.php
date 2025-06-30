<?php

namespace App\Http\Controllers;

use App\Models\Social;
use Illuminate\Http\Request;

class SocialController extends Controller
{
    //

    public function index()
    {
        $socials = auth()->user()->socials()->with('socialNetwork')->get();
        return response()->json($socials);
    }

    // Yeni sosyal hesap ekle
    public function store(Request $request)
    {
        $request->validate([
            'url' => 'required|url',
            'social_network_id' => 'required|exists:social_networks,id',
        ]);

        $social = Social::create([
            'user_id' => auth()->id(),
            'url' => $request->url,
            'social_network_id' => $request->social_network_id,
        ]);

        return response()->json(['message' => 'Sosyal hesap eklendi', 'social' => $social]);
    }

    // Sosyal hesap sil
    public function destroy($id)
    {
        $social = Social::where('id', $id)->where('user_id', auth()->id())->firstOrFail();
        $social->delete();

        return response()->json(['message' => 'Sosyal hesap silindi']);
    }
}
