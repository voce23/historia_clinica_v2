<div class="max-w-4xl mx-auto py-6">
    <h2 class="text-xl font-bold mb-4">Gestión de Establecimientos</h2>

    <form wire:submit.prevent="guardar" class="space-y-4 mb-6">
        <div>
            <label>Nombre</label>
            <input type="text" wire:model="nombre" class="w-full border rounded p-2">
            @error('nombre') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
        </div>

        <div>
            <label>Código</label>
            <input type="text" wire:model="codigo" class="w-full border rounded p-2">
            @error('codigo') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
        </div>

        <div>
            <label>Tipo</label>
            <select wire:model="tipo" class="w-full border rounded p-2">
                <option value="">Seleccione</option>
                <option value="CSA">CSA</option>
                <option value="Hospital">Hospital</option>
            </select>
            @error('tipo') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
        </div>

        <div>
            <label>Municipio</label>
            <input type="text" wire:model="municipio" class="w-full border rounded p-2">
        </div>

        <div>
            <label><input type="checkbox" wire:model="activo"> Activo</label>
        </div>

        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">
            {{ $modoEditar ? 'Actualizar' : 'Registrar' }}
        </button>
        <button type="button" wire:click="resetFormulario" class="ml-2 text-gray-500">Limpiar</button>
    </form>

    <h3 class="text-lg font-semibold mb-2">Lista de Establecimientos</h3>

    <table class="min-w-full bg-white border">
        <thead>
            <tr class="bg-gray-100">
                <th class="px-2 py-1 border">#</th>
                <th class="px-2 py-1 border">Nombre</th>
                <th class="px-2 py-1 border">Código</th>
                <th class="px-2 py-1 border">Tipo</th>
                <th class="px-2 py-1 border">Activo</th>
                <th class="px-2 py-1 border">Acciones</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($establecimientos as $i => $e)
                <tr>
                    <td class="px-2 py-1 border">{{ $i + 1 }}</td>
                    <td class="px-2 py-1 border">{{ $e->nombre }}</td>
                    <td class="px-2 py-1 border">{{ $e->codigo }}</td>
                    <td class="px-2 py-1 border">{{ $e->tipo }}</td>
                    <td class="px-2 py-1 border">{{ $e->activo ? 'Sí' : 'No' }}</td>
                    <td class="px-2 py-1 border">
                        <button wire:click="editar({{ $e->id }})" class="text-blue-600 mr-2">Editar</button>
                        <button wire:click="eliminar({{ $e->id }})" class="text-red-600">Eliminar</button>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
