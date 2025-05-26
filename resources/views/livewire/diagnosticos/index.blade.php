<div class="p-6 bg-white rounded shadow">
    <h1 class="text-xl font-bold mb-4">Módulo Diagnósticos</h1>

    @if (session()->has('message'))
        <div class="mb-4 p-3 rounded bg-green-100 text-green-800 border border-green-300">
            {{ session('message') }}
        </div>
    @endif

    <form wire:submit.prevent="save" class="grid grid-cols-2 gap-4 mb-6">
        <input type="text" wire:model="form.codigo" placeholder="Código diagnóstico" class="border px-3 py-2 rounded">
        <input type="text" wire:model="form.descripcion" placeholder="Descripción" class="border px-3 py-2 rounded">

        <div class="col-span-2 flex space-x-4">
            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">
                {{ $form['id'] ? 'Actualizar' : 'Guardar' }}
            </button>
            <button type="button" wire:click="resetForm" class="bg-gray-500 text-white px-4 py-2 rounded">
                Limpiar
            </button>
        </div>
    </form>

    <div class="mb-4 flex items-center space-x-4">
        <input type="text" wire:model.live="search" placeholder="Buscar diagnóstico..." class="w-full px-4 py-2 border rounded">
        <select wire:model="searchBy" class="px-3 py-2 border rounded">
            <option value="codigo">Código</option>
            <option value="descripcion">Descripción</option>
        </select>
    </div>

    <table class="w-full table-auto border-collapse mb-6">
        <thead class="bg-gray-100">
            <tr>
                <th class="border px-4 py-2">#</th>
                <th class="border px-4 py-2">Código</th>
                <th class="border px-4 py-2">Descripción</th>
                <th class="border px-4 py-2">Acciones</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($diagnosticos as $d)
                <tr>
                    <td class="border px-4 py-2">{{ $loop->iteration }}</td>
                    <td class="border px-4 py-2 font-mono font-semibold text-blue-700">{{ $d->codigo }}</td>
                    <td class="border px-4 py-2">{{ $d->descripcion }}</td>
                    <td class="border px-4 py-2 space-x-2">
                        <button wire:click="edit({{ $d->id }})" class="text-blue-600">Editar</button>
                        @if ($confirmingDeleteId === $d->id)
                            <button wire:click="deleteConfirmed" class="text-red-600 font-bold">¿Confirmar?</button>
                            <button wire:click="$set('confirmingDeleteId', null)" class="text-gray-600">Cancelar</button>
                        @else
                            <button wire:click="confirmDelete({{ $d->id }})" class="text-red-600">Eliminar</button>
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" class="text-center py-4">No hay diagnósticos registrados.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    {{ $diagnosticos->links() }}
</div>
