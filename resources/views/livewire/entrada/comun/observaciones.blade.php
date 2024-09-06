<div>

    @if ($flags['observaciones'])

        <div class="flex-auto bg-white p-4 rounded-lg mb-3 shadow-md relative" wire:loading.class.delay.longest="opacity-50">

            <div class="mb-2">

                <Label class="text-lg tracking-widest rounded-xl border-gray-500">Observaciones</Label>

            </div>

            <div>

                <textarea rows="3" wire:model.lazy="modelo_editar.observaciones" class="bg-white rounded text-sm w-full"></textarea>

            </div>

            <div>

                @error('modelo_editar.observaciones') <span class="error text-sm text-red-500">{{ $message }}</span> @enderror

                @error('modelo_editar.movimiento_registral') <span class="error text-sm text-red-500">{{ $message }}</span> @enderror

            </div>

        </div>

    @endif

</div>
