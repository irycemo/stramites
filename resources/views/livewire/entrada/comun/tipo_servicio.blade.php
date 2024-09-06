@if($flags['tipo_servicio'])

    <div class="flex-auto bg-white p-4 rounded-lg mb-3 shadow-md">

        <div class="mb-2">

            <Label class="text-lg tracking-widest rounded-xl border-gray-500">Tipo de servicio</Label>
        </div>

        <div>

            <select class="bg-white rounded text-sm w-full" wire:model.live="modelo_editar.tipo_servicio">

                <option value="" selected>Seleccione una opción</option>
                <option value="ordinario">Ordinario</option>
                <option value="urgente">Urgente</option>
                <option value="extra_urgente">Extra Urgente</option>

            </select>

        </div>

        <div>

            @error('modelo_editar.tipo_servicio') <span class="error text-sm text-red-500">{{ $message }}</span> @enderror

        </div>

    </div>

@endif
