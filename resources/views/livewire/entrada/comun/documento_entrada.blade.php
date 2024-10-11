@if ($flags['documento'])

    <x-h4>Documento de entrada</x-h4>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-3 bg-white p-4 rounded-lg mb-3 shadow-md">

        <x-input-group for="modelo_editar.tipo_documento" label="Tipo de documento" :error="$errors->first('modelo_editar.tipo_documento')" class="w-full">

            <x-input-select id="modelo_editar.tipo_documento" wire:model.live="modelo_editar.tipo_documento" class="w-full">

                <option value="">Seleccione una opción</option>
                @foreach ($documentos_entrada as $key => $value)

                    <option value="{{ $key }}">{{ $value }}</option>

                @endforeach

            </x-input-select>

        </x-input-group>

        <x-input-group for="modelo_editar.autoridad_cargo" label="Autoridad cargo" :error="$errors->first('modelo_editar.autoridad_cargo')" class="w-full">

            <x-input-select id="modelo_editar.autoridad_cargo" wire:model.live="modelo_editar.autoridad_cargo" class="w-full">

                <option value="">Seleccione una opción</option>
                <option value="notario">Notario(a)</option>
                <option value="foraneo">Notario(a) foraneo</option>
                <option value="juez">Juez(a)</option>
                <option value="funcionario">Funcionario</option>
                <option value="servidor_público">Servidor Público</option>

            </x-input-select>

        </x-input-group>

        @if($modelo_editar->autoridad_cargo == 'notario')

            <x-input-group for="modelo_editar.nombre_autoridad" label="Nombre de la autoridad" :error="$errors->first('modelo_editar.nombre_autoridad')" class="w-full">

                <x-input-select id="modelo_editar.nombre_autoridad" wire:model="modelo_editar.nombre_autoridad" class="w-full">

                    <option value="">Seleccione una opción</option>
                    @foreach ($notarias as $notario)

                        <option value="{{ $notario->numero . '-' . $notario->notario }}">{{ $notario->numero . '-' . $notario->notario }}</option>

                    @endforeach

                </x-input-select>

            </x-input-group>

        @else

            <x-input-group for="modelo_editar.nombre_autoridad" label="Nombre de la autoridad" :error="$errors->first('modelo_editar.nombre_autoridad')" class="w-full">

                <x-input-text id="modelo_editar.nombre_autoridad" wire:model="modelo_editar.nombre_autoridad" />

            </x-input-group>

        @endif

        <x-input-group for="modelo_editar.numero_documento" label="{{ $labelNumeroDocumento }}" :error="$errors->first('modelo_editar.numero_documento')" class="w-full">

            <x-input-text id="modelo_editar.numero_documento" wire:model="modelo_editar.numero_documento" />

        </x-input-group>

        <x-input-group for="modelo_editar.fecha_emision" label="Fecha de emisión" :error="$errors->first('modelo_editar.fecha_emision')" class="w-full">

            <x-input-text type="date" id="modelo_editar.fecha_emision" wire:model="modelo_editar.fecha_emision" />

        </x-input-group>

        <x-input-group for="modelo_editar.procedencia" label="Dependencia" :error="$errors->first('modelo_editar.procedencia')" class="w-full">

            <x-input-text id="modelo_editar.procedencia" wire:model="modelo_editar.procedencia" />

        </x-input-group>

    </div>

@endif
