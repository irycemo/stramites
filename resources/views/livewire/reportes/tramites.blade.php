<div>

    <div class="md:flex md:flex-row flex-col md:space-x-4 items-end bg-white rounded-xl mb-5 p-4">

        <div>

            <div>

                <Label>Fecha inicial</Label>

            </div>

            <div>

                <input type="date" class="bg-white rounded text-sm " wire:model.live="fecha1">

            </div>

            <div>

                @error('fecha1') <span class="error text-sm text-red-500">{{ $message }}</span> @enderror

            </div>

        </div>

        <div class="mt-2 md:mt-0">

            <div>

                <Label>Fecha final</Label>

            </div>

            <div>

                <input type="date" class="bg-white rounded text-sm " wire:model.live="fecha2">

            </div>

            <div>

                @error('fecha2') <span class="error text-sm text-red-500">{{ $message }}</span> @enderror

            </div>

        </div>

    </div>

    <div class="md:flex flex-col md:flex-row justify-between md:space-x-3 items-center bg-white rounded-xl mb-5 p-4">

        <div class="flex-auto ">

            <div>

                <Label>Estado</Label>
            </div>

            <div>

                <select class="rounded text-sm w-full" wire:model.live="estado">

                    <option value="" selected>Seleccione una opción</option>
                    <option value="nuevo">Nuevo</option>
                    <option value="pagado">Pagado</option>
                    <option value="inactivo">Inactivo</option>
                    <option value="concluido">Concluido</option>
                    <option value="rechazado">Rechazado</option>
                    <option value="expirado">Expirado</option>
                    <option value="procesando">Procesando</option>
                    <option value="revision">Revision</option>
                    <option value="recibido">Recibido</option>
                    <option value="finalizado">Finalizado</option>

                </select>

            </div>

            <div>

                @error('estado') <span class="error text-sm text-red-500">{{ $message }}</span> @enderror

            </div>

        </div>

        <div class="flex-auto ">

            <div>

                <Label>Ubicación</Label>
            </div>

            <div>

                <select class="rounded text-sm w-full" wire:model.live="ubicacion">

                    <option value="" selected>Seleccione una opción</option>
                    @foreach ($ubicaciones as $ubicacion)

                        <option value="{{$ubicacion}}" >{{$ubicacion}}</option>

                    @endforeach

                </select>

            </div>

            <div>

                @error('estado') <span class="error text-sm text-red-500">{{ $message }}</span> @enderror

            </div>

        </div>

        <div class="flex-auto ">

            <div>

                <Label>Usuario</Label>
            </div>

            <div>

                <select class="rounded text-sm w-full" wire:model.live="usuario_id">

                    <option value="" selected>Seleccione una opción</option>

                    @foreach ($usuarios as $usuario)

                        <option value="{{$usuario->id}}" >{{$usuario->name}}</option>

                    @endforeach

                </select>

            </div>

            <div>

                @error('usuario_id') <span class="error text-sm text-red-500">{{ $message }}</span> @enderror

            </div>

        </div>

        <div class="flex-auto ">

            <div>

                <Label>Servicio</Label>
            </div>

            <div>

                <select class="rounded text-sm w-full" wire:model.live="servicio_id">

                    <option value="" selected>Seleccione una opción</option>

                    @foreach ($servicios as $servicio)

                        <option value="{{$servicio->id}}" >{{$servicio->nombre}}</option>

                    @endforeach

                </select>

            </div>

            <div>

                @error('servicio_id') <span class="error text-sm text-red-500">{{ $message }}</span> @enderror

            </div>

        </div>

        <div class="flex-auto ">

            <div>

                <Label>Tipo de servicio</Label>
            </div>

            <div>

                <select class="rounded text-sm w-full" wire:model.live="tipo_servicio">

                    <option value="" selected>Seleccione una opción</option>
                    <option value="ordinario" selected>Ordinario</option>
                    <option value="urgente" selected>Urgente</option>
                    <option value="extra_urgente" selected>Extra urgente</option>

                </select>

            </div>

            <div>

                @error('tipo_servicio') <span class="error text-sm text-red-500">{{ $message }}</span> @enderror

            </div>

        </div>

        <div class="flex-auto ">

            <div>

                <Label>Solicitante</Label>
            </div>

            <div>

                <select class="rounded text-sm w-full" wire:model.live="solicitante">

                    <option value="" selected>Seleccione una opción</option>

                    @foreach ($solicitantes as $solicitante)

                        <option value="{{$solicitante}}" >{{$solicitante}}</option>

                    @endforeach

                </select>

            </div>

            <div>

                @error('solicitante') <span class="error text-sm text-red-500">{{ $message }}</span> @enderror

            </div>

        </div>

    </div>

    @if(count($tramites))

        <div class="rounded-lg shadow-xl mb-5 p-4 font-thin md:flex items-center justify-between bg-white">

            <p class="text-xl font-extralight">Se encontraron: {{ number_format($tramites->total()) }} registros con los filtros seleccionados.</p>

            <x-button-green wire:click="descargarExcel" >

                <img wire:loading wire:target="descargarExcel" class="mx-auto h-4 mr-1" src="{{ asset('storage/img/loading3.svg') }}" alt="Loading">

                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3M3 17V7a2 2 0 012-2h6l2 2h6a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2z" />
                </svg>

                Exportar a Excel

            </x-button-green>

        </div>

        <div class="relative overflow-x-auto rounded-lg shadow-xl border-t-2 border-t-gray-500">

            <x-table>

                <x-slot name="head">

                    <x-table.heading>Número de control</x-table.heading>
                    <x-table.heading>Estado</x-table.heading>
                    <x-table.heading>Servicio</x-table.heading>
                    <x-table.heading>Solicitante</x-table.heading>
                    <x-table.heading>Folio real</x-table.heading>
                    <x-table.heading>Tomo</x-table.heading>
                    <x-table.heading>Registro</x-table.heading>
                    <x-table.heading>Monto</x-table.heading>
                    <x-table.heading>Tipo de servicio</x-table.heading>
                    <x-table.heading>Número de oficio</x-table.heading>
                    <x-table.heading>Tomo gravamen</x-table.heading>
                    <x-table.heading>Registro gravamen</x-table.heading>
                    <x-table.heading>Distrito</x-table.heading>
                    <x-table.heading>Sección</x-table.heading>
                    <x-table.heading>Cantidad</x-table.heading>
                    <x-table.heading>Número de inmuebles</x-table.heading>
                    <x-table.heading>Número de escritura</x-table.heading>
                    <x-table.heading>Notaria</x-table.heading>
                    <x-table.heading>Valor de la propiedad</x-table.heading>
                    <x-table.heading>Fecha de entrega</x-table.heading>
                    <x-table.heading>Fecha de pago</x-table.heading>
                    <x-table.heading>Documento de pago</x-table.heading>
                    <x-table.heading>Linea de captura</x-table.heading>
                    <x-table.heading>Movimiento registral</x-table.heading>
                    <x-table.heading>Observaciones</x-table.heading>
                    <x-table.heading>Registro</x-table.heading>
                    <x-table.heading>Actualizado</x-table.heading>

                </x-slot>

                <x-slot name="body">

                    @foreach($tramites as $tramite)

                        <x-table.row wire:key="row-{{ $usuario->id }}">

                            <x-table.cell>

                                <span class="lg:hidden absolute top-0 left-0 bg-blue-300 px-2 py-1 text-xs text-white font-bold uppercase rounded-br-xl">Número de control</span>

                                {{ $tramite->año }}-{{ $tramite->numero_control }}-{{ $tramite->usuario }}

                            </x-table.cell>

                            <x-table.cell>

                                <span class="lg:hidden absolute top-0 left-0 bg-blue-300 px-2 py-1 text-xs text-white font-bold uppercase rounded-br-xl">Estado</span>

                                <span class="bg-{{ $tramite->estado_color }} py-1 px-2 rounded-full text-white text-xs">{{ ucfirst($tramite->estado) }}</span>

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

                                <span class="lg:hidden absolute top-0 left-0 bg-blue-300 px-2 py-1 text-xs text-white font-bold uppercase rounded-br-xl">Folio Real</span>

                                {{ $tramite->folio_real ? $tramite->folio_real : 'N/A' }}

                            </x-table.cell>

                            <x-table.cell>

                                <span class="lg:hidden absolute top-0 left-0 bg-blue-300 px-2 py-1 text-xs text-white font-bold uppercase rounded-br-xl">Tomo / Bis</span>

                                {{ $tramite->tomo ? $tramite->tomo : 'N/A' }} {{ $tramite->tomo_bis ? '/ Bis' : ''}}

                            </x-table.cell>

                            <x-table.cell>

                                <span class="lg:hidden absolute top-0 left-0 bg-blue-300 px-2 py-1 text-xs text-white font-bold uppercase rounded-br-xl">Registro / Bis</span>

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

                                <span class="lg:hidden absolute top-0 left-0 bg-blue-300 px-2 py-1 text-xs text-white font-bold uppercase rounded-br-xl">Número de oficio</span>

                                {{ $tramite->numero_oficio ? $tramite->numero_oficio : 'N/A' }}

                            </x-table.cell>

                            <x-table.cell>

                                <span class="lg:hidden absolute top-0 left-0 bg-blue-300 px-2 py-1 text-xs text-white font-bold uppercase rounded-br-xl">Tomo gravamen</span>

                                {{ $tramite->tomo_gravamen ? $tramite->tomo_gravamen : 'N/A' }}

                            </x-table.cell>

                            <x-table.cell>

                                <span class="lg:hidden absolute top-0 left-0 bg-blue-300 px-2 py-1 text-xs text-white font-bold uppercase rounded-br-xl">Registro gravamen</span>

                                {{ $tramite->registro_gravamen ? $tramite->registro_gravamen : 'N/A' }}

                            </x-table.cell>

                            <x-table.cell>

                                <span class="lg:hidden absolute top-0 left-0 bg-blue-300 px-2 py-1 text-xs text-white font-bold uppercase rounded-br-xl">Distrito</span>

                                {{ $tramite->distrito }}

                            </x-table.cell>

                            <x-table.cell>

                                <span class="lg:hidden absolute top-0 left-0 bg-blue-300 px-2 py-1 text-xs text-white font-bold uppercase rounded-br-xl">Sección</span>

                                {{ $tramite->seccion }}

                            </x-table.cell>

                            <x-table.cell>

                                <span class="lg:hidden absolute top-0 left-0 bg-blue-300 px-2 py-1 text-xs text-white font-bold uppercase rounded-br-xl">Cantidad</span>

                                {{ $tramite->cantidad ?? 'N/A' }}

                            </x-table.cell>

                            <x-table.cell>

                                <span class="lg:hidden absolute top-0 left-0 bg-blue-300 px-2 py-1 text-xs text-white font-bold uppercase rounded-br-xl">Número de inmuebles</span>

                                {{ $tramite->numero_inmuebles ? $tramite->numero_inmuebles : 'N/A' }}

                            </x-table.cell>

                            <x-table.cell>

                                <span class="lg:hidden absolute top-0 left-0 bg-blue-300 px-2 py-1 text-xs text-white font-bold uppercase rounded-br-xl">Número de escritura</span>

                                {{ $tramite->numero_escritura ? $tramite->numero_escritura : 'N/A' }}

                            </x-table.cell>

                            <x-table.cell>

                                <span class="lg:hidden absolute top-0 left-0 bg-blue-300 px-2 py-1 text-xs text-white font-bold uppercase rounded-br-xl">Notaria</span>

                                {{ $tramite->numero_notaria ? $tramite->numero_notaria . ' - ' . $tramite->nombre_notario : 'N/A' }}

                            </x-table.cell>

                            <x-table.cell>

                                <span class="lg:hidden absolute top-0 left-0 bg-blue-300 px-2 py-1 text-xs text-white font-bold uppercase rounded-br-xl">Valor de la propiedad</span>

                                {{ $tramite->valor_propiedad ? $tramite->valor_propiedad : 'N/A' }}

                            </x-table.cell>

                            <x-table.cell>

                                <span class="lg:hidden absolute top-0 left-0 bg-blue-300 px-2 py-1 text-xs text-white font-bold uppercase rounded-br-xl">Fecha de entrega</span>

                                {{ $tramite->fecha_entrega?->format('d-m-Y') }}

                            </x-table.cell>

                            <x-table.cell>

                                <span class="lg:hidden absolute top-0 left-0 bg-blue-300 px-2 py-1 text-xs text-white font-bold uppercase rounded-br-xl">Fecha de pago</span>

                                {{ $tramite->fecha_pago?->format('d-m-Y') }}

                            </x-table.cell>

                            <x-table.cell>

                                <span class="lg:hidden absolute top-0 left-0 bg-blue-300 px-2 py-1 text-xs text-white font-bold uppercase rounded-br-xl">Documento de pago</span>

                                {{ $tramite->documento_de_pago ?? 'N/A' }}

                            </x-table.cell>

                            <x-table.cell>

                                <span class="lg:hidden absolute top-0 left-0 bg-blue-300 px-2 py-1 text-xs text-white font-bold uppercase rounded-br-xl">Línea de captura</span>

                                {{ $tramite->linea_de_captura ?? 'N/A' }}

                            </x-table.cell>

                            <x-table.cell>

                                <span class="lg:hidden absolute top-0 left-0 bg-blue-300 px-2 py-1 text-xs text-white font-bold uppercase rounded-br-xl">Movimeinto registral</span>

                                {{ $tramite->movimiento_registral ?? 'N/A' }}

                            </x-table.cell>

                            <x-table.cell>

                                <span class="lg:hidden absolute top-0 left-0 bg-blue-300 px-2 py-1 text-xs text-white font-bold uppercase rounded-br-xl">Observaciones</span>

                                {{ $tramite->observaciones ? $tramite->observaciones : 'N/A' }}

                            </x-table.cell>

                            <x-table.cell>

                                <span class="lg:hidden absolute top-0 left-0 bg-blue-300 px-2 py-1 text-xs text-white font-bold uppercase rounded-br-xl">Registrado</span>

                                @if($tramite->creadoPor != null)

                                    <span class="font-semibold">Registrado por: {{$tramite->creadoPor->name}}</span> <br>

                                @endif

                                {{ $tramite->created_at }}

                            </x-table.cell>

                            <x-table.cell>

                                <span class="lg:hidden absolute top-0 left-0 bg-blue-300 px-2 py-1 text-xs text-white font-bold uppercase rounded-br-xl">Actualizado</span>

                                @if($tramite->actualizadoPor != null)

                                    <span class="font-semibold">Actualizado por: {{$tramite->actualizadoPor->name}}</span> <br>

                                @endif

                                {{ $tramite->updated_at }}

                            </x-table.cell>

                        </x-table.row>

                    @endforeach

                </x-slot>

                <x-slot name="tfoot">

                    <x-table.row>

                        <x-table.cell colspan="1" class="bg-gray-50">

                            <select class="bg-white rounded-full text-sm" wire:model.live="pagination">

                                <option value="10">10</option>
                                <option value="25">25</option>
                                <option value="50">50</option>
                                <option value="100">100</option>

                            </select>

                        </x-table.cell>

                        <x-table.cell colspan="26" class="bg-gray-50">

                            {{ $tramites->links()}}

                        </x-table.cell>

                    </x-table.row>

                </x-slot>

            </x-table>

            <div class="h-full w-full rounded-lg bg-gray-200 bg-opacity-75 absolute top-0 left-0" wire:loading>

                <img class="mx-auto h-16" src="{{ asset('storage/img/loading.svg') }}" alt="">

            </div>

        </div>

    @else

        <div class="border-b border-gray-300 bg-white text-gray-500 text-center p-5 rounded-full text-lg">

            No hay resultados.

        </div>

    @endif

</div>
