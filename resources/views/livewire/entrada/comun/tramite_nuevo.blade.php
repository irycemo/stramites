<div class="bg-white p-3 rounded-lg  shadow-md">

    <h1 class="text-lg tracking-widest rounded-xl border-gray-500 text-center mb-5">Trámite Nuevo</h1>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-2">

        <div class="rounded-lg bg-gray-100 py-1 px-2">

            <p><strong>Categoría:</strong> {{ $servicio['categoria']['nombre'] }}</p>

        </div>

        <div class="rounded-lg bg-gray-100 py-1 px-2">

            <p><strong>Servicio:</strong> {{ $servicio['nombre'] }}</p>

        </div>

        <div class="rounded-lg bg-gray-100 py-1 px-2">

            <p><strong>Tipo de servicio:</strong> {{ Str::ucfirst($modelo_editar->tipo_servicio) }}</p>

        </div>

        <div class="rounded-lg bg-gray-100 py-1 px-2">

            <p><strong>Solicitante:</strong>{{ $modelo_editar->solicitante }} / {{ $modelo_editar->nombre_solicitante }}</p>

        </div>

        @if ($modelo_editar->tomo)

            <div class="rounded-lg bg-gray-100 py-1 px-2">

                <p><strong>Tomo:</strong> {{ $modelo_editar->tomo }}</p>

            </div>

        @endif

        @if ($modelo_editar->registro)

            <div class="rounded-lg bg-gray-100 py-1 px-2">

                <p><strong>Registro:</strong> {{ $modelo_editar->registro }}</p>

            </div>

        @endif

        @if ($modelo_editar->distrito)

            <div class="rounded-lg bg-gray-100 py-1 px-2">

                <p><strong>Distrito:</strong> {{ $modelo_editar->distrito }}</p>

            </div>

        @endif

        @if ($modelo_editar->seccion)

            <div class="rounded-lg bg-gray-100 py-1 px-2">

                <p><strong>Sección:</strong> {{ $modelo_editar->seccion }}</p>

            </div>

        @endif

        @if ($modelo_editar->numero_oficio)

            <div class="rounded-lg bg-gray-100 py-1 px-2">

                <p><strong>Número de oficio:</strong> {{ $modelo_editar->numero_oficio }}</p>

            </div>

        @endif

        @if ($modelo_editar->numero_notaria)

            <div class="rounded-lg bg-gray-100 py-1 px-2">

                <p><strong>Número de notaría:</strong> {{ $modelo_editar->numero_notaria }}</p>

            </div>

        @endif

        @if ($modelo_editar->nombre_notario)

            <div class="rounded-lg bg-gray-100 py-1 px-2">

                <p><strong>Nombre del notarío:</strong> {{ $modelo_editar->nombre_notario }}</p>

            </div>

        @endif

        @if ($modelo_editar->cantidad)

            <div class="rounded-lg bg-gray-100 py-1 px-2">

                <p><strong>Cantidad:</strong> {{ $modelo_editar->cantidad }}</p>

            </div>

        @endif

        <div class="rounded-lg bg-gray-100 py-1 px-2">

            <p><strong>Monto:</strong> ${{ number_format($modelo_editar->monto, 2) }}</p>

        </div>

    </div>

    @if ($modelo_editar->observaciones)

        <div class="rounded-lg bg-gray-100 py-1 px-2 my-3">

            <strong>Observaciones:</strong>

            <p>{{ $modelo_editar->observaciones }}</p>

        </div>

    @endif

    <div class="mt-4 text-right">

        <div class="flex space-x-4 items-center">

            <x-checkbox wire:model="mantener"></x-checkbox>

            <Label>Mantener información</Label>

        </div>

        @if ($editar)

            <button
                wire:click="actualizar"
                wire:loading.attr="disabled"
                wire:target="actualizar"
                type="button"
                class="bg-blue-400 hover:shadow-lg text-white font-bold px-4 py-2 rounded text-sm hover:bg-blue-700 focus:outline-blue-400 focus:outline-offset-2 flex items-center ml-auto">
                <img wire:loading wire:target="actualizar" class="mx-auto h-4 mr-1" src="{{ asset('storage/img/loading3.svg') }}" alt="Loading">
                Actualizar trámite
            </button>

        @else

            <button
                wire:click="crear"
                wire:loading.attr="disabled"
                wire:target="crear"
                type="button"
                class="bg-blue-400 hover:shadow-lg text-white font-bold px-4 py-2 rounded text-sm hover:bg-blue-700 focus:outline-blue-400 focus:outline-offset-2 flex items-center ml-auto">
                <img wire:loading wire:target="crear" class="mx-auto h-4 mr-1" src="{{ asset('storage/img/loading3.svg') }}" alt="Loading">
                Crear nuevo trámite
            </button>

        @endif

    </div>

</div>
