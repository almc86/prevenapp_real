@extends('layouts.app')

@section('content')
<div class="container mx-auto p-4 max-w-md">
    <h1 class="text-2xl font-bold mb-4">Crear Usuario</h1>

    <form action="{{ route('usuarios.store') }}" method="POST">
        @csrf

        <div class="mb-4">
            <label for="name" class="block font-medium">Nombre</label>
            <input type="text" name="name" class="w-full border p-2 rounded" required>
        </div>

        <div class="mb-4">
            <label for="email" class="block font-medium">Correo electrónico</label>
            <input type="email" name="email" class="w-full border p-2 rounded" required>
        </div>

        <div class="mb-4">
            <label for="password" class="block font-medium">Contraseña</label>
            <input type="password" name="password" class="w-full border p-2 rounded" required>
        </div>

        <div class="mb-4">
            <label for="role_id" class="block font-medium">Rol</label>
            <select name="role_id" class="w-full border p-2 rounded" required>
                <option value="">Seleccionar</option>
                @foreach ($roles as $rol)
                    <option value="{{ $rol->id }}">{{ ucfirst($rol->nombre) }}</option>
                @endforeach
            </select>
        </div>

        <button type="submit" class="bg-green-500 text-white px-4 py-2 rounded">Guardar</button>
    </form>
</div>
@endsection
