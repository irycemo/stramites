@if($flags['folio_real'])

    <div class="flex-auto bg-white p-4 rounded-lg mb-3 shadow-md justify-between">

        <div class="mb-2">

            <Label class="text-lg tracking-widest rounded-xl border-gray-500">Folio real {{ $servicio['clave_ingreso'] == 'D153' ? 'reestructura' : '' }}</Label>

        </div>

        <div>

            <input type="number" min="1" class="bg-white rounded text-sm w-full" wire:model.lazy="folio_real_extra">

        </div>

        <div>

            @error('folio_real_extra') <span class="error text-sm text-red-500">{{ $message }}</span> @enderror

        </div>

    </div>

@endif
