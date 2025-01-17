@if($flags['movimiento_registral'])

    <div class="flex-auto bg-white p-4 rounded-lg mb-3 shadow-md justify-between">

        <div class="mb-2">

            <Label class="text-lg tracking-widest rounded-xl border-gray-500">Movimiento registral</Label>

        </div>

        <div>

            <input type="number" min="1" class="bg-white rounded text-sm w-full" wire:model.lazy="modelo_editar.asiento_registral">

        </div>

        <div>

            @error('modelo_editar.asiento_registral') <span class="error text-sm text-red-500">{{ $message }}</span> @enderror

        </div>

    </div>

@endif
