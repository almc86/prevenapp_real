@extends('layouts.app')

@section('content')
<div class="container mx-auto p-4">
    <h1 class="text-2xl font-bold mb-4">Usuarios</h1>
    <a href="{{ route('usuarios.create') }}" class="bg-blue-500 text-white px-4 py-2 rounded mb-4 inline-block">Crear nuevo usuario</a>

    @if(session('success'))
        <div class="bg-green-100 text-green-800 p-2 rounded mb-4">{{ session('success') }}</div>
    @endif

    <table class="w-full table-auto border">
        <thead class="bg-gray-200">
            <tr>
                <th class="p-2">Nombre</th>
                <th class="p-2">Correo</th>
                <th class="p-2">Rol</th>
            </tr>
        </thead>
        <tbody>
            @foreach($usuarios as $usuario)
            <tr class="border-t">
                <td class="p-2">{{ $usuario->name }}</td>
                <td class="p-2">{{ $usuario->email }}</td>
                <td class="p-2">{{ $usuario->role->nombre ?? 'Sin rol' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="mt-4">
        {{ $usuarios->links() }}
    </div>
</div>
@endsection
