@if($flags['registro'])

    <div class="flex space-x-3 bg-white p-4 rounded-lg mb-3 shadow-md">

        <div class="flex-auto">

            <div class="mb-2">

                <Label class="text-lg tracking-widest rounded-xl border-gray-500">Registro</Label>

            </div>

            <div>

                <input type="number" class="bg-white rounded text-sm w-full" wire:model.lazy="modelo_editar.registro" @if($modelo_editar->folio_real != null || $modelo_editar->folio_real_persona_moral != null) readonly @endif>

            </div>

            <div>

                @error('modelo_editar.registro') <span class="error text-sm text-red-500">{{ $message }}</span> @enderror

            </div>

        </div>

        {{-- <div class="flex-auto" >

            <div class="mb-2">

                <Label class="text-lg tracking-widest rounded-xl border-gray-500">Bis</Label>

            </div>

            <div>

                <x-checkbox wire:model="modelo_editar.registro_bis"></x-checkbox>

            </div>

            <div>

                @error('modelo_editar.registro_bis') <span class="error text-sm text-red-500">{{ $message }}</span> @enderror

            </div>

        </div> --}}

    </div>

@endif
