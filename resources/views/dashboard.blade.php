<x-app-layout>
    <div class="py-8 px-6 max-w-7xl mx-auto">
        <h2 class="text-2xl font-bold mb-6 text-center">SISTEMA MUNICIPAL DE SALUD CAPINOTA</h2>

        {{-- Panel dinámico según rol --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            {{-- Módulo: Consulta Médica --}}
            @if (auth()->user()->role !== 'admin')
                <div class="bg-white p-4 rounded shadow">
                    <h3 class="text-lg font-bold mb-2">Consulta Médica</h3>
                    <p class="mb-4 text-sm text-gray-600">Formulario para registro clínico del paciente.</p>
                    <a href="{{ route('consultas.index') }}"
                        class="inline-block bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                        Ingresar
                    </a>
                </div>
            @endif

            {{-- Módulo exclusivo: Estadísticas y reportes (solo para estadísticos) --}}
            @if (auth()->user()->role === 'estadistico')
                <div class="bg-white p-4 rounded shadow">
                    <h3 class="text-lg font-bold mb-2">Estadísticas Municipales</h3>
                    <p class="mb-4 text-sm text-gray-600">Visualización de reportes y datos epidemiológicos.</p>
                    <a href="{{ route('construccion') }}"
                        class="inline-block bg-gray-600 text-white px-4 py-2 rounded hover:bg-gray-700">
                        Acceder
                    </a>
                </div>
            @endif


            {{-- Módulo: Usuarios (solo admin) --}}
            @if (auth()->user()->role === 'admin')
                <div class="bg-white p-4 rounded shadow">
                    <h3 class="text-lg font-bold mb-2">Gestión de Usuarios</h3>
                    <p class="mb-4 text-sm text-gray-600">Administrar personal médico y administrativo.</p>
                    <a href="{{ route('usuarios.index') }}"
                        class="inline-block bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">
                        Administrar
                    </a>
                </div>

                {{-- Establecimientos --}}
                <div class="bg-white p-4 rounded shadow">
                    <h3 class="text-lg font-bold mb-2">Establecimientos</h3>
                    <p class="mb-4 text-sm text-gray-600">Registrar y editar centros de salud.</p>
                    <a href="{{ route('establecimientos.index') }}"
                        class="inline-block bg-indigo-600 text-white px-4 py-2 rounded hover:bg-indigo-700">
                        Ver
                    </a>
                </div>
            @endif

            {{-- Módulo en construcción con redirección visual --}}
            <div class="bg-gray-100 p-4 rounded shadow">
                <h3 class="text-lg font-bold mb-2">Vacunación en menores de 5 años</h3>
                <p class="mb-4 text-sm text-gray-600">Seguimiento mensual de cobertura e indicadores.</p>
                <a href="{{ route('construccion') }}"
                    class="inline-block bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600">
                    Ver módulo
                </a>
            </div>

            {{-- Módulos en desarrollo (todos los roles) --}}
            <div class="bg-yellow-100 border-l-4 border-yellow-500 text-yellow-700 p-4 col-span-1 md:col-span-3">
                <p class="font-bold text-lg">🛠️ Módulos en construcción</p>
                <ul class="list-disc list-inside mt-2 text-sm leading-6">
                    <li>🗂️ Fichas clínicas</li>
                    <li>📊 Estadísticas y reportes</li>
                    <li>💉 Vacunas en menores de 5 años y reportes</li>
                    <li>💉 Vacunas en mayores de 5 años y reportes</li>
                    <li>👴 Atención integral de Carmelos (60+ años) y reportes</li>
                    <li>📦 Programas (Tuberculosis, Chagas, Covid-19, etc) y reportes</li>
                </ul>
                <p class="mt-3 text-sm">Muy pronto estarán disponibles. Gracias por su comprensión.</p>
            </div>
        </div>

        {{-- Pie de página --}}
        <div class="mt-10 text-center text-gray-600 text-sm">
            <p>Todos los derechos reservados &copy; Dr. Panozo</p>
        </div>
    </div>
</x-app-layout>
