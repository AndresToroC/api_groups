<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

use App\Models\User;

class UserController extends Controller
{
    public function index()
    {
        $users = User::paginate(10);

        return response()->json([
            'status' => true,
            'users' => $users
        ], 200);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|max:255',
            'email' => 'required|email|unique:users|max:255',
            'password' => 'required|confirmed',
            'role_id' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ]);
        }

        $request['password'] = Hash::make($request->password);

        $role_id = $request->role_id;

        $role = Role::find($role_id);
        if (!$role) {
            return response()->json([
                'status' => false,
                'message' => 'El rol ingresado no existe'
            ]);
        }

        $user = User::create($request->except(['role_id']));

        $user->assignRole($role_id);

        return response()->json([
            'status' => true,
            'message' => 'Usuario creado correctmente'
        ], 201);
    }

    public function show(User $user)
    {
        $user->getRoleNames();

        return response()->json([
            'status' => true,
            'user' => $user
        ]);
    }

    public function update(Request $request, User $user)
    {
        $rules = [
            'name' => 'required|max:255',
            'email' => 'required|email|max:255|unique:users,id,'.$user->id,
            'role_id' => 'required'
        ];

        if ($request->has('password')) {
            $rules['password'] = 'required|confirmed';
        }
    
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ]);
        }

        if ($request->has('password')) {
            $request['password'] = Hash::make($request->password);
        }

        $role_id = $request->role_id;

        $role = Role::find($role_id);
        if (!$role) {
            return response()->json([
                'status' => false,
                'message' => 'El rol ingresado no existe'
            ]);
        }

        $user->update($request->except(['role_id']));
        
        // Se elimina el rol que tiene asignado
        $user->roles()->detach();

        // Se le asigna el nuevo rol
        $user->assignRole($role_id);

        return response()->json([
            'status' => true,
            'message' => 'Usuario actualizado correctmente'
        ], 201);
    }

    public function destroy(User $user)
    {
        $user->roles()->detach();

        $user->delete();

        return response()->json([
            'status' => true,
            'message' => 'Usuario eliminado corretamente'
        ], 200);
    }
}
