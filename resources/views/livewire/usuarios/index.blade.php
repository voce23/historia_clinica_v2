<div class="max-w-4xl mx-auto py-8 space-y-6">
    {{-- Formulario para registrar nuevo usuario --}}
    <h2 class="text-xl font-bold mb-4">Registrar nuevo usuario</h2>

    @if (session()->has('message'))
        <div class="bg-green-100 text-green-700 px-4 py-2 rounded mb-4">
            {{ session('message') }}
        </div>
    @endif


    <form wire:submit.prevent="{{ $modoEditar ? 'actualizarUsuario' : 'crearUsuario' }}" class="space-y-4">

        <div>
            <label>Nombre</label>
            <input type="text" wire:model="nombre" class="w-full border rounded p-2">
            @error('nombre')
                <span class="text-red-500 text-sm">{{ $message }}</span>
            @enderror
        </div>

        <div>
            <label>Email</label>
            <input type="email" wire:model="email" class="w-full border rounded p-2">
            @error('email')
                <span class="text-red-500 text-sm">{{ $message }}</span>
            @enderror
        </div>

        <div>
            <label>Contraseña</label>
            <input type="password" wire:model="password" class="w-full border rounded p-2">
            @error('password')
                <span class="text-red-500 text-sm">{{ $message }}</span>
            @enderror
        </div>

        <div>
            <label>Role</label>
            <select wire:model="role" class="w-full border rounded p-2">
                <option value="">Seleccione</option>
                <option value="medico">Médico</option>
                <option value="enfermera">Enfermera</option>
                <option value="admin">Administrador</option>
            </select>
            @error('role')
                <span class="text-red-500 text-sm">{{ $message }}</span>
            @enderror
        </div>

        <div>
            <label>Establecimiento</label>
            <select wire:model="establecimiento_id" class="w-full border rounded p-2">
                <option value="">Seleccione</option>
                @foreach ($establecimientos as $e)
                    <option value="{{ $e->id }}">{{ $e->nombre }}</option>
                @endforeach
            </select>
            @error('establecimiento_id')
                <span class="text-red-500 text-sm">{{ $message }}</span>
            @enderror
        </div>


        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">
            {{ $modoEditar ? 'Actualizar' : 'Registrar' }}
        </button>
        <button type="button" wire:click="resetFormulario" class="bg-gray-300 text-black px-4 py-2 rounded ml-2">
            Limpiar
        </button>

    </form>




    {{-- Mensaje de éxito --}}
    @if (session()->has('message'))
        <div class="text-green-600 font-semibold">
            {{ session('message') }}
        </div>
    @endif

    {{-- Tabla de usuarios --}}
    <h2 class="text-lg font-bold mt-8 mb-4">Usuarios Registrados</h2>

    <table class="w-full table-auto border text-sm">
        <thead class="bg-gray-200">
            <tr>
                <th class="px-2 py-1">#</th>
                <th class="px-2 py-1">Nombre</th>
                <th class="px-2 py-1">Email</th>
                <th class="px-2 py-1">Role</th>
                <th class="px-2 py-1 border">Establecimiento</th> <!-- AGREGADO -->
                @if (auth()->user()->role === 'admin')
                    <th class="px-2 py-1 border">Acciones</th>
                @endif
            </tr>
        </thead>
        <tbody>
            @foreach ($usuarios as $user)
                <tr class="border-t">
                    <td class="px-2 py-1">{{ $loop->iteration }}</td>
                    <td class="px-2 py-1">{{ $user->name }}</td>
                    <td class="px-2 py-1">{{ $user->email }}</td>
                    <td class="px-2 py-1 capitalize">{{ $user->role }}</td>
                    <td class="px-2 py-1 border">
                        {{ $user->establecimiento?->nombre ?? 'Sin asignar' }}
                    </td>
                    <td class="px-2 py-1">
                        @if (auth()->user()->role === 'admin')
                            <button wire:click="editar({{ $user->id }})"
                                class="text-blue-600 hover:underline">Editar</button>

                            @if ($confirmandoEliminacionId === $user->id)
                                <button wire:click="eliminarUsuario" class="text-red-600 font-bold">¿Confirmar?</button>
                                <button wire:click="$set('confirmandoEliminacionId', null)"
                                    class="text-gray-500">Cancelar</button>
                            @else
                                <button wire:click="confirmarEliminacion({{ $user->id }})"
                                    class="text-red-600 hover:underline">Eliminar</button>
                            @endif
                        @endif
                    </td>

                </tr>
            @endforeach
        </tbody>
    </table>
</div>
