@extends('layout')
@section('title', 'Acceso restringido')
@section('content')
    <x-heading title="Acceso restringido" description="Este registro está fuera de tu cuenta o de la moneda activa." />
    <a class="button primary" href="{{ route('dashboard') }}">Volver al inicio</a>
@endsection
