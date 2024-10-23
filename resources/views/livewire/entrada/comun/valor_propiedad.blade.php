@if($flags['valor_propiedad'])

    <div class="flex-auto bg-white p-4 rounded-lg mb-3 shadow-md">

        <div class="mb-2">

            <Label class="text-lg tracking-widest rounded-xl border-gray-500">Valor de la propiedad</Label>
        </div>

        <div>

            <input type="number" class="bg-white rounded text-sm w-full" wire:model.lazy="modelo_editar.valor_propiedad">
            <small>Moneda nacional</small>

        </div>

        <div>

            @error('modelo_editar.valor_propiedad') <span class="error text-sm text-red-500">{{ $message }}</span> @enderror

        </div>

    </div>

@endif
