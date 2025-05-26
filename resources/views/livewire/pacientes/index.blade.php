<div class="p-6 bg-white rounded shadow">
    <h1 class="text-xl font-bold mb-4">Módulo Pacientes</h1>

    @if (session()->has('message'))
        <div class="mb-4 p-3 rounded bg-green-100 text-green-800 border border-green-300">
            {{ session('message') }}
        </div>
    @endif

    <form wire:submit.prevent="save" class="grid grid-cols-2 gap-4 mb-6">
        <input type="text" wire:model="form.numero_historia" placeholder="N° Historia Clínica"
            class="border px-3 py-2 rounded">
        <input type="text" wire:model="form.documento" placeholder="Documento" class="border px-3 py-2 rounded">

        <input type="text" wire:model="form.nombre" placeholder="Nombre" class="border px-3 py-2 rounded">
        <input type="text" wire:model="form.apellido" placeholder="Apellido" class="border px-3 py-2 rounded">

        <input type="date" wire:model="form.fecha_nacimiento" class="border px-3 py-2 rounded col-span-1">
        <input type="text" wire:model="form.comunidad" placeholder="Comunidad"
            class="border px-3 py-2 rounded col-span-1">

        <div class="col-span-2 flex space-x-4">
            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">Guardar</button>
            <button type="button" wire:click="resetForm"
                class="bg-gray-500 text-white px-4 py-2 rounded">Limpiar</button>
        </div>
    </form>

    <div class="mb-4 flex items-center space-x-4">
        <input type="text" wire:model.live="search" placeholder="Buscar..."
            class="w-full px-4 py-2 border rounded" />


        <select wire:model="searchBy" class="px-3 py-2 border rounded">
            <option value="nombre">Nombre</option>
            <option value="numero_historia">Historia Clínica</option>
        </select>

    </div>


    <table class="w-full table-auto border-collapse mb-6">
        <thead class="bg-gray-100">
            <tr>
                <th class="border px-4 py-2">#</th>
                <th class="border px-4 py-2">Historia</th>
                <th class="border px-4 py-2">Nombre</th>
                <th class="border px-4 py-2">Documento</th>
                <th class="border px-4 py-2">F. Nac.</th>
                <th class="border px-4 py-2">Comunidad</th>
                <th class="border px-4 py-2">Acciones</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($pacientes as $p)
                <tr>
                    <td class="border px-4 py-2">{{ $loop->iteration }}</td>
                    <td class="border px-4 py-2">{{ $p->numero_historia }}</td>
                    <td class="border px-4 py-2">{{ $p->nombre }} {{ $p->apellido }}</td>
                    <td class="border px-4 py-2">{{ $p->documento }}</td>
                    <td class="border px-4 py-2">{{ $p->fecha_nacimiento }}</td>
                    <td class="border px-4 py-2">{{ $p->comunidad }}</td>
                    <td class="border px-4 py-2 space-x-2">
                        <button wire:click="edit({{ $p->id }})" class="text-blue-600">Editar</button>
                        @if ($confirmingDeleteId === $p->id)
                            <button wire:click="deleteConfirmed" class="text-red-600 font-bold">¿Confirmar?</button>
                            <button wire:click="$set('confirmingDeleteId', null)"
                                class="text-gray-600">Cancelar</button>
                        @else
                            <button wire:click="confirmDelete({{ $p->id }})"
                                class="text-red-600">Eliminar</button>
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="text-center py-4">No hay pacientes encontrados.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    {{ $pacientes->links() }}


</div>
