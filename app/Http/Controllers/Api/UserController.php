<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{

    public function index()
    {
        return User::all();
    }

    // Tek bir kullanıcıyı getir
    public function show($id)
    {
        $user = User::findOrFail($id);
        return response()->json($user);
    }

    // Kullanıcıyı güncelle
    public function update(Request $request, $id)
    {

        $user = User::findOrFail($id);

        if (auth()->id() !== $user->id) {
            return response()->json(['message' => 'Yetkisiz erişim'], 403);
        }
        $data = $request->except('image');

        if ($request->hasFile('image')) {

            if ($user->image && file_exists(public_path($user->image))) {
                unlink(public_path($user->image));
            }

            $filename = time() . '.' . $request->file('image')->getClientOriginalExtension();
            $request->file('image')->move(public_path('uploads'), $filename);
            $data['image'] = 'uploads/' . $filename;
        }

        $user->update($data);

        return response()->json($user);
    }

    // Kullanıcıyı sil
    public function destroy($id)
    {
        $user = User::findOrFail($id);

        if (auth()->id() !== $user->id) {
            return response()->json(['message' => 'Yetkisiz erişim'], 403);
        }

        $user->delete();
        return response()->json(['message' => 'Silindi']);
    }


    public function search(Request $request)
    {
        $query = $request->input('q');

        if (!$query) {
            return response()->json(['message' => 'Arama kelimesi boş olamaz'], 400);
        }

        $users = User::where('F_name', 'like', "%$query%")
            ->orWhere('L_name', 'like', "%$query%")
            ->orWhere('email', 'like', "%$query%")
            ->orWhere('faculty', 'like', "%$query%")
            ->orWhere('profession', 'like', "%$query%")
            ->get();

        return response()->json($users);
    }
}
