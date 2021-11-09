<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

use App\Models\Group;

class GroupController extends Controller
{
    public function index()
    {
        $groups = Group::all();

        return response()->json([
            'status' => true,
            'groups' => $groups
        ], 200);
    }
    
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|unique:groups|max:255'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ], 400);
        }

        Group::create($request->all());

        return response()->json([
            'status' => 200,
            'message' => 'Grupo creado correctamente'
        ], 201);
    }

    public function show(Group $group)
    {
        return response()->json([
            'status' => true,
            'group' => $group
        ], 200);
    }

    public function update(Request $request, Group $group)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|max:255|unique:groups,id,'.$group->id
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ], 400);
        }

        $group->update($request->all());

        return response()->json([
            'status' => 200,
            'message' => 'Grupo actualizado correctamente'
        ], 200);
    }

    public function destroy(Group $group)
    {
        try {
            $group->delete();
    
            return response()->json([
                'status' => true,
                'message' => 'Grupo eliminado correctamente'
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => true,
                'message' => 'Error al elimianr el grupo'
            ], 200);
        }
    }
}
