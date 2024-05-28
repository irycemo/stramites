<div class="">

    <div class="mb-6">

        <x-header>Consultas</x-header>

        <div class="flex justify-between">

            <div class="flex lg:w-1/4">

                <select class="bg-white rounded-l text-sm border border-r-transparent  focus:ring-0 @error('año') border-red-500 @enderror" wire:model="año">
                    @foreach ($años as $año)

                        <option value="{{ $año }}">{{ $año }}</option>

                    @endforeach
                </select>

                <input type="number" placeholder="Número de control" min="1" class="bg-white text-sm w-full focus:ring-0 border-r-transparent @error('numero_control') border-red-500 @enderror" wire:model="numero_control">

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

        <div class="overflow-x-auto rounded-lg shadow-xl border-t-2 border-t-gray-500">

            <x-table>

                <x-slot name="head">

                    <x-table.heading sortable wire:click="sortBy('numero_control')" :direction="$sort === 'numero_control' ? $direction : null">Número de control</x-table.heading>
                    <x-table.heading sortable wire:click="sortBy('id_servicio')" :direction="$sort === 'id_servicio' ? $direction : null">Servicio</x-table.heading>
                    <x-table.heading sortable wire:click="sortBy('solicitante')" :direction="$sort === 'solicitante' ? $direction : null">Solicitante</x-table.heading>
                    <x-table.heading sortable wire:click="sortBy('folio_real')" :direction="$sort === 'folio_real' ? $direction : null">Folio real</x-table.heading>
                    <x-table.heading sortable wire:click="sortBy('tomo')" :direction="$sort === 'tomo' ? $direction : null">Tomo</x-table.heading>
                    <x-table.heading sortable wire:click="sortBy('registro')" :direction="$sort === 'registro' ? $direction : null">Registro</x-table.heading>
                    <x-table.heading sortable wire:click="sortBy('tipo_servicio')" :direction="$sort === 'tipo_servicio' ? $direction : null">Tipo servicio</x-table.heading>
                    <x-table.heading sortable wire:click="sortBy('estado')" :direction="$sort === 'estado' ? $direction : null">Estado</x-table.heading>
                    <x-table.heading >Observaciones</x-table.heading>
                    <x-table.heading sortable wire:click="sortBy('created_at')" :direction="$sort === 'created_at' ? $direction : null">Registro</x-table.heading>
                    <x-table.heading sortable wire:click="sortBy('updated_at')" :direction="$sort === 'updated_at' ? $direction : null">Actualizado</x-table.heading>
                    <x-table.heading >Acciones</x-table.heading>

                </x-slot>

                <x-slot name="body">

                    <x-table.row wire:loading.class.delaylongest="opacity-50">

                        <x-table.cell>

                            <span class="lg:hidden absolute top-0 left-0 bg-blue-300 px-2 py-1 text-xs text-white font-bold uppercase rounded-br-xl">Número de control</span>

                            {{ $tramite->año }}-{{ $tramite->numero_control }}-{{ $tramite->usuario }}

                        </x-table.cell>

                        <x-table.cell>

                            <span class="lg:hidden absolute top-0 left-0 bg-blue-300 px-2 py-1 text-xs text-white font-bold uppercase rounded-br-xl">Servicio</span>

                            {{ $tramite->servicio->nombre }}

                        </x-table.cell>

                        <x-table.cell>

                            <span class="lg:hidden absolute top-0 left-0 bg-blue-300 px-2 py-1 text-xs text-white font-bold uppercase rounded-br-xl">Solicitante</span>

                            {{ $tramite->solicitante }}

                            {{ $tramite->nombre_solicitante ? '/ ' . $tramite->nombre_solicitante : ''}}

                        </x-table.cell>

                        <x-table.cell>

                            <span class="lg:hidden  absolute top-0 left-0 bg-blue-300 px-2 py-1 text-xs text-white font-bold uppercase rounded-br-xl">Folio real</span>

                            {{ $tramite->folio_real ?? 'N/A' }}

                        </x-table.cell>

                        <x-table.cell>

                            <span class="lg:hidden  absolute top-0 left-0 bg-blue-300 px-2 py-1 text-xs text-white font-bold uppercase rounded-br-xl">Tomo</span>

                            {{ $tramite->tomo ?? 'N/A' }} {{ $tramite->tomo_bis ? '/ Bis' : ''}}

                        </x-table.cell>

                        <x-table.cell>

                            <span class="lg:hidden  absolute top-0 left-0 bg-blue-300 px-2 py-1 text-xs text-white font-bold uppercase rounded-br-xl">Registro</span>

                            {{ $tramite->registro ?? 'N/A' }} {{ $tramite->registro_bis ? '/ Bis' : ''}}

                        </x-table.cell>

                        <x-table.cell>

                            <span class="lg:hidden  absolute top-0 left-0 bg-blue-300 px-2 py-1 text-xs text-white font-bold uppercase rounded-br-xl">Tipo servicio</span>

                            {{ $tramite->tipo_servicio }}

                        </x-table.cell>

                        <x-table.cell>

                            <span class="lg:hidden absolute top-0 left-0 bg-blue-300 px-2 py-1 text-xs text-white font-bold uppercase rounded-br-xl">Estado</span>

                            <span class="bg-{{ $tramite->estado_color }} py-1 px-2 rounded-full text-white text-xs">{{ ucfirst($tramite->estado) }}</span>

                        </x-table.cell>

                        <x-table.cell>

                            <span class="lg:hidden  absolute top-0 left-0 bg-blue-300 px-2 py-1 text-xs text-white font-bold uppercase rounded-br-xl">Observaciones</span>

                            {{ $tramite->observaciones }}

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

                            <div class="flex md:flex-col justify-center lg:justify-start gap-2">

                                <x-button-green
                                    wire:click="abrirModalEditar({{ $tramite->id }})"
                                    wire:loading.attr="disabled"
                                >
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4 mr-2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    </svg>

                                    <span>Ver</span>

                                </x-button-green>

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

    <x-dialog-modal wire:model="modal">

        <x-slot name="title">

            <h1 class="text-lg tracking-widest rounded-xl border-gray-500 text-center mb-5">Trámite</h1>

        </x-slot>

        <x-slot name="content">

            <div>

                @if ($modelo_editar->id)

                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-2">

                        <div class="rounded-lg bg-gray-100 py-1 px-2">

                            <p><strong>Número de control:</strong> {{ $modelo_editar->numero_control }}</p>

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

                        @if ($modelo_editar->numero_propiedad)

                            <div class="rounded-lg bg-gray-100 py-1 px-2">

                                <p><strong>Número de propiedad:</strong> {{ $modelo_editar->numero_propiedad }}</p>

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

                                    <p><strong>NC:</strong>{{ $item->año }}-{{ $item->numero_control }}</p>

                                @endforeach

                            </div>

                        </div>

                    @endif

                    @if($modelo_editar->adicionaAlTramite?->count())

                        <div class="rounded-lg bg-gray-100 py-1 px-2 my-3">

                            <p>Adiciona a:</p>

                            <div class="flex space-x-2 flex-row">

                                <p><strong>NC:</strong>{{ $modelo_editar->adicionaAlTramite->año }}-{{ $modelo_editar->adicionaAlTramite->numero_control }}</p>

                            </div>

                        </div>

                    @endif

                @endif

            </div>

        </x-slot>

        <x-slot name="footer">

            <div class="flex gap-3">

                @if(!$modelo_editar->fecha_pago)

                    <x-button-gray
                        wire:click="reimprimir"
                        wire:loading.attr="disabled"
                        wire:target="reimprimir">

                        <img wire:loading wire:target="reimprimir" class="mx-auto h-4 mr-1" src="{{ asset('storage/img/loading3.svg') }}" alt="Loading">

                        <span>Imprimir recibo</span>
                    </x-button-gray>

                    @can('Validar pago')

                        <x-button-blue
                            wire:click="validarPago"
                            wire:loading.attr="disabled"
                            wire:target="validarPago">

                            <img wire:loading wire:target="validarPago" class="mx-auto h-4 mr-1" src="{{ asset('storage/img/loading3.svg') }}" alt="Loading">

                            <span>Validar</span>

                        </x-button-blue>

                    @endcan

                @endif

                <x-button-red
                    wire:click="resetearTodo"
                    wire:loading.attr="disabled"
                    wire:target="resetearTodo"
                    type="button">

                    <span>Cerrar</span>

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
