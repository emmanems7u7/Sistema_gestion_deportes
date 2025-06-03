@csrf
@if(isset($competencia))
    @method('PUT')
@endif

<div class="row mb-3">
    <div class="col-md-6">
        <label class="form-label">Nombre:</label>
        <input type="text" name="nombre" class="form-control @error('nombre') is-invalid @enderror"
            value="{{ old('nombre', $competencia->nombre ?? '') }}" required>
        @error('nombre')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-6">
        <label class="form-label">Código de categoría:</label>
        <select name="codigo_categoria" class="form-select @error('codigo_categoria') is-invalid @enderror" required>
            <option value="">-- Seleccione una categoría --</option>
            @foreach ($categorias as $categoria)
                <option value="{{ $categoria->catalogo_codigo }}" {{ old('codigo_categoria', $competencia->codigo_categoria ?? '') == $categoria->catalogo_codigo ? 'selected' : '' }}>
                    {{ $categoria->catalogo_descripcion }}
                </option>
            @endforeach
        </select>
        @error('codigo_categoria')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
</div>

<div class="mb-3">
    <label class="form-label">Descripción:</label>
    <textarea name="descripcion" class="form-control @error('descripcion') is-invalid @enderror"
        rows="3">{{ old('descripcion', $competencia->descripcion ?? '') }}</textarea>
    @error('descripcion')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

<div class="row mb-3">
    <div class="col-md-6">
        <label class="form-label">Fecha de inicio:</label>
        <input type="date" name="fecha_inicio" class="form-control @error('fecha_inicio') is-invalid @enderror"
            value="{{ old('fecha_inicio', $competencia->fecha_inicio ?? '') }}">
        @error('fecha_inicio')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-6">
        <label class="form-label">Fecha de fin:</label>
        <input type="date" name="fecha_fin" class="form-control @error('fecha_fin') is-invalid @enderror"
            value="{{ old('fecha_fin', $competencia->fecha_fin ?? '') }}">
        @error('fecha_fin')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
</div>



<div class="d-flex justify-content-end gap-2">
    <a href="{{ route('competencias.index') }}" class="btn btn-secondary">Cancelar</a>
    <button type="submit" class="btn btn-primary">Guardar</button>
</div>