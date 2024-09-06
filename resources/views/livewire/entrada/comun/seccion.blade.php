@if($flags['seccion'])

    <div class="flex-auto bg-white p-4 rounded-lg mb-3 shadow-md">

        <div class="mb-2">

            <Label class="text-lg tracking-widest rounded-xl border-gray-500">Sección</Label>

        </div>

        <div>

            <select class="bg-white rounded text-sm w-full" wire:model.lazy="modelo_editar.seccion">

                <option value="" selected>Seleccione una opción</option>

                @foreach ($secciones as $seccion)

                    <option value="{{ $seccion }}">{{ $seccion }}</option>

                @endforeach

            </select>

        </div>

        <div>

            @error('modelo_editar.seccion') <span class="error text-sm text-red-500">{{ $message }}</span> @enderror

        </div>

    </div>

@endif
