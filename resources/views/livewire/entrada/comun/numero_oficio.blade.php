@if($flags['numero_oficio'])

    <div class="flex-auto bg-white p-4 rounded-lg mb-3 shadow-md">

        <div class="mb-2">

            <Label class="text-lg tracking-widest rounded-xl border-gray-500">Número de oficio</Label>
        </div>

        <div>

            <input type="text" class="bg-white rounded text-sm w-full" wire:model="modelo_editar.numero_oficio">

        </div>

        <div>

            @error('modelo_editar.numero_oficio') <span class="error text-sm text-red-500">{{ $message }}</span> @enderror

        </div>

    </div>

@endif
