@extends('layouts.argon')

@section('content')
    <div class="card">
        <div class="card-body">
            <h5>Editar Competencia</h5>

            <form action="{{ route('competencias.update', $competencia->id) }}" method="POST">
                @include('competencias._form')
            </form>
        </div>
    </div>

@endsection