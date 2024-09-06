@if ($flags['adiciona'])

    <div class="flex justify-around space-x-3 bg-white p-4 rounded-lg mb-3 shadow-md relative" wire:loading.class.delay.longest="opacity-50">

        <div class="flex space-x-4 items-center">

            <Label>¿Adiciona a otro trámite?</Label>

            <x-checkbox wire:model.live="adicionaTramite"></x-checkbox>

        </div>

        @if($adicionaTramite)

            <div class="inline-flex">

                <select class="bg-white rounded-l text-sm border border-r-transparent  focus:ring-0" wire:model="año">
                    @foreach ($años as $año)

                        <option value="{{ $año }}">{{ $año }}</option>

                    @endforeach
                </select>

                <input type="number" class="bg-white text-sm w-20 focus:ring-0 @error('folio') border-red-500 @enderror" wire:model="folio">

                <input type="number" class="bg-white text-sm w-20 border-l-0 focus:ring-0 @error('usuario') border-red-500 @enderror" wire:model="usuario">

                <button
                    wire:click="buscarTramiteAdiciona"
                    wire:loading.attr="disabled"
                    wire:target="buscarTramiteAdiciona"
                    type="button"
                    class="bg-blue-400 hover:shadow-lg text-white font-bold px-4 rounded-r text-sm hover:bg-blue-700 focus:outline-blue-400 focus:outline-offset-2">

                    <img wire:loading wire:target="buscarTramiteAdiciona" class="mx-auto h-5 mr-1" src="{{ asset('storage/img/loading3.svg') }}" alt="Loading">

                    <svg wire:loading.remove wire:target="buscarTramiteAdiciona" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" />
                    </svg>

                </button>

            </div>

        @endif

        <div wire:loading.delay.longest class="flex absolute top-1 right-1 items-center">
            <svg class="animate-spin h-5 w-5 text-blue-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
        </div>

    </div>

    <div class="w-full">

        @error('modelo_editar.adiciona') <span class="error text-sm text-red-500 bg-white p-1 rounded-lg mb-3 shadow-md mt-1 w-full inline-flex">{{ $message }}</span> @enderror

    </div>

@endif
