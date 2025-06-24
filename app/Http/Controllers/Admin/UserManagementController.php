<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserManagementController extends Controller
{
    // Tüm kullanıcıları getir
    public function index()
    {
        return response()->json(User::all());
    }

    // Belirli kullanıcıyı getir
    public function show($id)
    {
        $user = User::findOrFail($id);
        return response()->json($user);
    }

    // Kullanıcıyı güncelle
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

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

        return response()->json(['message' => 'Kullanıcı güncellendi', 'user' => $user]);
    }

    // Kullanıcıyı sil
    public function destroy($id)
    {
        $user = User::findOrFail($id);

        if ($user->image && file_exists(public_path($user->image))) {
            unlink(public_path($user->image));
        }

        $user->delete();

        return response()->json(['message' => 'Kullanıcı silindi']);
    }
}
