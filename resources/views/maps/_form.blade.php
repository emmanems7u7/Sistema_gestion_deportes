@csrf

<div class="mb-3">
    <label for="title" class="form-label">Título</label>
    <input type="text" name="title" id="title" class="form-control @error('title') is-invalid @enderror"
        value="{{ old('title', $map->titulo ?? '') }}" required>
    @error('title')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

<div class="mb-3">
    <label for="description" class="form-label">Descripción</label>
    <textarea name="description" id="description"
        class="form-control @error('description') is-invalid @enderror">{{ old('description', $map->descripcion ?? '') }}</textarea>
    @error('description')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

<div class="mb-3">
    <label for="direccion" class="form-label">Dirección</label>
    <input type="text" name="direccion" id="direccion" class="form-control @error('direccion') is-invalid @enderror"
        value="{{ old('direccion', $map->direccion ?? '') }}" required>
    @error('direccion')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

<label class="form-label">Selecciona la ubicación en el mapa</label>
<div id="map" style="height: 300px; margin-bottom: 1rem;"></div>

<input type="hidden" id="latitude" name="latitude" value="{{ old('latitude', $map->latitude ?? '') }}" required>
@error('latitude')
    <div class="text-danger small">{{ $message }}</div>
@enderror

<input type="hidden" id="longitude" name="longitude" value="{{ old('longitude', $map->longitude ?? '') }}" required>
@error('longitude')
    <div class="text-danger small">{{ $message }}</div>
@enderror

<script>
    // Obtén valores iniciales de los inputs (en edit estarán cargados, en create estarán vacíos)
    const initialLat = parseFloat(document.getElementById('latitude').value) || -16.5000;
    const initialLng = parseFloat(document.getElementById('longitude').value) || -68.1500;

    const map = L.map('map').setView([initialLat, initialLng], initialLat && initialLng ? 13 : 6);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 19,
        attribution: '&copy; OpenStreetMap contributors'
    }).addTo(map);

    let marker;

    // Si ya hay coordenadas, pon marcador inicial
    if (document.getElementById('latitude').value && document.getElementById('longitude').value) {
        marker = L.marker([initialLat, initialLng]).addTo(map);
    }

    map.on('click', function (e) {
        const { lat, lng } = e.latlng;
        if (marker) marker.setLatLng(e.latlng);
        else marker = L.marker(e.latlng).addTo(map);

        document.getElementById('latitude').value = lat.toFixed(7);
        document.getElementById('longitude').value = lng.toFixed(7);
    });
</script>