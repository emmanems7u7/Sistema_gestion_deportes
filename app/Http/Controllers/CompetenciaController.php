<?php

namespace App\Http\Controllers;

use App\Models\Catalogo;
use App\Models\Competencia;
use Illuminate\Http\Request;

class CompetenciaController extends Controller
{
    public function index()
    {
        $breadcrumb = [
            ['name' => 'Inicio', 'url' => route('home')],
            ['name' => 'Competencias', 'url' => route('competencias.index')],


        ];
        $competencias = Competencia::with('documentos', 'eventos')->paginate(10)->through(function ($competencia) {
            // Ejemplo: transformar el código de categoría a texto (puede venir de una relación o lógica)
            $competencia->categoria_nombre = Catalogo::where('catalogo_codigo', $competencia->codigo_categoria)->first()->catalogo_descripcion;
            return $competencia;
        });
        return view('competencias.index', compact('breadcrumb', 'competencias'));
    }

    public function create()
    {
        $breadcrumb = [
            ['name' => 'Inicio', 'url' => route('home')],
            ['name' => 'Catalogo', 'url' => route('catalogos.index')],
            ['name' => 'Crear Categoria', 'url' => route('categorias.index')],

        ];

        $categorias = Catalogo::where('categoria_id', 7)->get();
        return view('competencias.create', compact('breadcrumb', 'categorias'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string|max:1000',
            'fecha_inicio' => 'required|date',
            'fecha_fin' => 'required|date|after_or_equal:fecha_inicio',
            'codigo_categoria' => 'required|string|max:50',

        ]);

        Competencia::create($request->all());
        return redirect()->route('competencias.index')->with('success', 'Competencia creada correctamente.');
    }

    public function show($id)
    {
        $competencia = Competencia::findOrFail($id);
        return view('competencias.show', compact('competencia'));
    }

    public function edit($id)
    {

        $competencia = Competencia::findOrFail($id);

        $breadcrumb = [
            ['name' => 'Inicio', 'url' => route('home')],
            ['name' => 'Competencias', 'url' => route('competencias.index')],
            ['name' => 'Editar Competencia', 'url' => route('competencias.index')],

        ];
        $categorias = Catalogo::where('categoria_id', 7)->get();

        return view('competencias.edit', compact('breadcrumb', 'competencia', 'categorias'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string|max:1000',
            'fecha_inicio' => 'required|date',
            'fecha_fin' => 'required|date|after_or_equal:fecha_inicio',
            'codigo_categoria' => 'required|string|max:50',

        ]);

        $competencia = Competencia::findOrFail($id);
        $competencia->update($request->all());

        return redirect()->route('competencias.index')->with('success', 'Competencia actualizada correctamente.');
    }

    public function destroy($id)
    {
        $competencia = Competencia::findOrFail($id);
        $competencia->delete();

        return redirect()->route('competencias.index')->with('success', 'Competencia eliminada correctamente.');
    }
}
