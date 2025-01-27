@if($flags['tomo'])

    <div class="flex space-x-3 bg-white p-4 rounded-lg mb-3 shadow-md">

        <div class="flex-auto">

            <div class="mb-2">

                <Label class="text-lg tracking-widest rounded-xl border-gray-500">Tomo</Label>

            </div>

            <div>

                <input type="number" class="bg-white rounded text-sm w-full" wire:model.lazy="modelo_editar.tomo" @if($modelo_editar->folio_real != null || $modelo_editar->folio_real_persona_moral != null) readonly @endif>

            </div>

            <div>

                @error('modelo_editar.tomo') <span class="error text-sm text-red-500">{{ $message }}</span> @enderror

            </div>

        </div>

        {{-- <div class="flex-auto">

            <div class="mb-2">

                <Label class="text-lg tracking-widest rounded-xl border-gray-500">Bis</Label>

            </div>

            <div>

                <x-checkbox wire:model="modelo_editar.tomo_bis"></x-checkbox>

            </div>

            <div>

                @error('modelo_editar.tomo_bis') <span class="error text-sm text-red-500">{{ $message }}</span> @enderror

            </div>

        </div> --}}

    </div>

@endif
