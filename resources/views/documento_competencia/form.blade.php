@csrf
<div class="mb-3">
    <label for="documento_nombre">Nombre del Documento</label>
    <input type="text" name="documento_nombre" id="documento_nombre"
        class="form-control @error('documento_nombre') is-invalid @enderror"
        value="{{ old('documento_nombre', $documento->documento_nombre ?? '') }}">
    @error('documento_nombre')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

<div class="mb-3">
    <label for="documento_ruta">Documento (Archivo)</label>
    <input type="file" name="documento_ruta" id="documento_ruta"
        class="form-control @error('documento_ruta') is-invalid @enderror">
    @error('documento_ruta')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>


<button type="submit" class="btn btn-success">Guardar</button>
<a href="{{ route('documento_competencia.index') }}" class="btn btn-secondary">Cancelar</a>