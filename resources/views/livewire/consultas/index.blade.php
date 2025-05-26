<div class="p-6 bg-white rounded shadow">
    <h1 class="text-xl font-bold mb-4">Consulta Médica</h1>

    @if (session()->has('message'))
        <div class="mb-4 p-3 rounded bg-green-100 text-green-800 border border-green-300">
            {{ session('message') }}
        </div>
    @endif

    {{-- FORMULARIO PRINCIPAL --}}
    <form wire:submit.prevent="save" class="grid grid-cols-2 gap-4 mb-6">
        {{-- Seleccionar paciente --}}
        <select wire:model="form.paciente_id" class="col-span-2 border px-3 py-2 rounded">
            <option value="">Seleccione paciente</option>
            @foreach ($pacientes as $p)
                <option value="{{ $p->id }}">{{ $p->nombre }} {{ $p->apellido }} ({{ $p->numero_historia }})
                </option>
            @endforeach
        </select>
        <input type="date" wire:model="form.fecha" class="col-span-2 border px-3 py-2 rounded">


        {{-- Motivo y examen --}}
        <textarea wire:model="form.motivo" placeholder="Motivo de la consulta" class="col-span-2 border px-3 py-2 rounded"></textarea>
        <textarea wire:model="form.examen_fisico" placeholder="Examen físico (opcional)" rows="10"
            class="col-span-2 border px-3 py-2 rounded"></textarea>



        {{-- Diagnósticos múltiples --}}
        <label class="col-span-2 font-semibold">Diagnósticos</label>
        <div class="col-span-2 grid grid-cols-3 gap-4">
            @foreach ($diagnosticos as $d)
                <label
                    class="flex items-start space-x-2 bg-blue-50 p-2 rounded-md border border-blue-200 shadow-sm text-sm">
                    <input type="checkbox" wire:model="form.diagnosticos" value="{{ $d->id }}" class="mt-1">
                    <span>{{ $d->codigo }} - {{ $d->descripcion }}</span>
                </label>
            @endforeach
        </div>



        {{-- Medicamentos múltiples --}}
        <label class="col-span-2 font-semibold mt-4">Medicamentos</label>
        <div class="col-span-2 grid grid-cols-3 gap-4">
            @foreach ($medicamentos as $m)
                <label
                    class="flex items-start space-x-2 bg-green-50 p-2 rounded-md border border-green-200 shadow-sm text-sm">
                    <input type="checkbox" wire:model="form.medicamentos" value="{{ $m->id }}" class="mt-1">
                    <span>{{ $m->nombre }} ({{ $m->presentacion }})</span>
                </label>
            @endforeach
        </div>





        {{-- Botones --}}
        <div class="col-span-2 flex space-x-4 mt-2">
            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">Guardar</button>
            <button type="button" wire:click="resetForm"
                class="bg-gray-500 text-white px-4 py-2 rounded">Limpiar</button>
        </div>
    </form>

    {{-- HISTORIAL DE CONSULTAS --}}
    <h2 class="text-lg font-bold mb-2">Historial de consultas</h2>
    <table class="w-full table-auto border-collapse mb-6">
        <thead class="bg-gray-100">
            <tr>
                <th class="border px-4 py-2">#</th>
                <th class="border px-4 py-2">Paciente</th>
                <th class="border px-4 py-2">Motivo</th>
                <th class="border px-4 py-2">Fecha</th>
                <th class="border px-4 py-2">Acciones</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($consultas as $c)
                <tr>
                    <td class="border px-4 py-2">{{ $loop->iteration }}</td>
                    <td class="border px-4 py-2">{{ $c->paciente->nombre }} {{ $c->paciente->apellido }}</td>
                    <td class="border px-4 py-2">{{ Str::limit($c->motivo, 30) }}</td>
                    <td class="border px-4 py-2">{{ $c->created_at->format('d/m/Y') }}</td>
                    <td class="border px-4 py-2 space-x-2">
                        <button wire:click="edit({{ $c->id }})" class="text-blue-600">Editar</button>
                        @if ($confirmingDeleteId === $c->id)
                            <button wire:click="deleteConfirmed" class="text-red-600 font-bold">¿Confirmar?</button>
                            <button wire:click="$set('confirmingDeleteId', null)"
                                class="text-gray-600">Cancelar</button>
                        @else
                            <button wire:click="confirmDelete({{ $c->id }})"
                                class="text-red-600">Eliminar</button>
                        @endif
                        <a href="{{ route('consultas.exportarWord', $c->id) }}" target="_blank"
                            class="text-green-600 hover:underline">Exportar</a>

                    </td>

                </tr>
            @empty
                <tr>
                    <td colspan="5" class="text-center py-4">No hay consultas registradas.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    {{ $consultas->links() }}
    <div class="mt-12 text-center border-t pt-6 text-sm text-gray-600">
        <p>____________________</p>
        <p><strong>Firma y Sello</strong></p>
        <p class="uppercase">CENTRO DE SALUD AMBULATORIO HORNOMA</p>
    </div>

</div>
