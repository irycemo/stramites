@if ($flags['antecedente_gravamen'])

    <x-h4>Antecedente de gravamen</x-h4>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-3 bg-white p-4 rounded-lg mb-3 shadow-md">

        <x-input-group for="modelo_editar.asiento_registral" label="Movimiento registral" :error="$errors->first('modelo_editar.asiento_registral')" class="">

            <x-input-text type="number" id="modelo_editar.asiento_registral" wire:model.lazy="modelo_editar.asiento_registral" :readonly="$modelo_editar->folio_real == null"/>

        </x-input-group>

        <x-input-group for="modelo_editar.tomo_gravamen" label="Tomo de gravamen" :error="$errors->first('modelo_editar.tomo_gravamen')" class="">

            <x-input-text type="number" id="modelo_editar.tomo_gravamen" wire:model.lazy="modelo_editar.tomo_gravamen" :readonly="$modelo_editar->asiento_registral != null"/>

        </x-input-group>

        <x-input-group for="modelo_editar.registro_gravamen" label="Registro de gravamen" :error="$errors->first('modelo_editar.registro_gravamen')" class="">

            <x-input-text type="number" id="modelo_editar.registro_gravamen" wire:model.lazy="modelo_editar.registro_gravamen" :readonly="$modelo_editar->asiento_registral != null"/>

        </x-input-group>

    </div>

@endif
