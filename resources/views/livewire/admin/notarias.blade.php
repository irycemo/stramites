<div class="">

    <div class="mb-6">

        <x-header>Notarias</x-header>

        <div class="flex justify-between">

            <div class="flex gap-3">

                <input type="text" wire:model.live.debounce.500ms="search" placeholder="Buscar" class="bg-white rounded-full text-sm">

                <x-input-select class="bg-white rounded-full text-sm w-min" wire:model.live="pagination">

                    <option value="10">10</option>
                    <option value="25">25</option>
                    <option value="50">50</option>
                    <option value="100">100</option>

                </x-input-select>

            </div>

            @can('Crear notaría')

                <button wire:click="abrirModalCrear" class="bg-gray-500 hover:shadow-lg hover:bg-gray-700 text-sm py-2 px-4 text-white rounded-full hidden md:block items-center justify-center focus:outline-gray-400 focus:outline-offset-2">

                    <img wire:loading wire:target="abrirModalCrear" class="mx-auto h-4 mr-1" src="{{ asset('storage/img/loading3.svg') }}" alt="Loading">
                    Agregar nueva notaria

                </button>

                <button wire:click="abrirModalCrear" class="bg-gray-500 hover:shadow-lg hover:bg-gray-700 float-right text-sm py-2 px-4 text-white rounded-full md:hidden focus:outline-gray-400 focus:outline-offset-2">+</button>

            @endcan

        </div>

    </div>

    <div class="overflow-x-auto rounded-lg shadow-xl border-t-2 border-t-gray-500">

        <x-table>

            <x-slot name="head">

                <x-table.heading sortable wire:click="sortBy('numero')" :direction="$sort === 'numero' ? $direction : null" >Número</x-table.heading>
                <x-table.heading sortable wire:click="sortBy('notario')" :direction="$sort === 'notario' ? $direction : null" >Notario</x-table.heading>
                <x-table.heading sortable wire:click="sortBy('email')" :direction="$sort === 'email' ? $direction : null" >Email</x-table.heading>
                <x-table.heading sortable wire:click="sortBy('rfc')" :direction="$sort === 'rfc' ? $direction : null" >RFC</x-table.heading>
                <x-table.heading sortable wire:click="sortBy('created_at')" :direction="$sort === 'created_at' ? $direction : null">Registro</x-table.heading>
                <x-table.heading sortable wire:click="sortBy('updated_at')" :direction="$sort === 'updated_at' ? $direction : null">Actualizado</x-table.heading>
                <x-table.heading >Acciones</x-table.heading>

            </x-slot>

            <x-slot name="body">

                @forelse ($notarias as $notario)

                    <x-table.row wire:loading.class.delaylongest="opacity-50" wire:key="row-{{ $notario->id }}">

                        <x-table.cell title="Número">

                            {{ $notario->numero }}

                        </x-table.cell>

                        <x-table.cell title="Notario">

                            {{ $notario->notario }}

                        </x-table.cell>

                        <x-table.cell title="Emial">

                            {{ $notario->email }}

                        </x-table.cell>

                        <x-table.cell title="RFC">

                            {{ $notario->rfc }}

                        </x-table.cell>

                        <x-table.cell title="Registrado">

                            <span class="font-semibold">@if($notario->creadoPor != null)Registrado por: {{$notario->creadoPor->name}} @else Registro: @endif</span> <br>

                            {{ $notario->created_at }}

                        </x-table.cell>

                        <x-table.cell title="Actualizado">

                            <span class="font-semibold">@if($notario->actualizadoPor != null)Actualizado por: {{$notario->actualizadoPor->name}} @else Actualizado: @endif</span> <br>

                            {{ $notario->updated_at }}

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

                                    @can('Editar notaría')

                                        <button
                                            wire:click="abrirModalEditar({{ $notario->id }})"
                                            wire:target="abrirModalEditar({{ $notario->id }})"
                                            wire:loading.attr="disabled"
                                            class="w-full text-left block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 focus:outline-none focus:bg-gray-100"
                                            role="menuitem">
                                            Editar
                                        </button>

                                    @endcan

                                    @can('Borrar notaría')

                                        <button
                                            wire:click="abrirModalBorrar({{ $notario->id }})"
                                            wire:target="abrirModalBorrar({{ $notario->id }})"
                                            wire:loading.attr="disabled"
                                            class="w-full text-left block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 focus:outline-none focus:bg-gray-100"
                                            role="menuitem">
                                            Borrar
                                        </button>

                                    @endcan

                                </div>

                            </div>

                        </x-table.cell>

                    </x-table.row>

                @empty

                    <x-table.row>

                        <x-table.cell colspan="9">

                            <div class="bg-white text-gray-500 text-center p-5 rounded-full text-lg">

                                No hay resultados.

                            </div>

                        </x-table.cell>

                    </x-table.row>

                @endforelse

            </x-slot>

            <x-slot name="tfoot">

                <x-table.row>

                    <x-table.cell colspan="9" class="bg-gray-50">

                        {{ $notarias->links() }}

                    </x-table.cell>

                </x-table.row>

            </x-slot>

        </x-table>

    </div>

    <x-dialog-modal wire:model="modal" maxWidth="sm">

        <x-slot name="title">

            @if($crear)
                Nuevo Notario
            @elseif($editar)
                Editar Notario
            @endif

        </x-slot>

        <x-slot name="content">

            <div class="flex flex-col md:flex-row justify-between gap-3 mb-3">

                <x-input-group for="modelo_editar.numero" label="Número" :error="$errors->first('modelo_editar.numero')" class="w-full">

                    <x-input-text type="number" id="modelo_editar.numero" wire:model="modelo_editar.numero" />

                </x-input-group>

            </div>

            <div class="flex flex-col md:flex-row justify-between gap-3 mb-3">

                <x-input-group for="modelo_editar.notario" label="Notario" :error="$errors->first('modelo_editar.notario')" class="w-full">

                    <x-input-text id="modelo_editar.notario" wire:model="modelo_editar.notario" />

                </x-input-group>

            </div>

            <div class="flex flex-col md:flex-row justify-between gap-3 mb-3">

                <x-input-group for="modelo_editar.email" label="Email" :error="$errors->first('modelo_editar.email')" class="w-full">

                    <x-input-text type="email" id="modelo_editar.email" wire:model="modelo_editar.email" />

                </x-input-group>

            </div>

            <div class="flex flex-col md:flex-row justify-between gap-3 mb-3">

                <x-input-group for="modelo_editar.rfc" label="RFC" :error="$errors->first('modelo_editar.rfc')" class="w-full">

                    <x-input-text id="modelo_editar.rfc" wire:model="modelo_editar.rfc" />

                </x-input-group>

            </div>

        </x-slot>

        <x-slot name="footer">

            <div class="flex gap-3">

                @if($crear)

                    <x-button-blue
                        wire:click="guardar"
                        wire:loading.attr="disabled"
                        wire:target="guardar">

                        <img wire:loading wire:target="guardar" class="mx-auto h-4 mr-1" src="{{ asset('storage/img/loading3.svg') }}" alt="Loading">

                        <span>Guardar</span>
                    </x-button-blue>

                @elseif($editar)

                    <x-button-blue
                        wire:click="actualizar"
                        wire:loading.attr="disabled"
                        wire:target="actualizar">

                        <img wire:loading wire:target="actualizar" class="mx-auto h-4 mr-1" src="{{ asset('storage/img/loading3.svg') }}" alt="Loading">

                        <span>Actualizar</span>
                    </x-button-blue>

                @endif

                <x-button-red
                    wire:click="resetearTodo"
                    wire:loading.attr="disabled"
                    wire:target="resetearTodo"
                    type="button">
                    Cerrar
                </x-button-red>

            </div>

        </x-slot>

    </x-dialog-modal>

    <x-confirmation-modal wire:model="modalBorrar" maxWidth="sm">

        <x-slot name="title">
            Eliminar notaría
        </x-slot>

        <x-slot name="content">
            ¿Esta seguro que desea eliminar la notaría? No sera posible recuperar la notaría.
        </x-slot>

        <x-slot name="footer">

            <x-secondary-button
                wire:click="$toggle('modalBorrar')"
                wire:loading.attr="disabled"
            >
                No
            </x-secondary-button>

            <x-danger-button
                class="ml-2"
                wire:click="borrar"
                wire:loading.attr="disabled"
                wire:target="borrar"
            >
                Borrar
            </x-danger-button>

        </x-slot>

    </x-confirmation-modal>

</div>
