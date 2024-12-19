@if($flags['numero_inmuebles'])

    <div class="flex-auto bg-white p-4 rounded-lg mb-3 shadow-md">

        <div class="mb-2">

            <Label class="text-lg tracking-widest rounded-xl border-gray-500">Número de inmuebles</Label>
        </div>

        <div>

            <input type="number" min="1" class="bg-white rounded text-sm w-full" wire:model.lazy="modelo_editar.numero_inmuebles">

        </div>

        <div>

            @error('modelo_editar.numero_inmuebles') <span class="error text-sm text-red-500">{{ $message }}</span> @enderror

        </div>

    </div>

@endif
