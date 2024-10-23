@if($flags['tipo_tramite'])

    <div class="flex-auto bg-white p-4 rounded-lg mb-3 shadow-md">

        <div class="mb-2">

            <Label class="text-lg tracking-widest rounded-xl border-gray-500">Tipo de trámite</Label>
        </div>

        <div>

            <select class="bg-white rounded text-sm w-full" wire:model.live="modelo_editar.tipo_tramite">

                <option value="normal" selected>Normal</option>
                <option value="complemento">Cambio de tipo de servicio</option>

            </select>

        </div>

        <div>

            @error('modelo_editar.tipo_tramite') <span class="error text-sm text-red-500">{{ $message }}</span> @enderror

        </div>

    </div>

@endif
