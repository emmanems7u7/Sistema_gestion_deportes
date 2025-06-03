<?php

namespace App\Http\Controllers;

use App\Models\Map;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
class MapController extends Controller
{

    public function index()
    {
        $breadcrumb = [
            ['name' => 'Inicio', 'url' => route('home')],
            ['name' => 'Mapas', 'url' => route('maps.index')],


        ];
        $maps = Map::all();
        return view('maps.index', compact('breadcrumb', 'maps'));
    }

    public function create()
    {
        $breadcrumb = [
            ['name' => 'Inicio', 'url' => route('home')],
            ['name' => 'Mapas', 'url' => route('maps.index')],
            ['name' => 'Crear Mapa', 'url' => route('maps.index')],

        ];
        $map = new Map();
        return view('maps.create', compact('breadcrumb', 'map'));
    }

    public function store(Request $request, $tipo = 0)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'direccion' => 'nullable|string',
            'description' => 'nullable|string',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
        ]);

        $map = Map::create([
            'titulo' => $request->title,
            'descripcion' => $request->description,
            'direccion' => $request->direccion,
            'latitud' => $request->latitude,
            'longitud' => $request->longitude,
            'accion_usuario' => Auth::user()->name
        ]);
        if ($tipo == 0) {
            return redirect()->back('maps.index')->with('success', 'Mapa creado exitosamente.');

        } else if ($tipo == 1) {

            return response()->json([
                'message' => 'Mapa creado exitosamente',
                'map' => $map,
            ], 201);

        }
    }

    public function edit(Map $map)
    {
        $breadcrumb = [
            ['name' => 'Inicio', 'url' => route('home')],
            ['name' => 'Mapas', 'url' => route('maps.index')],
            ['name' => 'Crear Mapa', 'url' => route('maps.index')],

        ];
        return view('maps.edit', compact('breadcrumb', 'map'));
    }

    public function update(Request $request, Map $map)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'direccion' => 'nullable|string',
            'description' => 'nullable|string',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
        ]);

        $map->titulo = $data['title'];
        $map->direccion = $data['direccion'];
        $map->descripcion = $data['description'] ?? null;
        $map->latitud = $data['latitude'];
        $map->longitud = $data['longitude'];

        $map->save();

        return redirect()->route('maps.index')->with('success', 'Mapa actualizado exitosamente.');
    }

    public function destroy(Map $map)
    {
        $map->delete();
        return redirect()->route('maps.index')->with('success', 'Mapa eliminado exitosamente.');
    }
    public function showJSON($id)
    {
        $ubicacion = Map::findOrFail($id);

        return response()->json([
            'titulo' => $ubicacion->titulo,
            'descripcion' => $ubicacion->descripcion,
            'direccion' => $ubicacion->direccion,
            'latitud' => $ubicacion->latitud,
            'longitud' => $ubicacion->longitud,
        ]);
    }
    public function getAllJson()
    {
        $ubicaciones = Map::all(['id', 'titulo', 'direccion']);
        return response()->json($ubicaciones);
    }

}
