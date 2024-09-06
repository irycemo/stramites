<div>

    @if ($flags['antecedente'])

        <x-h4>Antecedente de propiedad</x-h4>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-3 bg-white p-4 rounded-lg mb-3 shadow-md" wire:loading.class.delay.longest="opacity-50">

            <x-input-group for="modelo_editar.folio_real" label="Folio real" :error="$errors->first('modelo_editar.folio_real')" class="">

                <x-input-text type="number" id="modelo_editar.folio_real" wire:model.lazy="modelo_editar.folio_real" />

            </x-input-group>

            <x-input-group for="modelo_editar.tomo" label="Tomo" :error="$errors->first('modelo_editar.tomo')" class="">

                <x-input-text type="number" id="modelo_editar.tomo" wire:model.lazy="modelo_editar.tomo" :readonly="$modelo_editar->folio_real != null"/>

            </x-input-group>

            <x-input-group for="modelo_editar.registro" label="Registro" :error="$errors->first('modelo_editar.registro')" class="">

                <x-input-text type="number" id="modelo_editar.registro" wire:model.lazy="modelo_editar.registro" :readonly="$modelo_editar->folio_real != null"/>

            </x-input-group>

            <x-input-group for="modelo_editar.numero_propiedad" label="Número de propiedad" :error="$errors->first('modelo_editar.numero_propiedad')" class="">

                <x-input-text type="number" id="modelo_editar.numero_propiedad" wire:model.lazy="modelo_editar.numero_propiedad" :readonly="$modelo_editar->folio_real != null"/>

            </x-input-group>

            <x-input-group for="modelo_editar.distrito" label="Distrito" :error="$errors->first('modelo_editar.distrito')" class="">

                <x-input-select id="modelo_editar.distrito" wire:model.lazy="modelo_editar.distrito" class="w-full" :disabled="$modelo_editar->folio_real != null ">

                    <option value="" selected>Seleccione una opción</option>

                    @foreach ($distritos as $key => $item)

                        <option value="{{  $key }}">{{  $item }}</option>

                    @endforeach

                </x-input-select>

            </x-input-group>

            <x-input-group for="modelo_editar.seccion" label="Seccion" :error="$errors->first('modelo_editar.seccion')" class="">

                <x-input-select id="modelo_editar.seccion" wire:model.lazy="modelo_editar.seccion" class="w-full" :disabled="$modelo_editar->folio_real != null ">

                    <option value="" selected>Seleccione una opción</option>

                    @foreach ($secciones as $seccion)

                        <option value="{{ $seccion }}">{{ $seccion }}</option>

                    @endforeach

                </x-input-select>

            </x-input-group>

        </div>

    @endif

</div>
