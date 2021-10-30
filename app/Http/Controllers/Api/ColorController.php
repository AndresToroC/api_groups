<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

use App\Models\Color;

class ColorController extends Controller
{
    public function index()
    {
        $colors = Color::all();

        return response()->json([
            'status' => true,
            'colors' => $colors
        ], 200);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'color' => 'required|unique:colors'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ]);
        }

        Color::create($request->all());

        return response()->json([
            'status' => true,
            'message' => 'Color creado correctamente'
        ]);
    }

    public function show(Color $color)
    {
        return response()->json([
            'status' => true,
            'color' => $color
        ]);
    }

    public function update(Request $request, Color $color)
    {
        $validator = Validator::make($request->all(), [
            'color' => 'required|unique:colors,id,'.$color->id
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ]);
        }

        $color->update($request->all());

        return response()->json([
            'status' => true,
            'message' => 'Color actualizado correctamente'
        ]);
    }

    public function destroy(Color $color)
    {
        try {
            $color->delete();
            
            return response()->json([
                'status' => true,
                'message' => 'Color eliminado correctamente'
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => 'Error al eliminar el color'
            ]);
        }
    }
}
