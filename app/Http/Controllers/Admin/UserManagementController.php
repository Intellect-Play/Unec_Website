<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AdminUser;
use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;

class UserManagementController extends Controller
{
    // Tüm kullanıcıları getir
    public function index(Request $request)
    {

        $admin = $request->user('admin'); // admin guard'ıyla gelen kullanıcıyı al

        // Eğer super_admin değilse ve 'view_user' izni yoksa engelle
        if ($admin->role->name !== 'super_admin' && !$admin->role->permissions->contains('name', 'view_user')) {
            return response()->json(['message' => 'Yetkiniz yok'], 403);
        }

        return response()->json(User::all());
    }


    public function createAdminUser(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string',
            'email' => 'required|email|unique:admin_users,email',
            'password' => 'required|string|min:6',
            'role_id' => 'required|exists:roles,id',
        ]);

        $validated['password'] = bcrypt($validated['password']);

        $admin = AdminUser::create($validated);

        return response()->json(['message' => 'Admin oluşturuldu', 'admin' => $admin], 201);
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





    public function createRole(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:roles,name'
        ]);

        $role = Role::create([
            'name' => $request->name
        ]);

        return response()->json(['message' => 'Rol oluşturuldu', 'role' => $role], 201);
    }


    public function createPermission(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:permissions,name'
        ]);

        $permission = Permission::create([
            'name' => $request->name
        ]);

        return response()->json(['message' => 'İzin oluşturuldu', 'permission' => $permission], 201);
    }

    public function assignPermissions(Request $request, $roleId)
    {
        $role = Role::findOrFail($roleId);

        $validated = $request->validate([
            'permissions' => 'required|array',
            'permissions.*' => 'exists:permissions,id',
        ]);

        $role->permissions()->sync($validated['permissions']); // mevcut izinleri siler, yenilerini ekler

        return response()->json([
            'message' => 'İzinler başarıyla atandı',
            'role' => $role->load('permissions')
        ]);
    }
}
