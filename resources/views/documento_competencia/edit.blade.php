@extends('layouts.argon')

@section('content')
    <div class="card">
        <div class="card-body">
            <h5>Editar Documento de competencia</h5>

            <form action="{{ route('documento_competencia.update', $competencia->id) }}" method="POST"
                enctype="multipart/form-data">
                @include('documento_competencia.form')
            </form>
        </div>
    </div>

@endsection