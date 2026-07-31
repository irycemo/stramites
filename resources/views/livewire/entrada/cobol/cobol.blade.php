<div>

    <x-header>Tramites de cobol</x-header>

    <div class="bg-white rounded-lg p-4 shadow-xl text-center">

        <x-input-group for="numero_control" label="Número de control" :error="$errors->first('numero_control')" class="w-full lg:w-1/3 mx-auto mb-3">

            <x-input-text id="numero_control" wire:model="numero_control" />

        </x-input-group>

        <button
            wire:click="buscarNumeroControl"
            wire:loading.attr="disabled"
            wire:target="buscarNumeroControl"
            type="button"
            class="bg-blue-400 flex hover:shadow-lg text-white font-bold px-4 py-2 mx-auto rounded text-sm hover:bg-blue-700 focus:outline-blue-400 focus:outline-offset-2">

            Buscar

            <div wire:loading.flex class="ml-2">
                <svg class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
            </div>

        </button>

    </div>

</div>

@push('scripts')

    <script>

        document.addEventListener('imprimir_recibo', event => {

            const tramite = event.detail[0].tramite;

            var url_orden = "{{ route('tramites.orden', '')}}" + "/" + tramite;

            window.open(url_orden, '_blank');

            var url_ticket = "{{ route('tramites.recibo', '')}}" + "/" + tramite;

            window.open(url_ticket, '_blank');

        });

    </script>

@endpush
