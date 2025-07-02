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
        $request->validate([
            'title' => 'required|string',
            'content' => 'required|string',
            'type' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048', // max 2MB
        ]);

        $data = $request->except('image');
        $data['admin_user_id'] = auth()->id();

        if ($request->hasFile('image')) {
            $filename = time() . '.' . $request->file('image')->getClientOriginalExtension();
            $request->file('image')->move(public_path('uploads'), $filename);
            $data['image'] = 'uploads/' . $filename;
        }

        $post = Post::create($data);

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

        $request->validate([
            'title' => 'sometimes|string',
            'content' => 'sometimes|string',
            'type' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $data = $request->except('image');

        if ($request->hasFile('image')) {
            if ($post->image && file_exists(public_path($post->image))) {
                unlink(public_path($post->image));
            }

            $filename = time() . '.' . $request->file('image')->getClientOriginalExtension();
            $request->file('image')->move(public_path('uploads'), $filename);
            $data['image'] = 'uploads/' . $filename;
        }

        $post->update($data);

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
