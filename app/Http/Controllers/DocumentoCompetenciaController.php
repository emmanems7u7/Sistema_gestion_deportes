<?php

namespace App\Http\Controllers;
use App\Models\DocumentoCompetencia;
use App\Models\Competencia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
class DocumentoCompetenciaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Competencia $competencia)
    {
        $breadcrumb = [
            ['name' => 'Inicio', 'url' => route('home')],
            ['name' => 'Competencias', 'url' => route('competencias.index')],
            ['name' => 'Crear Documento asociado', 'url' => route('categorias.index')],

        ];
        return view('documento_competencia.create', compact('breadcrumb', 'competencia'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, Competencia $competencia)
    {
        $request->validate([

            'documento_nombre' => 'required|string|max:255',
            'documento_ruta' => 'required|file|mimes:pdf,doc,docx,zip|max:2048',
            'accion_usuario' => 'nullable|string|max:255',
        ]);

        if ($request->hasFile('documento_ruta')) {
            $file = $request->file('documento_ruta');
            $filename = Str::uuid() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('documentos'), $filename);
            $path = 'documentos/' . $filename;
        }

        DocumentoCompetencia::create([
            'competencia_id' => $competencia->id,
            'documento_nombre' => $request->documento_nombre,
            'documento_ruta' => $path,
            'accion_usuario' => Auth::user()->name,
        ]);

        return redirect()->route('competencias.index')
            ->with('success', 'Documento registrado correctamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show(DocumentoCompetencia $documentoCompetencia)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $documento = DocumentoCompetencia::findOrFail($id);
        $competencias = Competencia::all();
        return view('documento_competencia.edit', compact('documento', 'competencias'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'competencia_id' => 'required|exists:competencias,id',
            'documento_nombre' => 'required|string|max:255',
            'documento_ruta' => 'required|file|mimes:pdf,doc,docx,zip|max:2048',
            'accion_usuario' => 'nullable|string|max:255',
        ]);

        $documento = DocumentoCompetencia::findOrFail($id);

        $data = $request->only(['competencia_id', 'documento_nombre', 'accion_usuario']);

        if ($request->hasFile('documento_ruta')) {

            if (!empty($data['documento_ruta']) && File::exists(public_path($data['documento_ruta']))) {
                File::delete(public_path($data['documento_ruta']));
            }

            $file = $request->file('documento_ruta');
            $filename = Str::uuid() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('documentos'), $filename);
            $data['documento_ruta'] = 'documentos/' . $filename;
        }

        $documento->update($data);

        return redirect()->route('documento_competencia.index')
            ->with('success', 'Documento actualizado correctamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        DocumentoCompetencia::destroy($id);
        return redirect()->route('documento_competencia.index')
            ->with('success', 'Documento eliminado.');
    }
    public function mostrarArchivo($archivo)
    {
        $path = storage_path('app/documentos/' . $archivo);

        if (!File::exists($path)) {
            abort(404);
        }

        return response()->file($path);
    }
}
