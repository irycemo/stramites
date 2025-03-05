<div class="bg-white p-3 rounded-lg  shadow-md">

    <h1 class="text-lg tracking-widest rounded-xl border-gray-500 text-center mb-5">Trámite</h1>

    @include('livewire.entrada.comun.tramite')

    @if ($tramite->observaciones)

        <div class="rounded-lg bg-gray-100 py-1 px-2 my-3">

            <strong>Observaciones:</strong>

            <p>{{ $tramite->observaciones }}</p>

        </div>

    @endif

    <div class="mt-4 text-right">

        @if ($tramite->estado == 'nuevo' || $tramite->estado == 'rechazado')

            <button
                wire:click="editarTramite"
                wire:loading.attr="disabled"
                wire:target="editarTramite"
                type="button"
                class="bg-green-400 hover:shadow-lg text-white font-bold px-4 py-2 rounded text-sm hover:bg-green-700 focus:outline-none ">
                <img wire:loading wire:target="editarTramite" class="mx-auto h-4 mr-1" src="{{ asset('storage/img/loading3.svg') }}" alt="Loading">
                Editar
            </button>

        @endif

        @if (!$tramite->fecha_pago)

            <button
                wire:click="reimprimir"
                wire:loading.attr="disabled"
                wire:target="reimprimir"
                type="button"
                class="bg-blue-400 hover:shadow-lg text-white font-bold px-4 py-2 rounded text-sm hover:bg-blue-700 focus:outline-none ">
                <img wire:loading wire:target="reimprimir" class="mx-auto h-4 mr-1" src="{{ asset('storage/img/loading3.svg') }}" alt="Loading">
                Reimprimir
            </button>

            @can('Validar pago')

                <button
                    wire:click="validarPago"
                    wire:loading.attr="disabled"
                    wire:target="validarPago"
                    type="button"
                    class="bg-red-400 hover:shadow-lg text-white font-bold px-4 py-2 rounded text-sm hover:bg-red-700 focus:outline-none ">
                    <img wire:loading wire:target="validarPago" class="mx-auto h-4 mr-1" src="{{ asset('storage/img/loading3.svg') }}" alt="Loading">
                    Validar
                </button>

            @endif

        @endif

    </div>

</div>
