@extends('layouts.argon')

@section('content')

    <div class="card">
        <div class="card-body">
            <h5>Crear Competencia</h5>

            <form action="{{ route('competencias.store') }}" method="POST">
                @include('competencias._form')
            </form>
        </div>
    </div>

@endsection