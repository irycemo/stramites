<div>

    <x-header>Complemento</x-header>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-3">

        <div class="bg-white p-3 rounded-lg text-center shadow-md mb-3">

            <div class="mb-2">

                <Label class="text-lg tracking-widest rounded-xl border-gray-500">Buscar trámite</Label>

            </div>

            <div class="flex lg:w-1/2 mx-auto mb-4">

                <select class="bg-white rounded-l text-sm border border-r-transparent  focus:ring-0" wire:model="año">
                    @foreach ($años as $año)

                        <option value="{{ $año }}">{{ $año }}</option>

                    @endforeach
                </select>

                <input type="number" placeholder="# Control" min="1" class="bg-white text-sm w-full focus:ring-0 border border-r-transparent @error('numero_control') border-red-500 @enderror" wire:model="numero_control">

                <input type="number" placeholder="Usuario" min="1" class="bg-white text-sm w-full focus:ring-0 @error('usuario') border-red-500 @enderror" wire:model="usuario">

                <button
                    wire:click="buscarTramite"
                    wire:loading.attr="disabled"
                    wire:target="buscarTramite"
                    type="button"
                    class="bg-blue-400 hover:shadow-lg text-white font-bold px-4 rounded-r text-sm hover:bg-blue-700 focus:outline-blue-400 focus:outline-offset-2 relative">

                    <div wire:loading.flex class="flex absolute top-2 right-2 items-center">
                        <svg class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                    </div>

                    <svg wire:loading.remove xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" />
                    </svg>

                </button>

            </div>

            @if($tramite)

                <div class="flex flex-col lg:w-1/2 mx-auto">

                    <div class="flex-auto bg-white p-4 rounded-lg mb-3">

                        <div class="mb-2">

                            <Label class="text-lg tracking-widest rounded-xl border-gray-500">Tipo de servicio</Label>
                        </div>

                        <div>

                            <select class="bg-white rounded text-sm w-full" wire:model.live="tipo_servicio">

                                <option value="" selected>Seleccione una opción</option>
                                <option value="ordinario">Ordinario</option>
                                <option value="urgente">Urgente</option>
                                <option value="extra_urgente">Extra Urgente</option>

                            </select>

                        </div>

                        <div>

                            @error('tipo_servicio') <span class="error text-sm text-red-500">{{ $message }}</span> @enderror

                        </div>

                    </div>

                    <x-button-blue
                        wire:click="crearTramite"
                        wire:loading.attr="disabled"
                        wire:target="crearTramite">

                        <img wire:loading wire:target="crearTramite" class="h-4 mr-1" src="{{ asset('storage/img/loading3.svg') }}" alt="Loading">

                        <span >Crear trámite</span>

                    </x-button-blue>

                </div>

            @endif

        </div>


        <div class="bg-white p-3 rounded-lg text-center shadow-md mb-3">

            @if($tramite)

                <h1 class="text-lg tracking-widest rounded-xl border-gray-500 text-center mb-5">Trámite</h1>

               @include('livewire.entrada.comun.tramite')

            @endif

        </div>

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
