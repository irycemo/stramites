<div class="">

    <div class="mb-6">

        <x-header>Recepción</x-header>

        <div class="flex justify-between">

            <div class="flex lg:w-1/4">

                <select class="bg-white rounded-l text-sm border border-r-transparent  focus:ring-0 @error('año') border-red-500 @enderror" wire:model="año">
                    @foreach ($años as $año)

                        <option value="{{ $año }}">{{ $año }}</option>

                    @endforeach
                </select>

                <input type="number" placeholder="# de control" min="1" class="bg-white text-sm w-full focus:ring-0 border-r-transparent @error('numero_control') border-red-500 @enderror" wire:model="numero_control">

                <input type="number" placeholder="Usuario" min="1" class="bg-white text-sm w-full focus:ring-0 @error('usuario') border-red-500 @enderror" wire:model="usuario">

                <button
                    wire:click="consultar"
                    wire:loading.attr="disabled"
                    wire:target="consultar"
                    type="button"
                    class="bg-blue-400 hover:shadow-lg text-white font-bold px-4 rounded-r text-sm hover:bg-blue-700 focus:outline-blue-400 focus:outline-offset-2">

                    <img wire:loading wire:target="consultar" class="mx-auto h-4 mr-1" src="{{ asset('storage/img/loading3.svg') }}" alt="Loading">

                    <svg wire:loading.remove wire:target="consultar" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" />
                    </svg>

                </button>

            </div>

        </div>

    </div>

    @if ($tramite)

        <div class="rounded-lg shadow-xl border-t-2 border-t-gray-500">

            <x-table>

                <x-slot name="head">

                    <x-table.heading sortable wire:click="sortBy('numero_control')" :direction="$sort === 'numero_control' ? $direction : null">Número de control</x-table.heading>
                    <x-table.heading sortable wire:click="sortBy('id_servicio')" :direction="$sort === 'id_servicio' ? $direction : null">Servicio</x-table.heading>
                    <x-table.heading sortable wire:click="sortBy('solicitante')" :direction="$sort === 'solicitante' ? $direction : null">Solicitante</x-table.heading>
                    <x-table.heading sortable wire:click="sortBy('folio_real')" :direction="$sort === 'folio_real' ? $direction : null">Folio real</x-table.heading>
                    <x-table.heading sortable wire:click="sortBy('tipo_servicio')" :direction="$sort === 'tipo_servicio' ? $direction : null">Tipo servicio</x-table.heading>
                    <x-table.heading sortable wire:click="sortBy('estado')" :direction="$sort === 'estado' ? $direction : null">Estado</x-table.heading>
                    <x-table.heading >Observaciones</x-table.heading>
                    <x-table.heading sortable wire:click="sortBy('created_at')" :direction="$sort === 'created_at' ? $direction : null">Registro</x-table.heading>
                    <x-table.heading sortable wire:click="sortBy('updated_at')" :direction="$sort === 'updated_at' ? $direction : null">Actualizado</x-table.heading>
                    <x-table.heading >Acciones</x-table.heading>

                </x-slot>

                <x-slot name="body">

                    <x-table.row wire:loading.class.delaylongest="opacity-50">

                        <x-table.cell title="Número de control">

                            {{ $tramite->año }}-{{ $tramite->numero_control }}-{{ $tramite->usuario }}

                        </x-table.cell>

                        <x-table.cell title="Servicio">

                            {{ $tramite->servicio->nombre }}

                        </x-table.cell>

                        <x-table.cell title="Solicitante">

                            {{ $tramite->solicitante }}

                            {{ $tramite->nombre_solicitante ? '/ ' . $tramite->nombre_solicitante : ''}}

                        </x-table.cell>

                        <x-table.cell title="Folio real">

                            {{ $tramite->folio_real ?? 'N/A' }}

                        </x-table.cell>

                        <x-table.cell title="Tipo servicio">

                            {{ $tramite->tipo_servicio }}

                        </x-table.cell>

                        <x-table.cell title="Estado">

                            <span class="bg-{{ $tramite->estado_color }} py-1 px-2 rounded-full text-white text-xs">{{ ucfirst($tramite->estado) }}</span>

                        </x-table.cell>

                        <x-table.cell title="Observaciones">

                            {{ $tramite->observaciones }}

                        </x-table.cell>

                        <x-table.cell title="Registrado">

                            <span class="font-semibold">@if($tramite->creadoPor != null)Registrado por: {{$tramite->creadoPor->name}} @else Registro: @endif</span> <br>

                            {{ $tramite->created_at }}

                        </x-table.cell>

                        <x-table.cell title="Actualizado">

                            <span class="font-semibold">@if($tramite->actualizadoPor != null)Actualizado por: {{$tramite->actualizadoPor->name}} @else Actualizado: @endif</span> <br>

                            {{ $tramite->updated_at }}

                        </x-table.cell>

                        <x-table.cell title="Acciones">

                            <div class="ml-3 relative" x-data="{ open_drop_down:false }">

                                <div>

                                    <button x-on:click="open_drop_down=true" type="button" class="rounded-full focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-gray-800 focus:ring-white">

                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 12a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0ZM12.75 12a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0ZM18.75 12a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Z" />
                                        </svg>

                                    </button>

                                </div>

                                <div x-cloak x-show="open_drop_down" x-on:click="open_drop_down=false" x-on:click.away="open_drop_down=false" class="z-50 origin-top-right absolute right-0 mt-2 w-48 rounded-md shadow-lg py-1 bg-white ring-1 ring-black ring-opacity-5 focus:outline-none" role="menu" aria-orientation="vertical" aria-labelledby="user-menu">

                                    <button
                                        wire:click="abrirModalEditar({{ $tramite->id }})"
                                        wire:loading.attr="disabled"
                                        class="w-full text-left block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 focus:outline-none focus:bg-gray-100"
                                        role="menuitem">
                                        Subir Archivo
                                    </button>

                                    <button
                                        wire:click="recibir"
                                        wire:loading.attr="disabled"
                                        class="w-full text-left block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 focus:outline-none focus:bg-gray-100"
                                        role="menuitem">
                                        Recibir
                                    </button>

                                </div>

                            </div>

                        </x-table.cell>

                    </x-table.row>

                </x-slot>

                <x-slot name="tfoot">

                    <x-table.row>

                    </x-table.row>

                </x-slot>

            </x-table>

        </div>

    @else

        <div class="bg-white text-gray-500 text-center p-5 rounded-full text-lg">

            No hay resultados.

        </div>

    @endif

    <x-dialog-modal wire:model="modal" maxWidth="sm">

        <x-slot name="title">

            Subir archivo

        </x-slot>

        <x-slot name="content">

            <x-filepond wire:model.live="documento" accept="['application/pdf']"/>

            <div>

                @error('documento') <span class="error text-sm text-red-500">{{ $message }}</span> @enderror

            </div>

        </x-slot>

        <x-slot name="footer">

            <div class="flex gap-3">

                <x-button-blue
                    wire:click="guardar"
                    wire:loading.attr="disabled"
                    wire:target="guardar">

                    <img wire:loading wire:target="guardar" class="mx-auto h-4 mr-1" src="{{ asset('storage/img/loading3.svg') }}" alt="Loading">

                    <span>Guardar</span>

                </x-button-blue>

                <x-button-red
                    wire:click="$toggle('modal')"
                    wire:loading.attr="disabled"
                    wire:target="$toggle('modal')"
                    type="button">

                    <span>Cerrar</span>

                </x-button-red>

            </div>

        </x-slot>

    </x-dialog-modal>

</div>
