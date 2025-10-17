<div class="">

    <div class="mb-6">

        <x-header>Trámites</x-header>

        <div class="flex justify-between">

            <div class="flex gap-3 overflow-auto p-1">

                <x-input-select class="bg-white rounded-full text-sm w-min" wire:model.live="filters.año">

                    <option value="" selected>Año</option>
                    @foreach ($años as $año)
                        <option value="{{ $año }}">{{ $año }}</option>
                    @endforeach

                </x-input-select>

                <input type="text" wire:model.live.debounce.500ms="filters.folio" placeholder="folio" class="bg-white rounded-full text-sm w-20">

                <input type="text" wire:model.live.debounce.500ms="filters.usuario" placeholder="Usuario" class="bg-white rounded-full text-sm w-20">

                <x-input-select class="bg-white rounded-full text-sm w-min" wire:model.live="filters.estado">

                    <option value="" selected>Seleccione una opción</option>
                    <option value="nuevo">Nuevo</option>
                    <option value="caducado">Caducado</option>
                    <option value="rechazado">Rechazado</option>
                    <option value="pagado">Pagado</option>
                    <option value="finalizado">Finalizado</option>
                    <option value="recibido">Recibido</option>

                </x-input-select>

                <x-input-select class="bg-white rounded-full text-sm w-20" wire:model.live="filters.categoria">

                    <option value="" selected>Categoría</option>
                    @foreach ($categorias as $categoria)
                        <option value="{{ $categoria->id }}">{{ $categoria->nombre }}</option>
                    @endforeach

                </x-input-select>

                <x-input-select class="bg-white rounded-full text-sm w-10" wire:model.live="filters.servicio">

                    <option value="" selected>Servicio</option>
                    @foreach ($servicios as $servicio)
                        <option value="{{ $servicio->id }}">{{ $servicio->nombre }}</option>
                    @endforeach

                </x-input-select>

                <x-input-select class="bg-white rounded-full text-sm w-28" wire:model.live="filters.regional">

                    <option value="" selected>Regional</option>
                    @foreach ($regionales as $regional)
                        <option value="{{ $regional }}">{{ $regional }}</option>
                    @endforeach

                </x-input-select>

                <input type="text" wire:model.live.debounce.500ms="search" placeholder="Buscar" class="bg-white rounded-full text-sm">

                <x-input-select class="bg-white rounded-full text-sm w-min" wire:model.live="pagination">

                    <option value="10">10</option>
                    <option value="25">25</option>
                    <option value="50">50</option>
                    <option value="100">100</option>

                </x-input-select>

            </div>

            <div class="ml-3 relative z-50" x-data="{ open_drop_down:false }">

                <div>

                    <button x-on:click="open_drop_down=true" x-on:click.away="open_drop_down=false" type="button" class="bg-gray-500 hover:shadow-lg hover:bg-gray-700 text-sm p-2 text-white rounded-full hidden md:block items-center justify-center focus:outline-gray-400 focus:outline-offset-2" id="tramites-menu" aria-expanded="false" aria-haspopup="true">

                        <span class="sr-only">Abrir menú de tramites</span>

                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 5.25 7.5 7.5 7.5-7.5m-15 6 7.5 7.5 7.5-7.5" />
                        </svg>

                    </button>

                </div>

                <div x-show="open_drop_down" x-on:click="open_drop_down=false" class="origin-top-right absolute right-0 mt-2 w-48 rounded-md shadow-lg py-1 bg-white ring-1 ring-black ring-opacity-5 focus:outline-none focus:outline-offset-2" role="menu" aria-orientation="vertical" aria-labelledby="tramites-menu">

                    <button type="button" wire:click="desactivarEntrada" class="w-full text-left block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 focus:outline-none" role="menuitem">Habilitar / deshabilitar entrada</button>

                </div>

            </div>

        </div>

    </div>

    <div class="overflow-x-auto rounded-lg shadow-xl border-t-2 border-t-gray-500">

        <x-table>

            <x-slot name="head">

                <x-table.heading sortable wire:click="sortBy('año')" :direction="$sort === 'año' ? $direction : null">Año</x-table.heading>
                <x-table.heading sortable wire:click="sortBy('numero_control')" :direction="$sort === 'numero_control' ? $direction : null">Número de Control</x-table.heading>
                <x-table.heading sortable wire:click="sortBy('usuario')" :direction="$sort === 'usuario' ? $direction : null">Usuario</x-table.heading>
                <x-table.heading sortable wire:click="sortBy('estado')" :direction="$sort === 'estado' ? $direction : null">Estado</x-table.heading>
                <x-table.heading >Categoría</x-table.heading>
                <x-table.heading sortable wire:click="sortBy('id_servicio')" :direction="$sort === 'id_servicio' ? $direction : null">Servicio</x-table.heading>
                <x-table.heading sortable wire:click="sortBy('solicitante')" :direction="$sort === 'solicitante' ? $direction : null">Solicitante</x-table.heading>
                <x-table.heading sortable wire:click="sortBy('folio_real')" :direction="$sort === 'folio_real' ? $direction : null">Folio real</x-table.heading>
                <x-table.heading sortable wire:click="sortBy('tomo')" :direction="$sort === 'tomo' ? $direction : null">Tomo</x-table.heading>
                <x-table.heading sortable wire:click="sortBy('registro')" :direction="$sort === 'registro' ? $direction : null">Registro</x-table.heading>
                <x-table.heading sortable wire:click="sortBy('monto')" :direction="$sort === 'monto' ? $direction : null">Monto</x-table.heading>
                <x-table.heading sortable wire:click="sortBy('tipo_servicio')" :direction="$sort === 'tipo_servicio' ? $direction : null">Tipo de servicio</x-table.heading>
                <x-table.heading sortable wire:click="sortBy('created_at')" :direction="$sort === 'created_at' ? $direction : null">Registro</x-table.heading>
                <x-table.heading sortable wire:click="sortBy('updated_at')" :direction="$sort === 'updated_at' ? $direction : null">Actualizado</x-table.heading>
                <x-table.heading >Acciones</x-table.heading>

            </x-slot>

            <x-slot name="body">

                @forelse ($tramites as $tramite)

                    <x-table.row  wire:loading.class.delaylongest="opacity-50" wire:key="row-{{ $tramite->id }}">

                        <x-table.cell>

                            <span class="lg:hidden absolute top-0 left-0 bg-blue-300 px-2 py-1 text-xs text-white font-bold uppercase rounded-br-xl">Año</span>

                            {{ $tramite->año }}

                        </x-table.cell>

                        <x-table.cell>

                            <span class="lg:hidden absolute top-0 left-0 bg-blue-300 px-2 py-1 text-xs text-white font-bold uppercase rounded-br-xl">Número de Control</span>

                            {{ $tramite->numero_control }}

                        </x-table.cell>

                        <x-table.cell>

                            <span class="lg:hidden absolute top-0 left-0 bg-blue-300 px-2 py-1 text-xs text-white font-bold uppercase rounded-br-xl">Usuario</span>

                            {{ $tramite->usuario }}

                        </x-table.cell>

                        <x-table.cell>

                            <span class="lg:hidden absolute top-0 left-0 bg-blue-300 px-2 py-1 text-xs text-white font-bold uppercase rounded-br-xl">Estado</span>

                            <span class="bg-{{ $tramite->estado_color }} py-1 px-2 rounded-full text-white text-xs">{{ ucfirst($tramite->estado) }}</span>

                        </x-table.cell>

                        <x-table.cell>

                            <span class="lg:hidden absolute top-0 left-0 bg-blue-300 px-2 py-1 text-xs text-white font-bold uppercase rounded-br-xl">Categoría</span>

                            {{ $tramite->servicio->categoria->nombre }}

                        </x-table.cell>

                        <x-table.cell>

                            <span class="lg:hidden absolute top-0 left-0 bg-blue-300 px-2 py-1 text-xs text-white font-bold uppercase rounded-br-xl">Servicio</span>

                            {{ $tramite->servicio->nombre }}

                        </x-table.cell>

                        <x-table.cell>

                            <span class="lg:hidden  absolute top-0 left-0 bg-blue-300 px-2 py-1 text-xs text-white font-bold uppercase rounded-br-xl">Solicitante</span>

                            {{ $tramite->solicitante }}

                                {{ $tramite->nombre_solicitante ? '/ ' . $tramite->nombre_solicitante : ''}}

                        </x-table.cell>

                        <x-table.cell>

                            <span class="lg:hidden  absolute top-0 left-0 bg-blue-300 px-2 py-1 text-xs text-white font-bold uppercase rounded-br-xl">Folio real</span>

                            {{ $tramite->folio_real ? $tramite->folio_real : 'N/A' }}

                        </x-table.cell>

                        <x-table.cell>

                            <span class="lg:hidden absolute top-0 left-0 bg-blue-300 px-2 py-1 text-xs text-white font-bold uppercase rounded-br-xl">Tomo</span>

                            {{ $tramite->tomo ? $tramite->tomo : 'N/A' }} {{ $tramite->tomo_bis ? '/ Bis' : ''}}

                        </x-table.cell>

                        <x-table.cell>

                            <span class="lg:hidden absolute top-0 left-0 bg-blue-300 px-2 py-1 text-xs text-white font-bold uppercase rounded-br-xl">Registro</span>

                            {{ $tramite->registro ? $tramite->registro : 'N/A' }} {{ $tramite->registro_bis ? '/ Bis' : ''}}

                        </x-table.cell>

                        <x-table.cell>

                            <span class="lg:hidden absolute top-0 left-0 bg-blue-300 px-2 py-1 text-xs text-white font-bold uppercase rounded-br-xl">Monto</span>

                            ${{ number_format($tramite->monto, 2) }}

                        </x-table.cell>

                        <x-table.cell>

                            <span class="lg:hidden absolute top-0 left-0 bg-blue-300 px-2 py-1 text-xs text-white font-bold uppercase rounded-br-xl">Tipo de servicio</span>

                            {{ $tramite->tipo_servicio }}

                        </x-table.cell>

                        <x-table.cell>

                            <span class="lg:hidden absolute top-0 left-0 bg-blue-300 px-2 py-1 text-xs text-white font-bold uppercase rounded-br-xl">Registrado</span>


                            <span class="font-semibold">@if($tramite->creadoPor != null)Registrado por: {{$tramite->creadoPor->name}} @else Registro: @endif</span> <br>

                            {{ $tramite->created_at }}

                        </x-table.cell>

                        <x-table.cell>

                            <span class="lg:hidden absolute top-0 left-0 bg-blue-300 px-2 py-1 text-xs text-white font-bold uppercase rounded-br-xl">Actualizado</span>

                            <span class="font-semibold">@if($tramite->actualizadoPor != null)Actualizado por: {{$tramite->actualizadoPor->name}} @else Actualizado: @endif</span> <br>

                            {{ $tramite->updated_at }}

                        </x-table.cell>

                        <x-table.cell>

                            <span class="lg:hidden absolute top-0 left-0 bg-blue-300 px-2 py-1 text-xs text-white font-bold uppercase rounded-br-xl">Acciones</span>

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
                                        wire:click="abrirModalVer({{ $tramite->id }})"
                                        wire:loading.attr="disabled"
                                        class="w-full text-left block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 focus:outline-none focus:bg-gray-100"
                                        role="menuitem">
                                        Ver
                                    </button>

                                    @can('Editar trámite')

                                        <button
                                            wire:click="abrirModalEditar({{ $tramite->id }})"
                                            wire:loading.attr="disabled"
                                            class="w-full text-left block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 focus:outline-none focus:bg-gray-100"
                                            role="menuitem">
                                            Editar
                                        </button>

                                    @endcan

                                    @if(!$tramite->fecha_pago)

                                        @can('Acreditar trámite')

                                            <button
                                                wire:click="abrirModalAcreditar({{ $tramite->id }})"
                                                wire:loading.attr="disabled"
                                                class="w-full text-left block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 focus:outline-none focus:bg-gray-100"
                                                role="menuitem">
                                                Acreditar pago
                                            </button>

                                        @endcan

                                    @endif

                                    @if($tramite->estado == 'nuevo')

                                        @can('Borrar trámite')

                                            <button
                                                wire:click="abrirModalBorrar({{$tramite->id}})"
                                                wire:loading.attr="disabled"
                                                class="w-full text-left block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 focus:outline-none focus:bg-gray-100"
                                                role="menuitem">
                                                Eliminar
                                            </button>

                                        @endcan

                                    @endif

                                    @if(in_array($tramite->estado, ['caducado', 'expirado']))

                                        @can('Reactivar trámite')

                                            <button
                                                wire:click="reactivarTramtie({{$tramite->id}})"
                                                wire:loading.attr="disabled"
                                                class="w-full text-left block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 focus:outline-none focus:bg-gray-100"
                                                role="menuitem">
                                                Reactivar
                                            </button>

                                        @endcan

                                    @endif

                                    <a
                                        href="{{ route('auditoria') . "?modelo=Tramite&modelo_id=" . $tramite->id }}"
                                        class="w-full text-left block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 focus:outline-none focus:bg-gray-100"
                                        role="menuitem">
                                        Auditar
                                    </a>

                                </div>

                            </div>

                        </x-table.cell>

                    </x-table.row>

                @empty

                    <x-table.row>

                        <x-table.cell colspan="15">

                            <div class="bg-white text-gray-500 text-center p-5 rounded-full text-lg">

                                No hay resultados.

                            </div>

                        </x-table.cell>

                    </x-table.row>

                @endforelse

            </x-slot>

            <x-slot name="tfoot">

                <x-table.row>

                    <x-table.cell colspan="15" class="bg-gray-50">

                        {{ $tramites->links()}}

                    </x-table.cell>

                </x-table.row>

            </x-slot>

        </x-table>

    </div>

    <x-dialog-modal wire:model="modal">

        <x-slot name="title">

            @if($crear)
                Nuevo Trámite
            @elseif($editar)
                Editar Trámite
            @endif

        </x-slot>

        <x-slot name="content">

            <div class="flex flex-col md:flex-row justify-between gap-3 mb-3">

                @if ($flags['solicitante'])

                    <div class="flex-row lg:flex lg:space-x-3 w-full">

                        <x-input-group for="modelo_editar.solicitante" label="Solicitante" :error="$errors->first('modelo_editar.solicitante')" class="w-full" >

                            <x-input-select id="modelo_editar.solicitante" wire:model.live="modelo_editar.solicitante" class="w-full" readonly>

                                <option value="">Seleccione una opción</option>

                                @foreach ($solicitantes as $item)

                                    <option value="{{ $item }}">{{ $item }}</option>

                                @endforeach

                            </x-input-select>

                        </x-input-group>

                        @if ($flags['nombre_solicitante'])

                            <x-input-group for="modelo_editar.nombre_solicitante" label="Nombre del solicitante" :error="$errors->first('modelo_editar.nombre_solicitante')" class="w-full">

                                <x-input-text id="modelo_editar.nombre_solicitante" wire:model="modelo_editar.nombre_solicitante" />

                            </x-input-group>

                        @endif

                        @if ($flags['dependencias'])

                            <x-input-group for="modelo_editar.nombre_solicitante" label="Dependencia" :error="$errors->first('modelo_editar.nombre_solicitante')" class="w-full">

                                <x-input-select id="modelo_editar.nombre_solicitante" wire:model="modelo_editar.nombre_solicitante" class="w-full">

                                    <option value="">Seleccione una opción</option>

                                    @foreach ($dependencias as $item)

                                        <option value="{{ $item }}">{{ $item }}</option>

                                    @endforeach

                                </x-input-select>

                            </x-input-group>

                        @endif

                        @if ($flags['notarias'])

                            <x-input-group for="notaria" label="Notaira" :error="$errors->first('notaria')" class="w-full">

                                <x-input-select id="notaria" wire:model="notaria" class="w-full">

                                    <option value="">Seleccione una opción</option>

                                    @foreach ($notarias as $item)

                                        <option value="{{ $item }}">{{ $item }}</option>

                                    @endforeach

                                </x-input-select>

                            </x-input-group>

                        @endif

                    </div>

                @endif

            </div>

            <div class="flex flex-col md:flex-row justify-between gap-3 mb-3">

                @if($modelo_editar->numero_oficio)

                    <x-input-group for="modelo_editar.numero_oficio" label="Número de oficio" :error="$errors->first('modelo_editar.numero_oficio')" class="w-full">

                        <x-input-text id="modelo_editar.numero_oficio" wire:model="modelo_editar.numero_oficio" />

                    </x-input-group>

                @endif

            </div>

            <div class="flex flex-col md:flex-row justify-between gap-3 mb-3">

                @if($modelo_editar->numero_escritura)

                    <x-input-group for="modelo_editar.numero_escritura" label="Número de escritura" :error="$errors->first('modelo_editar.numero_escritura')" class="w-full">

                        <x-input-text id="modelo_editar.numero_escritura" wire:model="modelo_editar.numero_escritura" />

                    </x-input-group>

                @endif

            </div>

            <div class="flex flex-col md:flex-row justify-between gap-3 mb-3">

                <x-input-group for="modelo_editar.tipo_documento" label="Tipo de documento" :error="$errors->first('modelo_editar.tipo_documento')" class="w-full">

                    <x-input-text id="modelo_editar.tipo_documento" wire:model="modelo_editar.tipo_documento" />

                </x-input-group>

                <x-input-group for="modelo_editar.autoridad_cargo" label="Autoridad cargo" :error="$errors->first('modelo_editar.autoridad_cargo')" class="w-full">

                    <x-input-text id="modelo_editar.autoridad_cargo" wire:model="modelo_editar.autoridad_cargo" />

                </x-input-group>

                <x-input-group for="modelo_editar.nombre_autoridad" label="Nombre de la autoridad" :error="$errors->first('modelo_editar.nombre_autoridad')" class="w-full">

                    <x-input-text id="modelo_editar.nombre_autoridad" wire:model="modelo_editar.nombre_autoridad" />

                </x-input-group>

            </div>

            <div class="flex flex-col md:flex-row justify-between gap-3 mb-3">

                <x-input-group for="modelo_editar.numero_documento" label="Número de documento" :error="$errors->first('modelo_editar.numero_documento')" class="w-full">

                    <x-input-text id="modelo_editar.numero_documento" wire:model="modelo_editar.numero_documento" />

                </x-input-group>

                <x-input-group for="modelo_editar.fecha_emision" label="Fecha de emisión" :error="$errors->first('modelo_editar.fecha_emision')" class="w-full">

                    <x-input-text type="date" id="modelo_editar.fecha_emision" wire:model="modelo_editar.fecha_emision" />

                </x-input-group>

                <x-input-group for="modelo_editar.procedencia" label="Procedencia" :error="$errors->first('modelo_editar.procedencia')" class="w-full">

                    <x-input-text id="modelo_editar.procedencia" wire:model="modelo_editar.procedencia" />

                </x-input-group>

            </div>

        </x-slot>

        <x-slot name="footer">

            <div class="flex gap-3">

                @if(!$modelo_editar->numero_control)

                    <x-button-gray
                        wire:click="generarNumeroControl"
                        wire:loading.attr="disabled"
                        wire:target="generarNumeroControl">

                        <img wire:loading wire:target="generarNumeroControl" class="mx-auto h-4 mr-1" src="{{ asset('storage/img/loading3.svg') }}" alt="Loading">

                        <span>Generar número de control</span>
                    </x-button-gray>

                @endif

                @if(!$modelo_editar->fecha_pago)

                    @can('Validar pago')

                        <x-button-gray
                            wire:click="validarPago"
                            wire:loading.attr="disabled"
                            wire:target="validarPago">

                            <img wire:loading wire:target="validarPago" class="mx-auto h-4 mr-1" src="{{ asset('storage/img/loading3.svg') }}" alt="Loading">

                            <span>Validar pago</span>
                        </x-button-gray>

                    @endcan

                @endif

                @if($editar)

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

    <x-dialog-modal wire:model="modalVer">

        <x-slot name="title">

            <h1 class="text-lg tracking-widest rounded-xl border-gray-500 text-center mb-5">Trámite</h1>

        </x-slot>

        <x-slot name="content">

            <div>

                @if ($modelo_editar->id)

                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-2">

                        <div class="rounded-lg bg-gray-100 py-1 px-2">

                            <p><strong>Número de control:</strong> {{ $modelo_editar->año }}-{{ $modelo_editar->numero_control }}-{{ $modelo_editar->usuario }}</p>

                        </div>

                        <div class="rounded-lg bg-gray-100 py-1 px-2">

                            <p><strong>Estado:</strong> {{ Str::ucfirst($modelo_editar->estado) }}</p>

                        </div>

                        <div class="rounded-lg bg-gray-100 py-1 px-2">

                            <p><strong>Categoría:</strong> {{ $modelo_editar->servicio->categoria->nombre }}</p>

                        </div>

                        <div class="rounded-lg bg-gray-100 py-1 px-2">

                            <p><strong>Servicio:</strong> {{ $modelo_editar->servicio->nombre }}</p>

                        </div>

                        <div class="rounded-lg bg-gray-100 py-1 px-2">

                            <p><strong>Tipo de trámite:</strong> {{ Str::ucfirst($modelo_editar->tipo_tramite) }}</p>

                        </div>

                        <div class="rounded-lg bg-gray-100 py-1 px-2">

                            <p><strong>Tipo de servicio:</strong> {{ Str::ucfirst($modelo_editar->tipo_servicio) }}</p>

                        </div>

                        <div class="rounded-lg bg-gray-100 py-1 px-2">

                            <p><strong>Solicitante:</strong>{{ $modelo_editar->solicitante }} / {{ $modelo_editar->nombre_solicitante }}</p>

                        </div>

                        @if ($modelo_editar->tomo_gravamen)

                            <div class="rounded-lg bg-gray-100 py-1 px-2">

                                <p><strong>Tomo gravamen:</strong> {{ $modelo_editar->tomo_gravamen }}</p>

                            </div>

                        @endif

                        @if ($modelo_editar->registro_gravamen)

                            <div class="rounded-lg bg-gray-100 py-1 px-2">

                                <p><strong>Registro gravamen:</strong> {{ $modelo_editar->registro_gravamen }}</p>

                            </div>

                        @endif

                        @if ($modelo_editar->numero_oficio)

                            <div class="rounded-lg bg-gray-100 py-1 px-2">

                                <p><strong>Número de oficio:</strong> {{ $modelo_editar->numero_oficio }}</p>

                            </div>

                        @endif

                        @if ($modelo_editar->numero_escritura)

                            <div class="rounded-lg bg-gray-100 py-1 px-2">

                                <p><strong>Número de escritura:</strong> {{ $modelo_editar->numero_escritura }}</p>

                            </div>

                        @endif

                        @if ($modelo_editar->numero_notaria)

                            <div class="rounded-lg bg-gray-100 py-1 px-2">

                                <p><strong>Número de notaría:</strong> {{ $modelo_editar->numero_notaria }}</p>

                            </div>

                        @endif

                        @if ($modelo_editar->nombre_notario)

                            <div class="rounded-lg bg-gray-100 py-1 px-2">

                                <p><strong>Nombre del notarío:</strong> {{ $modelo_editar->nombre_notario }}</p>

                            </div>

                        @endif

                        @if ($modelo_editar->valor_propiedad)

                            <div class="rounded-lg bg-gray-100 py-1 px-2">

                                <p><strong>Número de escritura:</strong> {{ $modelo_editar->numero_escritura }}</p>

                            </div>

                        @endif

                        @if ($modelo_editar->cantidad)

                            <div class="rounded-lg bg-gray-100 py-1 px-2">

                                <p><strong>Cantidad:</strong> {{ $modelo_editar->cantidad }}</p>

                            </div>

                        @endif

                        @if ($modelo_editar->numero_inmuebles)

                            <div class="rounded-lg bg-gray-100 py-1 px-2">

                                <p><strong>Número de inmuebles:</strong> {{ $modelo_editar->numero_inmuebles }}</p>

                            </div>

                        @endif

                        <div class="rounded-lg bg-gray-100 py-1 px-2">

                            <p><strong>Monto:</strong> ${{ number_format($modelo_editar->monto, 2) }}</p>

                        </div>

                        @if ($modelo_editar->fecha_entrega)

                            <div class="rounded-lg bg-gray-100 py-1 px-2">

                                <p><strong>Fecha de entrega:</strong> {{ $modelo_editar->fecha_entrega->format('d-m-Y') }}</p>

                            </div>

                        @endif

                        @if ($modelo_editar->valor_propiedad)

                            <div class="rounded-lg bg-gray-100 py-1 px-2">

                                <p><strong>Valor de propiedad:</strong> ${{ number_format($modelo_editar->valor_propiedad, 2) }}</p>

                            </div>

                        @endif

                        @if ($modelo_editar->fecha_pago)

                            <div class="rounded-lg bg-gray-100 py-1 px-2">

                                <p><strong>Fecha de pago:</strong> {{ $modelo_editar->fecha_pago }}</p>

                            </div>

                        @endif

                        @if ($modelo_editar->limite_de_pago)

                            <div class="rounded-lg bg-gray-100 py-1 px-2">

                                <p><strong>Límite de pago:</strong> {{ $modelo_editar->limite_de_pago }}</p>

                            </div>

                        @endif

                        <div class="rounded-lg bg-gray-100 py-1 px-2">

                            <p><strong>Orden de pago:</strong> {{ $modelo_editar->orden_de_pago }}</p>

                        </div>

                        <div class="rounded-lg bg-gray-100 py-1 px-2">

                            <p><strong>Linea de captura:</strong> {{ $modelo_editar->linea_de_captura }}</p>

                        </div>

                        @if ($modelo_editar->documento_de_pago)

                            <div class="rounded-lg bg-gray-100 py-1 px-2">

                                <p><strong>Documento de pago:</strong> {{ $modelo_editar->documento_de_pago }}</p>

                            </div>

                        @endif

                        <div class="rounded-lg bg-gray-100 py-1 px-2">

                            <p><strong>Registrado por:</strong> {{ $modelo_editar->creadoPor->name }} el {{ $modelo_editar->created_at }}</p>

                        </div>

                        <span class="lg:col-span-2 text-center ">Antecedente</span>

                        @if ($modelo_editar->folio_real)

                            <div class="rounded-lg bg-gray-100 py-1 px-2">

                                <p><strong>Folio real:</strong> {{ $modelo_editar->folio_real }}</p>

                            </div>

                        @endif

                        @if ($modelo_editar->tomo)

                            <div class="rounded-lg bg-gray-100 py-1 px-2">

                                <p><strong>Tomo:</strong> {{ $modelo_editar->tomo }}</p>

                            </div>

                        @endif

                        @if ($modelo_editar->registro)

                            <div class="rounded-lg bg-gray-100 py-1 px-2">

                                <p><strong>Registro:</strong> {{ $modelo_editar->registro }}</p>

                            </div>

                        @endif

                        @if ($modelo_editar->numero_propiedad)

                            <div class="rounded-lg bg-gray-100 py-1 px-2">

                                <p><strong>Númerpo de propiedad:</strong> {{ $modelo_editar->numero_propiedad }}</p>

                            </div>

                        @endif

                        @if ($modelo_editar->distrito)

                            <div class="rounded-lg bg-gray-100 py-1 px-2">

                                <p><strong>Distrito:</strong> {{ App\Constantes\Constantes::DISTRITOS[$modelo_editar->distrito] }}</p>

                            </div>

                        @endif

                        @if ($modelo_editar->seccion)

                            <div class="rounded-lg bg-gray-100 py-1 px-2">

                                <p><strong>Sección:</strong> {{ $modelo_editar->seccion }}</p>

                            </div>

                        @endif

                        @if ($modelo_editar->asiento_registral)

                            <div class="rounded-lg bg-gray-100 py-1 px-2">

                                <p><strong>Movimiento registral:</strong> {{ $modelo_editar->asiento_registral }}</p>

                            </div>

                        @endif

                        @if ($modelo_editar->tipo_documento)

                            <span class="lg:col-span-2 text-center ">Documento de entrada</span>

                            <div class="rounded-lg bg-gray-100 py-1 px-2">

                                <p><strong>Tipo de documento:</strong> {{ $modelo_editar->tipo_documento }}</p>

                            </div>

                        @endif

                        @if ($modelo_editar->autoridad_cargo)

                            <div class="rounded-lg bg-gray-100 py-1 px-2">

                                <p><strong>Autoridad cargo:</strong> {{ $modelo_editar->autoridad_cargo }}</p>

                            </div>

                        @endif

                        @if ($modelo_editar->nombre_autoridad)

                            <div class="rounded-lg bg-gray-100 py-1 px-2">

                                <p><strong>Nombre autoridad:</strong> {{ $modelo_editar->nombre_autoridad }}</p>

                            </div>

                        @endif

                        @if ($modelo_editar->numero_documento)

                            <div class="rounded-lg bg-gray-100 py-1 px-2">

                                <p><strong>Número de documento:</strong> {{ $modelo_editar->numero_documento }}</p>

                            </div>

                        @endif

                        @if ($modelo_editar->fecha_emision)

                            <div class="rounded-lg bg-gray-100 py-1 px-2">

                                <p><strong>Fecha de emisión:</strong> {{ $modelo_editar->fecha_emision }}</p>

                            </div>

                        @endif

                        @if ($modelo_editar->procedencia)

                            <div class="rounded-lg bg-gray-100 py-1 px-2">

                                <p><strong>Procedencia:</strong> {{ $modelo_editar->procedencia }}</p>

                            </div>

                        @endif

                    </div>

                    @if ($modelo_editar->observaciones)

                        <div class="rounded-lg bg-gray-100 py-1 px-2 my-3">

                            <strong>Observaciones:</strong>

                            <p>{{ $modelo_editar->observaciones }}</p>

                        </div>

                    @endif

                    @if($modelo_editar->adicionadoPor->count())

                        <div class="rounded-lg bg-gray-100 py-1 px-2 my-3">

                            <p>Adicionado por:</p>

                            <div class="flex space-x-2 flex-row">

                                @foreach ($modelo_editar->adicionadoPor as $item)

                                    <p><strong>NC:</strong><span class="whitespace-nowrap">{{ $item->año }}-{{ $item->numero_control }}-{{ $item->usuario }}</span></p>

                                @endforeach

                            </div>

                        </div>

                    @endif

                    @if($modelo_editar->adicionaAlTramite?->count())

                        <div class="rounded-lg bg-gray-100 py-1 px-2 my-3">

                            <p>Adiciona a:</p>

                            <div class="flex space-x-2 flex-row">

                                <p><strong>NC:</strong><span class="whitespace-nowrap">{{ $modelo_editar->adicionaAlTramite->año }}-{{ $modelo_editar->adicionaAlTramite->numero_control }}-{{ $modelo_editar->adicionaAlTramite->usuario }}</span></p>

                            </div>

                        </div>

                    @endif

                @endif

            </div>

        </x-slot>

        <x-slot name="footer">

            <div class="flex gap-3">

                @if(env('LOCAL') === "0" || env('LOCAL') === "3")

                    <x-button-gray
                        wire:click="simularPago"
                        wire:loading.attr="disabled"
                        wire:target="simularPago">

                        <img wire:loading wire:target="simularPago" class="mx-auto h-4 mr-1" src="{{ asset('storage/img/loading3.svg') }}" alt="Loading">

                        <span>Simular pago</span>
                    </x-button-gray>

                @endif

                <div>


                    @if($modelo_editar->solicitante === 'Oficialia de partes' || !$modelo_editar->movimiento_registral && $modelo_editar->fecha_pago)

                        <x-button-gray
                            wire:click="enviarTramiteRpp"
                            wire:loading.attr="disabled"
                            wire:target="enviarTramiteRpp">

                            <img wire:loading wire:target="enviarTramiteRpp" class="mx-auto h-4 mr-1" src="{{ asset('storage/img/loading3.svg') }}" alt="Loading">

                            <span>Enviar al Sistema RPP</span>
                        </x-button-gray>

                    @endif

                </div>

                @if($modelo_editar->estado == 'nuevo')

                    <x-button-gray
                        wire:click="reimprimir"
                        wire:loading.attr="disabled"
                        wire:target="reimprimir">

                        <img wire:loading wire:target="reimprimir" class="mx-auto h-4 mr-1" src="{{ asset('storage/img/loading3.svg') }}" alt="Loading">

                        <span>Imprimir recibo</span>
                    </x-button-gray>

                @endif

                @if($editar)

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
            Eliminar trámite
        </x-slot>

        <x-slot name="content">
            ¿Esta seguro que desea eliminar al trámite? No sera posible recuperar la información.
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

    <x-dialog-modal wire:model="modalAcreditar" maxWidth="sm">

        <x-slot name="title">

            <h1 class="text-lg tracking-widest rounded-xl border-gray-500 text-center mb-5">Acreditar pago</h1>

        </x-slot>

        <x-slot name="content">

            <x-input-group for="referencia_pago" label="Número de referencia de pago" :error="$errors->first('referencia_pago')" class="w-full mb-3">

                <x-input-text type="number" id="referencia_pago" wire:model="referencia_pago" />

            </x-input-group>

            <x-input-group for="fecha_pago" label="Fecha de pago" :error="$errors->first('fecha_pago')" class="w-full">

                <x-input-text type="date" id="fecha_pago" wire:model="fecha_pago" />

            </x-input-group>

        </x-slot>

        <x-slot name="footer">

            <div class="flex gap-3">

                <x-button-blue
                    wire:click="acreditarPago"
                    wire:loading.attr="disabled"
                    wire:target="acreditarPago">

                    <img wire:loading wire:target="acreditarPago" class="mx-auto h-4 mr-1" src="{{ asset('storage/img/loading3.svg') }}" alt="Loading">

                    <span>Actualizar</span>
                </x-button-blue>

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

    <script>

        window.addEventListener('imprimir_recibo', event => {

            const tramite = event.detail[0].tramite;

            var url_orden = "{{ route('tramites.orden', '')}}" + "/" + tramite;

            window.open(url_orden, '_blank');

            var url_ticket = "{{ route('tramites.recibo', '')}}" + "/" + tramite;

            window.open(url_ticket, '_blank');

        });

    </script>

</div>
