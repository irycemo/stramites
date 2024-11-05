@if ($flags['antecedente'])

    <x-h4>Antecedente de propiedad</x-h4>

    <div class=" bg-white p-4 rounded-lg mb-3 shadow-md" wire:loading.class.delay.longest="opacity-50">

        <div class="grid grid-cols-1 md:grid-cols-4 mb-3 gap-4">

            <x-input-group for="modelo_editar.folio_real" label="Folio real" :error="$errors->first('modelo_editar.folio_real')" class="">

                <x-input-text type="number" id="modelo_editar.folio_real" wire:model.lazy="modelo_editar.folio_real" />

            </x-input-group>

            <x-input-group for="modelo_editar.distrito" label="Distrito" :error="$errors->first('modelo_editar.distrito')" class="">

                <x-input-select id="modelo_editar.distrito" wire:model.lazy="modelo_editar.distrito" class="w-full" :disabled="$modelo_editar->folio_real != null ">

                    <option value="" selected>Seleccione una opción</option>

                    @foreach ($distritos as $key => $item)

                        <option value="{{  $key }}">{{  $item }}</option>

                    @endforeach

                </x-input-select>

            </x-input-group>

            <x-input-group for="modelo_editar.tomo" label="Tomo" :error="$errors->first('modelo_editar.tomo')" class="">

                <x-input-text type="number" id="modelo_editar.tomo" wire:model.lazy="modelo_editar.tomo" :readonly="$modelo_editar->folio_real != null"/>

            </x-input-group>

            <div class="flex items-end gap-2">

                <x-input-group for="modelo_editar.registro" label="Registro" :error="$errors->first('modelo_editar.registro')" class="">

                    <x-input-text type="number" id="modelo_editar.registro" wire:model.lazy="modelo_editar.registro" :readonly="$modelo_editar->folio_real != null"/>

                </x-input-group>

                @if(!$modelo_editar->folio_real)

                    <button
                        wire:click="consultarAntecedentes"
                        wire:loading.attr="disabled"
                        wire:target="consultarAntecedentes"
                        type="button"
                        class="bg-blue-400 hover:shadow-lg h-5 w-5 text-white text-center rounded-lg p-4 hover:bg-blue-700 focus:outline-blue-400 focus:outline-offset-2 relative">

                        <div wire:loading.flex class="flex absolute top-2 right-2 items-center">
                            <svg class="animate-spin h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                        </div>

                        <svg wire:loading.remove xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-4 w-4 flex absolute top-2 right-2 items-center">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" />
                        </svg>

                    </button>

                @endif

            </div>

        </div>

        <div>

            <x-input-group for="modelo_editar.numero_propiedad" label="Número de propiedad" :error="$errors->first('modelo_editar.numero_propiedad')" class=" col-span-2 w-full">

                <x-input-select id="modelo_editar.numero_propiedad" wire:model.lazy="modelo_editar.numero_propiedad" class="w-full" :disabled="$modelo_editar->folio_real != null ">

                    <option value="" selected>Seleccione una opción</option>

                    @foreach ($antecedentes as $key => $antecedente)

                        <option value="{{ $antecedente['noprop'] }}">{{ $antecedente['noprop'] }} - {{ $antecedente['ubicacion'] }}</option>

                    @endforeach

                </x-input-select>

            </x-input-group>

        </div>

    </div>

@endif
