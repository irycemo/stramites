@if ($flags['solicitante'])

    <div class="flex-row lg:flex lg:space-x-3">

        <div class="flex-auto bg-white p-3 rounded-lg mb-3 shadow-md">

            <div class="flex-auto ">

                <div class="mb-2">

                    <Label class="text-lg tracking-widest rounded-xl border-gray-500">Solicitante</Label>

                </div>

                <div>

                    <select class="bg-white rounded text-sm w-full" wire:model.live="modelo_editar.solicitante">

                        <option value="" selected>Seleccione una opción</option>

                        @foreach ($solicitantes as $solicitante)

                            <option value="{{ $solicitante }}">{{ $solicitante }}</option>

                        @endforeach

                    </select>

                </div>

                <div>

                    @error('modelo_editar.solicitante') <span class="error text-sm text-red-500">{{ $message }}</span> @enderror

                </div>

            </div>

        </div>

        @if ($flags['nombre_solicitante'])

            <div class="flex-auto bg-white p-3 rounded-lg mb-3 shadow-md">

                <div class="mb-2">

                    <Label class="text-lg tracking-widest rounded-xl border-gray-500">Nombre del solicitante</Label>

                </div>

                <div>

                    <input type="text" class="bg-white rounded text-sm w-full" wire:model.lazy="modelo_editar.nombre_solicitante">

                </div>

                <div>

                    @error('modelo_editar.nombre_solicitante') <span class="error text-sm text-red-500">{{ $message }}</span> @enderror

                </div>

            </div>

        @endif

        @if ($flags['dependencias'])

            <div class="flex-auto bg-white p-3 rounded-lg mb-3 shadow-md">

                <div class="mb-2">

                    <Label class="text-lg tracking-widest rounded-xl border-gray-500">Dependencia</Label>

                </div>

                <div>

                    <select class="bg-white rounded text-sm w-full" wire:model.live="modelo_editar.nombre_solicitante">

                        <option value="" selected>Seleccione una opción</option>

                        @foreach ($dependencias as $item)

                            <option value="{{ $item->nombre }}">{{ $item->nombre }}</option>

                        @endforeach

                    </select>

                </div>

                <div>

                    @error('modelo_editar.nombre_solicitante') <span class="error text-sm text-red-500">{{ $message }}</span> @enderror

                </div>

            </div>

        @endif

        @if ($flags['notarias'])

            <div class="flex-auto bg-white p-3 rounded-lg mb-3 shadow-md">

                <div class="mb-2">

                    <Label class="text-lg tracking-widest rounded-xl border-gray-500">Notaria</Label>

                </div>

                <div>

                    <select class="bg-white rounded text-sm w-full" wire:model.live="notaria">

                        <option value="" selected>Seleccione una opción</option>

                        @foreach ($notarias as $item)

                            <option value="{{ $item }}">{{ $item->numero }} - {{ $item->notario }}</option>

                        @endforeach

                    </select>

                </div>

                <div>

                    @error('modelo_editar.nombre_solicitante') <span class="error text-sm text-red-500">{{ $message }}</span> @enderror

                </div>

            </div>

        @endif

    </div>

@endif
