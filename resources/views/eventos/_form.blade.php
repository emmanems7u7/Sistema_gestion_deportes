@csrf
@if(isset($evento))
    @method('PUT')
@endif

<div class="mb-3">
    <label for="nombre" class="form-label">Nombre del evento</label>
    <input type="text" name="nombre" id="nombre" class="form-control" value="{{ old('nombre', $evento->nombre ?? '') }}"
        required>
    @error('nombre') <small class="text-danger">{{ $message }}</small> @enderror
</div>

<div class="mb-3">
    <label for="competencia_id" class="form-label">Competencia</label>
    <select name="competencia_id" id="competencia_id" class="form-control" required>
        <option value="">Seleccione una competencia</option>
        @foreach($competencias as $competencia)
            <option value="{{ $competencia->id }}" {{ old('competencia_id', $evento->competencia_id ?? '') == $competencia->id ? 'selected' : '' }}>
                {{ $competencia->nombre }}
            </option>
        @endforeach
    </select>
    @error('competencia_id') <small class="text-danger">{{ $message }}</small> @enderror
</div>

<div class="mb-3">
    <label for="fecha" class="form-label">Fecha</label>
    <input type="date" name="fecha" id="fecha" class="form-control" value="{{ old('fecha', $evento->fecha ?? '') }}"
        required>
    @error('fecha') <small class="text-danger">{{ $message }}</small> @enderror
</div>

<div class="mb-3">
    <label for="hora_inicio" class="form-label">Hora Inicio</label>
    <input type="time" name="hora_inicio" id="hora_inicio" class="form-control"
        value="{{ old('hora_inicio', $evento->hora_inicio ?? '') }}" required>
    @error('hora_inicio') <small class="text-danger">{{ $message }}</small> @enderror
</div>

<div class="mb-3">
    <label for="hora_fin" class="form-label">Hora Fin</label>
    <input type="time" name="hora_fin" id="hora_fin" class="form-control"
        value="{{ old('hora_fin', $evento->hora_fin ?? '') }}" required>
    @error('hora_fin') <small class="text-danger">{{ $message }}</small> @enderror
</div>

<div class="mb-3">
    <label for="maps_id" class="form-label">Ubicación</label>
    <select name="maps_id" id="maps_id" class="form-control" required>
        <option value="">Seleccione una Ubicación</option>
        @foreach($ubicaciones as $ubicacion)
            <option value="{{ $ubicacion->id }}" {{ old('maps_id', $evento->maps_id ?? '') == $ubicacion->id ? 'selected' : '' }}>
                {{ $ubicacion->titulo }} | {{ $ubicacion->direccion }}
            </option>
        @endforeach
    </select>
    @error('maps_id') <small class="text-danger">{{ $message }}</small> @enderror
</div>

<div class="mb-3">
    No encuentras la ubicacion? <a href="#" data-bs-toggle="modal" data-bs-target="#crearUbicacionModal">Crear
        Ubicación</a>

</div>

<div id="map-container" style="display:none; margin-top: 1rem;" class="mb-3">
    <div class="card">
        <div class="card-body">
            <h5 id="map-titulo"></h5>
            <p id="map-descripcion"></p>
            <p id="map-direccion"></p>
            <div id="leaflet-map" style="height: 300px;"></div>
        </div>
    </div>

</div>

<button type="submit" class="btn btn-primary">
    {{ isset($evento) ? 'Actualizar' : 'Guardar' }}
</button>
<a href="{{ route('eventos.index') }}" class="btn btn-secondary">Cancelar</a>



<!-- Modal -->
<div class="modal fade" id="crearUbicacionModal" tabindex="-1" aria-labelledby="crearUbicacionModalLabel"
    aria-hidden="true">
    <div class="modal-dialog">

        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="crearUbicacionModalLabel">Crear Nueva Ubicación</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <div class="modal-body">
                <form id="mapForm" action="" method="POST">

                    @include('maps._form')

                    <a class="btn btn-success" onclick="enviar_mapa()">Crear</a>
                    <a href="{{ route('maps.index') }}" class="btn btn-secondary">Cancelar</a>
                </form>
            </div>

        </div>

    </div>
</div>
<script>
    async function enviar_mapa() {


        const url = "{{ route('maps.store', 1) }}";

        const title = document.getElementById('title').value;
        const description = document.getElementById('description').value;
        const direccion = document.getElementById('direccion').value;
        const latitude = document.getElementById('latitude').value;
        const longitude = document.getElementById('longitude').value;
        // Recolectar datos del formulario
        const formData = new FormData();

        formData.set('_token', '{{ csrf_token() }}');
        formData.set('title', title);
        formData.set('description', description);
        formData.set('direccion', direccion);
        formData.set('latitude', latitude);
        formData.set('longitude', longitude);

        try {
            const response = await fetch(url, {
                method: 'POST',
                headers: {
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                },
                body: formData
            });

            const data = await response.json();

            if (response.ok) {
                alertify.alert('Registro exitoso', data.message, function () {
                    // Callback cuando el alertify se cierre
                    var myModalEl = document.getElementById('crearUbicacionModal');
                    var modal = bootstrap.Modal.getInstance(myModalEl);

                    if (modal) {
                        modal.hide();


                    }
                    actualizarSelectUbicaciones();

                    title.value = "";
                    description.value = "";
                    direccion.value = "";
                    latitude.value = "";
                    longitude.value = "";
                });

            } else {

                alert('Error: ' + (data.message || 'Error al crear mapa'));
            }

        } catch (error) {
            console.error('Error fetch:', error);
            alert('Error en la solicitud');
        }
    }

    async function actualizarSelectUbicaciones() {
        try {
            const response = await fetch("/ubicaciones/json");
            const ubicaciones = await response.json();

            const select = document.getElementById('maps_id');
            select.innerHTML = '<option value="">Seleccione una Ubicación</option>';

            ubicaciones.forEach(ubicacion => {
                const option = document.createElement('option');
                option.value = ubicacion.id;
                option.textContent = `${ubicacion.titulo} | ${ubicacion.direccion}`;
                select.appendChild(option);
            });
        } catch (error) {
            console.error('Error al actualizar ubicaciones:', error);
        }
    }
</script>
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const select = document.getElementById('maps_id');
        const mapContainer = document.getElementById('map-container');
        const tituloEl = document.getElementById('map-titulo');
        const descripcionEl = document.getElementById('map-descripcion');
        const direccionEl = document.getElementById('map-direccion');
        const leafletMapEl = document.getElementById('leaflet-map');

        let map;
        let marker;

        async function fetchUbicacion(id) {
            try {
                const response = await fetch(`/ubicaciones/${id}`);
                if (!response.ok) throw new Error('Error al cargar ubicación');
                const data = await response.json();
                return data;
            } catch (error) {
                console.error(error);
                alert('Error al obtener datos de ubicación');
                return null;
            }
        }

        async function updateMap() {
            const id = select.value;
            if (!id) {
                mapContainer.style.display = 'none';
                if (map) {
                    map.remove();
                    map = null;
                    marker = null;
                }
                return;
            }

            const data = await fetchUbicacion(id);
            if (!data) return;

            tituloEl.textContent = "Lugar: " + data.titulo;
            descripcionEl.textContent = "Descripcion: " + data.descripcion;
            direccionEl.textContent = "Direccion: " + data.direccion;

            mapContainer.style.display = 'block';

            const lat = parseFloat(data.latitud);
            const lng = parseFloat(data.longitud);

            if (!map) {
                map = L.map('leaflet-map').setView([lat, lng], 13);

                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    maxZoom: 19,
                    attribution: '&copy; OpenStreetMap contributors'
                }).addTo(map);

                marker = L.marker([lat, lng]).addTo(map);
            } else {
                map.setView([lat, lng], 13);
                marker.setLatLng([lat, lng]);
            }
        }

        // Cargar mapa al cargar la página si hay valor seleccionado
        updateMap();

        // Escuchar cambio en el select
        select.addEventListener('change', updateMap);
    });
</script>