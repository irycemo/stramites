<div class="">

    <div class="mb-2 lg:mb-5">

        <x-header>Servicios</x-header>

        <div class="flex justify-between gap-3 overflow-auto p-1">

            <div class="flex gap-3">

                <input type="text" wire:model.live.debounce.500ms="search" placeholder="Buscar" class="bg-white rounded-full text-sm">

                <select class="bg-white rounded-full text-sm" wire:model.live="categoria">

                    <option value="">Seleccione una categoría</option>

                    @foreach ($categorias as $categoriaItem)

                        <option value="{{ $categoriaItem->id }}">{{ $categoriaItem->nombre }}</option>

                    @endforeach

                </select>

                <x-input-select class="bg-white rounded-full text-sm w-min" wire:model.live="pagination">

                    <option value="10">10</option>
                    <option value="25">25</option>
                    <option value="50">50</option>
                    <option value="100">100</option>

                </x-input-select>

            </div>

            @can('Crear servicio')

                <button wire:click="abrirModalCrear" class="bg-gray-500 hover:shadow-lg hover:bg-gray-700 text-sm py-2 px-4 text-white rounded-full hidden md:block items-center justify-center focus:outline-gray-400 focus:outline-offset-2">

                    <img wire:loading wire:target="abrirModalCrear" class="mx-auto h-4 mr-1" src="{{ asset('storage/img/loading3.svg') }}" alt="Loading">
                    Agregar nuevo servicio

                </button>

                <button wire:click="abrirModalCrear" class="bg-gray-500 hover:shadow-lg hover:bg-gray-700 float-right text-sm py-2 px-4 text-white rounded-full md:hidden focus:outline-gray-400 focus:outline-offset-2">+</button>

            @endcan

        </div>

    </div>

    <div class="overflow-x-auto rounded-lg shadow-xl border-t-2 border-t-gray-500">

        <div class="h-full w-full rounded-lg bg-gray-200 bg-opacity-75 absolute top-0 left-0 " wire:loading.delay.longer>

            <img class="mx-auto h-16" src="{{ asset('storage/img/loading.svg') }}" alt="Loading">

        </div>

        <x-table>

            <x-slot name="head">

                <x-table.heading sortable wire:click="sortBy('categoria_servicio_id')" :direction="$sort === 'categoria_servicio_id' ? $direction : null">Categoría</x-table.heading>
                <x-table.heading sortable wire:click="sortBy('nombre')" :direction="$sort === 'nombre' ? $direction : null">Nombre</x-table.heading>
                <x-table.heading sortable wire:click="sortBy('estado')" :direction="$sort === 'estado' ? $direction : null">Estado</x-table.heading>
                <x-table.heading sortable wire:click="sortBy('tipo')" :direction="$sort === 'tipo' ? $direction : null">Tipo</x-table.heading>
                <x-table.heading sortable wire:click="sortBy('clave_ingreso')" :direction="$sort === 'clave_ingreso' ? $direction : null">Clave de ingreso</x-table.heading>
                <x-table.heading sortable wire:click="sortBy('ordinario')" :direction="$sort === 'ordinario' ? $direction : null">Ordinario</x-table.heading>
                <x-table.heading sortable wire:click="sortBy('urgente')" :direction="$sort === 'urgente' ? $direction : null">Urgente</x-table.heading>
                <x-table.heading sortable wire:click="sortBy('extra_urgente')" :direction="$sort === 'extra_urgente' ? $direction : null">Extra urgente</x-table.heading>
                <x-table.heading sortable wire:click="sortBy('created_at')" :direction="$sort === 'created_at' ? $direction : null">Registro</x-table.heading>
                <x-table.heading sortable wire:click="sortBy('updated_at')" :direction="$sort === 'updated_at' ? $direction : null">Actualizado</x-table.heading>
                <x-table.heading >Acciones</x-table.heading>

            </x-slot>

            <x-slot name="body">

                @forelse ($this->servicios as $servicio)

                    <x-table.row wire:loading.class.delaylongest="opacity-50" wire:key="row-{{ $servicio->id }}">

                        <x-table.cell>

                            <span class="lg:hidden absolute top-0 left-0 bg-blue-300 px-2 text-[10px] text-white font-bold uppercase rounded-br-xl">Categoría</span>

                            <p class="mt-2">{{ $servicio->categoria->nombre  }}</p>

                        </x-table.cell>

                        <x-table.cell>

                            <span class="lg:hidden absolute top-0 left-0 bg-blue-300 px-2 text-[10px] text-white font-bold uppercase rounded-br-xl">Nombre</span>

                            <p class="mt-2">{{ $servicio->nombre }}</p>

                        </x-table.cell>

                        <x-table.cell>

                            <span class="lg:hidden absolute top-0 left-0 bg-blue-300 px-2 text-[10px] text-white font-bold uppercase rounded-br-xl">Estado</span>

                            @if($servicio->estado == 'activo')

                                    <span class="bg-green-400 py-1 px-2 rounded-full text-white text-xs">{{ ucfirst($servicio->estado) }}</span>

                            @else

                                <span class="bg-red-400 py-1 px-2 rounded-full text-white text-xs">{{ ucfirst($servicio->estado) }}</span>

                            @endif

                        </x-table.cell>

                        <x-table.cell>

                            <span class="lg:hidden absolute top-0 left-0 bg-blue-300 px-2 text-[10px] text-white font-bold uppercase rounded-br-xl">Tipo</span>

                            {{ $servicio->tipo }}

                        </x-table.cell>

                        <x-table.cell>

                            <span class="lg:hidden  absolute top-0 left-0 bg-blue-300 px-2 text-[10px] text-white font-bold uppercase rounded-br-xl">Clave de ingreso</span>

                            {{ $servicio->clave_ingreso }}

                        </x-table.cell>

                        <x-table.cell>

                            <span class="lg:hidden absolute top-0 left-0 bg-blue-300 px-2 text-[10px] text-white font-bold uppercase rounded-br-xl">Ordinario</span>

                            ${{ $servicio->ordinario }}

                        </x-table.cell>

                        <x-table.cell>

                            <span class="lg:hidden absolute top-0 left-0 bg-blue-300 px-2 text-[10px] text-white font-bold uppercase rounded-br-xl">Urgente</span>

                            {{ $servicio->urgente > 0 ? '$' . $servicio->urgente : 'N/A'}}

                        </x-table.cell>

                        <x-table.cell>

                            <span class="lg:hidden absolute top-0 left-0 bg-blue-300 px-2 text-[10px] text-white font-bold uppercase rounded-br-xl">Extra urgente</span>

                            {{ $servicio->extra_urgente > 0 ? '$' . $servicio->extra_urgente : 'N/A'}}

                        </x-table.cell>

                        <x-table.cell>

                            <span class="lg:hidden absolute top-0 left-0 bg-blue-300 px-2 text-[10px] text-white font-bold uppercase rounded-br-xl">Registrado</span>

                            <p class="mt-2">

                                <span class="font-semibold">@if($servicio->creadoPor != null)Registrado por: {{$servicio->creadoPor->name}} @else Registro: @endif</span> <br>

                                {{ $servicio->created_at }}

                            </p>

                        </x-table.cell>

                        <x-table.cell>

                            <span class="lg:hidden absolute top-0 left-0 bg-blue-300 px-2 text-[10px] text-white font-bold uppercase rounded-br-xl">Actualizado</span>

                            <p class="mt-2">

                                <span class="font-semibold">@if($servicio->actualizadoPor != null)Actualizado por: {{$servicio->actualizadoPor->name}} @else Actualizado: @endif</span> <br>

                                {{ $servicio->updated_at }}

                            </p>

                        </x-table.cell>

                        <x-table.cell>

                            <span class="lg:hidden absolute top-0 left-0 bg-blue-300 px-2 text-[10px] text-white font-bold uppercase rounded-br-xl">Acciones</span>

                            <div class="ml-3 relative" x-data="{ open_drop_down:false }">

                                <div>

                                    <button x-on:click="open_drop_down=true" type="button" class="rounded-full focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-gray-800 focus:ring-white">

                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 12a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0ZM12.75 12a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0ZM18.75 12a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Z" />
                                        </svg>

                                    </button>

                                </div>

                                <div x-cloak x-show="open_drop_down" x-on:click="open_drop_down=false" x-on:click.away="open_drop_down=false" class="z-50 origin-top-right absolute right-0 mt-2 w-48 rounded-md shadow-lg py-1 bg-white ring-1 ring-black ring-opacity-5 focus:outline-none" role="menu" aria-orientation="vertical" aria-labelledby="user-menu">

                                    @can('Editar servicio')

                                        <button
                                            wire:click="abrirModalEditar({{ $servicio->id }})"
                                            wire:target="abrirModalEditar({{ $servicio->id }})"
                                            wire:loading.attr="disabled"
                                            class="w-full text-left block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 focus:outline-none focus:bg-gray-100"
                                            role="menuitem">
                                            Editar
                                        </button>

                                    @endcan

                                    @can('Borrar servicio')

                                        <button
                                            wire:click="abrirModalBorrar({{ $servicio->id }})"
                                            wire:target="abrirModalBorrar({{ $servicio->id }})"
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

                        <x-table.cell colspan="16">

                            <div class="bg-white text-gray-500 text-center p-5 rounded-full text-lg">

                                No hay resultados.

                            </div>

                        </x-table.cell>

                    </x-table.row>

                @endforelse

            </x-slot>

            <x-slot name="tfoot">

                <x-table.row>

                    <x-table.cell colspan="16" class="bg-gray-50">

                        {{ $this->servicios->links() }}

                    </x-table.cell>

                </x-table.row>

            </x-slot>

        </x-table>

    </div>

    <x-dialog-modal wire:model="modal">

        <x-slot name="title">

            @if($crear)
                Nuevo Servicio
            @elseif($editar)
                Editar Servicio
            @endif

        </x-slot>

        <x-slot name="content">

            <div class="flex flex-col md:flex-row justify-between gap-3 mb-3">

                <x-input-group for="modelo_editar.nombre" label="Nombre" :error="$errors->first('modelo_editar.nombre')" class="w-full">

                    <x-input-text id="modelo_editar.nombre" wire:model="modelo_editar.nombre" />

                </x-input-group>

            </div>

            <div class="flex flex-col md:flex-row justify-between gap-3 mb-3">

                <x-input-group for="modelo_editar.categoria_servicio_id" label="Categoría" :error="$errors->first('modelo_editar.categoria_servicio_id')" class="w-full">

                    <x-input-select id="modelo_editar.categoria_servicio_id" wire:model="modelo_editar.categoria_servicio_id" class="w-full">

                        <option value="">Seleccione una opción</option>

                        @foreach ($categorias as $categoria)

                            <option value="{{ $categoria->id }}">{{ $categoria->nombre }}</option>

                        @endforeach

                    </x-input-select>

                </x-input-group>

                <x-input-group for="modelo_editar.estado" label="Estado" :error="$errors->first('modelo_editar.estado')" class="w-full">

                    <x-input-select id="modelo_editar.estado" wire:model="modelo_editar.estado" class="w-full">

                        <option value="">Seleccione una opción</option>
                        <option value="activo" selected>Activo</option>
                        <option value="inactivo" selected>Inactivo</option>

                    </x-input-select>

                </x-input-group>

            </div>

            <div class="flex flex-col md:flex-row justify-between gap-3 mb-3">

                <x-input-group for="modelo_editar.material" label="Material" :error="$errors->first('modelo_editar.material')" class="w-full">

                    <x-input-text type="number" id="modelo_editar.material" wire:model="modelo_editar.material" />

                </x-input-group>

                <x-input-group for="modelo_editar.clave_ingreso" label="Clave de ingreso" :error="$errors->first('modelo_editar.clave_ingreso')" class="w-full">

                    <x-input-text type="text" id="modelo_editar.clave_ingreso" wire:model="modelo_editar.clave_ingreso" />

                </x-input-group>

            </div>

            <div class="flex flex-col md:flex-row justify-between gap-3 mb-3">

                <x-input-group for="modelo_editar.operacion_principal" label="Operación principal" :error="$errors->first('modelo_editar.operacion_principal')" class="w-full">

                    <x-input-text type="number" id="modelo_editar.operacion_principal" wire:model="modelo_editar.operacion_principal" />

                </x-input-group>

                <x-input-group for="modelo_editar.operacion_parcial" label="Operación parcial" :error="$errors->first('modelo_editar.operacion_parcial')" class="w-full">

                    <x-input-text type="number" id="modelo_editar.operacion_parcial" wire:model="modelo_editar.operacion_parcial" />

                </x-input-group>

                <x-input-group for="modelo_editar.tipo" label="Tipo" :error="$errors->first('modelo_editar.tipo')" class="w-full">

                    <x-input-select id="modelo_editar.tipo" wire:model.live="modelo_editar.tipo" class="w-full">

                        <option value="">Seleccione una opción</option>
                        <option value="fija" selected>Fija</option>
                        <option value="uma" selected>UMA</option>

                    </x-input-select>

                </x-input-group>

                @if($this->modelo_editar->tipo == 'uma')

                    <x-input-group for="modelo_editar.umas" label="UMAS" :error="$errors->first('modelo_editar.umas')" class="w-full">

                        <x-input-text type="number" id="modelo_editar.umas" wire:model.live.debounce.500ms="modelo_editar.umas" />

                    </x-input-group>

                @endif

            </div>

            <div class="flex flex-col md:flex-row justify-between gap-3 mb-3">

                <x-input-group for="modelo_editar.ordinario" label="Ordinario" :error="$errors->first('modelo_editar.ordinario')" class="w-full">

                    <x-input-text type="number" id="modelo_editar.ordinario" wire:model.live.debounce.500ms="modelo_editar.ordinario" />

                </x-input-group>

                <x-input-group for="modelo_editar.urgente" label="Urgente" :error="$errors->first('modelo_editar.urgente')" class="w-full">

                    <x-input-text type="number" id="modelo_editar.urgente" wire:model="modelo_editar.urgente" />

                </x-input-group>

                <x-input-group for="modelo_editar.extra_urgente" label="Extra urgente" :error="$errors->first('modelo_editar.extra_urgente')" class="w-full">

                    <x-input-text type="number" id="modelo_editar.extra_urgente" wire:model="modelo_editar.extra_urgente" />

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
            Eliminar Servicio
        </x-slot>

        <x-slot name="content">
            ¿Esta seguro que desea eliminar el servicio? No sera posible recuperar la información.
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
