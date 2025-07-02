<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\Http\Request;

class PostController extends Controller
{
    //
    // Tüm postları listele
    public function index()
    {
        $posts = Post::all();
        return response()->json($posts);
    }

    // Yeni post oluştur
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'type' => 'required|string',
            'image' => 'nullable|string',
        ]);

        $validated['admin_user_id'] = auth()->id();

        $post = Post::create($validated);

        return response()->json(['message' => 'Post oluşturuldu', 'post' => $post], 201);
    }

    // Belirli postu getir
    public function show($id)
    {
        $post = Post::findOrFail($id);
        return response()->json($post);
    }

    // Post güncelle
    public function update(Request $request, $id)
    {
        $post = Post::findOrFail($id);

        $validated = $request->validate([
            'title' => 'sometimes|required|string|max:255',
            'content' => 'sometimes|required|string',
            'type' => 'sometimes|required|string',
            'image' => 'nullable|string',
        ]);

        $post->update($validated);

        return response()->json(['message' => 'Post güncellendi', 'post' => $post]);
    }

    // Post sil
    public function destroy($id)
    {
        $post = Post::findOrFail($id);
        $post->delete();

        return response()->json(['message' => 'Post silindi']);
    }
}
