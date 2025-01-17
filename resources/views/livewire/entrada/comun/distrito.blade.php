@if($flags['distrito'])

    <div class="flex-auto bg-white p-4 rounded-lg mb-3 shadow-md">

        <div class="mb-2">

            <Label class="text-lg tracking-widest rounded-xl border-gray-500">Distrito</Label>
        </div>

        <div>

            <select class="bg-white rounded text-sm w-full" wire:model.lazy="modelo_editar.distrito" @if($modelo_editar->folio_real != null) disabled @endif>

                <option value="" selected>Seleccione una opción</option>

                @foreach ($distritos as $key => $item)

                    <option value="{{  $key }}">{{  $item }}</option>

                @endforeach

            </select>

        </div>

        <div>

            @error('modelo_editar.distrito') <span class="error text-sm text-red-500">{{ $message }}</span> @enderror

        </div>

    </div>

@endif
