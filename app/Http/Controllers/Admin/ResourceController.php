<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Resource;
use Illuminate\Http\Request;

class ResourceController extends Controller
{
    //
    public function index()
    {
        return response()->json(Resource::all());
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string',
            'description' => 'nullable|string',
            'file_path' => 'nullable|string',
            'url' => 'nullable|url',
            'related_course' => 'nullable|string',
        ]);

        $resource = Resource::create($validated);

        return response()->json(['message' => 'Resource oluşturuldu', 'resource' => $resource], 201);
    }

    public function show($id)
    {
        $resource = Resource::findOrFail($id);
        return response()->json($resource);
    }

    public function update(Request $request, $id)
    {
        $resource = Resource::findOrFail($id);
        $resource->update($request->all());

        return response()->json(['message' => 'Resource güncellendi', 'resource' => $resource]);
    }

    public function destroy($id)
    {
        $resource = Resource::findOrFail($id);
        $resource->delete();

        return response()->json(['message' => 'Resource silindi']);
    }
}
