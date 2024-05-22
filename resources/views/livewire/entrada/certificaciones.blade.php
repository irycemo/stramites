<div class="">

    <div class="grid grid-cols-1 md:grid-cols-2 gap-3">

        {{-- Campos --}}
        <div>

            @if (!$tramite)

                <div>

                    @if ($flags['adiciona'])

                        <div class="flex space-x-3 bg-white p-4 rounded-lg mb-3 shadow-md relative" wire:loading.class.delay.longest="opacity-50">

                            <div class="flex space-x-4 items-center">

                                <Label>¿Adiciona a otro trámite?</Label>

                                <x-checkbox wire:model.live="adicionaTramite"></x-checkbox>

                            </div>

                            @if($adicionaTramite)

                                <div class="flex-auto mr-1">

                                    <div class="flex space-x-4 items-center">

                                        <Label>Seleccione el trámite</Label>

                                    </div>

                                    <div
                                        x-data = "{ model: @entangle('modelo_editar.adiciona') }"
                                        x-init ="
                                            select2 = $($refs.select)
                                                .select2({
                                                    placeholder: 'Número de control',
                                                    width: '100%',
                                                })

                                            select2.on('change', function(){
                                                $wire.set('modelo_editar.adiciona', $(this).val())
                                            })

                                            select2.on('keyup', function(e) {
                                                if (e.keyCode === 13){
                                                    $wire.set('modelo_editar.adiciona', $('.select2').val())
                                                }
                                            });

                                            $watch('model', (value) => {
                                                select2.val(value).trigger('change');
                                            });
                                        "
                                        wire:ignore>

                                        <select
                                            class="bg-white rounded text-sm w-full z-50"
                                            wire:model.live="modelo_editar.adiciona"
                                            x-ref="select">

                                            @foreach ($tramitesAdicionados as $item)

                                                <option value="{{ $item->id }}">{{ $item->año }}-{{ $item->numero_control }}-{{ $item->usuario }}</option>

                                            @endforeach

                                        </select>

                                    </div>

                                    <div>

                                        @error('modelo_editar.adiciona') <span class="error text-sm text-red-500">{{ $message }}</span> @enderror

                                    </div>

                                </div>

                            @endif

                            <div wire:loading.delay.longest class="flex absolute top-1 right-1 items-center">
                                <svg class="animate-spin h-5 w-5 text-blue-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                            </div>

                        </div>

                    @endif

                    {{-- Solicitante - Nombre del solicitante --}}
                    @if ($flags['solicitante'])

                        <div class="flex-row lg:flex lg:space-x-3 relative" wire:loading.class.delay.longest="opacity-50">

                            <div class="flex-auto bg-white p-3 rounded-lg mb-3 shadow-md">

                                <div class="flex-auto ">

                                    <div class="mb-2">

                                        <Label class="text-lg tracking-widest rounded-xl border-gray-500">Solicitante</Label>

                                    </div>

                                    <div>

                                        <select class="bg-white rounded text-sm w-full" wire:model.live="modelo_editar.solicitante">

                                            <option value="" selected>Seleccione una opción</option>

                                            @foreach ($solicitantes as $solicitante)

                                                <option value="{{ $solicitante }}">{{ $loop->iteration }} - {{ $solicitante }}</option>

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

                                        <input type="text" class="bg-white rounded text-sm w-full" wire:model="modelo_editar.nombre_solicitante">

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

                                        <select class="bg-white rounded text-sm w-full" wire:model="modelo_editar.nombre_solicitante">

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

                                        <select class="bg-white rounded text-sm w-full" wire:model="notaria">

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

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-3 relative" wire:loading.class.delay.longest="opacity-50">

                        @if($flags['tomo'])

                            <div class="flex space-x-3 bg-white p-4 rounded-lg mb-3 shadow-md">

                                <div class="flex-auto">

                                    <div class="mb-2">

                                        <Label class="text-lg tracking-widest rounded-xl border-gray-500">Tomo</Label>

                                    </div>

                                    <div>

                                        <input type="number" class="bg-white rounded text-sm w-full" wire:model.lazy="modelo_editar.tomo">

                                    </div>

                                    <div>

                                        @error('modelo_editar.tomo') <span class="error text-sm text-red-500">{{ $message }}</span> @enderror

                                    </div>

                                </div>

                                <div class="flex-auto">

                                    <div class="mb-2">

                                        <Label class="text-lg tracking-widest rounded-xl border-gray-500">Bis</Label>

                                    </div>

                                    <div>

                                        <x-checkbox wire:model="modelo_editar.tomo_bis"></x-checkbox>

                                    </div>

                                    <div>

                                        @error('modelo_editar.tomo_bis') <span class="error text-sm text-red-500">{{ $message }}</span> @enderror

                                    </div>

                                </div>

                            </div>

                        @endif

                        @if($flags['registro'])

                            <div class="flex space-x-3 bg-white p-4 rounded-lg mb-3 shadow-md">

                                <div class="flex-auto">

                                    <div class="mb-2">

                                        <Label class="text-lg tracking-widest rounded-xl border-gray-500">Registro</Label>

                                    </div>

                                    <div>

                                        <input type="number" class="bg-white rounded text-sm w-full" wire:model.lazy="modelo_editar.registro">

                                    </div>

                                    <div>

                                        @error('modelo_editar.registro') <span class="error text-sm text-red-500">{{ $message }}</span> @enderror

                                    </div>

                                </div>

                                <div class="flex-auto" >

                                    <div class="mb-2">

                                        <Label class="text-lg tracking-widest rounded-xl border-gray-500">Bis</Label>

                                    </div>

                                    <div>

                                        <x-checkbox wire:model="modelo_editar.registro_bis"></x-checkbox>

                                    </div>

                                    <div>

                                        @error('modelo_editar.registro_bis') <span class="error text-sm text-red-500">{{ $message }}</span> @enderror

                                    </div>

                                </div>

                            </div>

                        @endif

                        @if($flags['distrito'])

                            <div class="flex-auto bg-white p-4 rounded-lg mb-3 shadow-md">

                                <div class="mb-2">

                                    <Label class="text-lg tracking-widest rounded-xl border-gray-500">Distrito</Label>
                                </div>

                                <div>

                                    <select class="bg-white rounded text-sm w-full" wire:model.lazy="modelo_editar.distrito">

                                        <option value="" selected>Seleccione una opción</option>

                                        @foreach ($distritos as $key => $item)

                                            <option value="{{  $key }}">{{  $item }}</option>

                                        @endforeach

                                    </select>

                                </div>

                                <div>

                                    @error('modelo_editar.distrito') <span class="error text-sm text-red-500">{{ $message }}</span> @enderror

                                </div>

                            </div>

                        @endif

                        @if($flags['seccion'])

                            <div class="flex-auto bg-white p-4 rounded-lg mb-3 shadow-md">

                                <div class="mb-2">

                                    <Label class="text-lg tracking-widest rounded-xl border-gray-500">Sección</Label>

                                </div>

                                <div>

                                    <select class="bg-white rounded text-sm w-full" wire:model.lazy="modelo_editar.seccion">

                                        <option value="" selected>Seleccione una opción</option>

                                        @foreach ($secciones as $seccion)

                                            <option value="{{ $seccion }}">{{ $seccion }}</option>

                                        @endforeach

                                    </select>

                                </div>

                                <div>

                                    @error('modelo_editar.seccion') <span class="error text-sm text-red-500">{{ $message }}</span> @enderror

                                </div>

                            </div>

                        @endif

                        @if($flags['cantidad'])

                            <div class="flex-auto bg-white p-4 rounded-lg mb-3 shadow-md justify-between">

                                <div class="mb-2">

                                    <Label class="text-lg tracking-widest rounded-xl border-gray-500">@if(in_array($this->servicio['clave_ingreso'], ['DL13', 'DL14'])) Cantidad de páginas @else Cantidad @endif</Label>
                                </div>

                                <div>

                                    <input type="number" min="1" class="bg-white rounded text-sm w-full" wire:model.lazy="modelo_editar.cantidad">

                                </div>

                                <div>

                                    @error('modelo_editar.cantidad') <span class="error text-sm text-red-500">{{ $message }}</span> @enderror

                                </div>

                            </div>

                        @endif

                        @if($flags['numero_oficio'])

                            <div class="flex-auto bg-white p-4 rounded-lg mb-3 shadow-md">

                                <div class="mb-2">

                                    <Label class="text-lg tracking-widest rounded-xl border-gray-500">Número de oficio</Label>
                                </div>

                                <div>

                                    <input type="text" class="bg-white rounded text-sm w-full" wire:model="modelo_editar.numero_oficio">

                                </div>

                                <div>

                                    @error('modelo_editar.numero_oficio') <span class="error text-sm text-red-500">{{ $message }}</span> @enderror

                                </div>

                            </div>

                        @endif

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

                    </div>

                    @if ($flags['observaciones'])

                        <div class="flex-auto bg-white p-4 rounded-lg mb-3 shadow-md relative" wire:loading.class.delay.longest="opacity-50">

                            <div class="mb-2">

                                <Label class="text-lg tracking-widest rounded-xl border-gray-500">Observaciones</Label>

                            </div>

                            <div>

                                <textarea rows="3" wire:model.lazy="modelo_editar.observaciones" class="bg-white rounded text-sm w-full"></textarea>

                            </div>

                            <div>

                                @error('modelo_editar.observaciones') <span class="error text-sm text-red-500">{{ $message }}</span> @enderror

                                @error('modelo_editar.movimiento_registral') <span class="error text-sm text-red-500">{{ $message }}</span> @enderror

                            </div>

                        </div>

                    @endif

                </div>

            @endif

        </div>

        {{-- Tramtie --}}
        <div>

            @if($tramite)

                <div class="bg-white p-3 rounded-lg  shadow-md">

                    <h1 class="text-lg tracking-widest rounded-xl border-gray-500 text-center mb-5">Trámite</h1>

                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-2">

                        <div class="rounded-lg bg-gray-100 py-1 px-2">

                            <p><strong>Número de control:</strong> {{ $tramite->año }}-{{ $tramite->numero_control }}-{{ $tramite->usuario }} </p>

                        </div>

                        <div class="rounded-lg bg-gray-100 py-1 px-2">

                            <p><strong>Estado:</strong> {{ Str::ucfirst($tramite->estado) }}</p>

                        </div>

                        <div class="rounded-lg bg-gray-100 py-1 px-2">

                            <p><strong>Categoría:</strong> {{ $tramite->servicio->categoria->nombre }}</p>

                        </div>

                        <div class="rounded-lg bg-gray-100 py-1 px-2">

                            <p><strong>Servicio:</strong> {{ $tramite->servicio->nombre }}</p>

                        </div>

                        <div class="rounded-lg bg-gray-100 py-1 px-2">

                            <p><strong>Tipo de servicio:</strong> {{ Str::ucfirst($tramite->tipo_servicio) }}</p>

                        </div>

                        @if ($tramite->tomo)

                            <div class="rounded-lg bg-gray-100 py-1 px-2">

                                <p><strong>Tomo:</strong> {{ $tramite->tomo }}</p>

                            </div>

                        @endif

                        @if ($tramite->registro)

                            <div class="rounded-lg bg-gray-100 py-1 px-2">

                                <p><strong>Registro:</strong> {{ $tramite->registro }}</p>

                            </div>

                        @endif

                        @if ($tramite->distrito)

                            <div class="rounded-lg bg-gray-100 py-1 px-2">

                                <p><strong>Distrito:</strong> {{ $tramite->distrito }}</p>

                            </div>

                        @endif

                        @if ($tramite->seccion)

                            <div class="rounded-lg bg-gray-100 py-1 px-2">

                                <p><strong>Sección:</strong> {{ $tramite->seccion }}</p>

                            </div>

                        @endif

                        @if ($tramite->numero_oficio)

                            <div class="rounded-lg bg-gray-100 py-1 px-2">

                                <p><strong>Número de oficio:</strong> {{ $tramite->numero_oficio }}</p>

                            </div>

                        @endif

                        @if ($tramite->numero_notaria)

                            <div class="rounded-lg bg-gray-100 py-1 px-2">

                                <p><strong>Número de notaría:</strong> {{ $tramite->numero_notaria }}</p>

                            </div>

                        @endif

                        @if ($tramite->nombre_notario)

                            <div class="rounded-lg bg-gray-100 py-1 px-2">

                                <p><strong>Nombre del notarío:</strong> {{ $tramite->nombre_notario }}</p>

                            </div>

                        @endif

                        @if ($tramite->cantidad)

                            <div class="rounded-lg bg-gray-100 py-1 px-2">

                                <p><strong>Cantidad:</strong> {{ $tramite->cantidad }}</p>

                            </div>

                        @endif

                        @if ($tramite->numero_inmuebles)

                            <div class="rounded-lg bg-gray-100 py-1 px-2">

                                <p><strong>Número de inmuebles:</strong> {{ $tramite->numero_inmuebles }}</p>

                            </div>

                        @endif

                        <div class="rounded-lg bg-gray-100 py-1 px-2">

                            <p><strong>Solicitante:</strong>{{ $tramite->solicitante }} / {{ $tramite->nombre_solicitante }}</p>

                        </div>

                        <div class="rounded-lg bg-gray-100 py-1 px-2">

                            <p><strong>Monto:</strong> ${{ number_format($tramite->monto, 2) }}</p>

                        </div>

                        @if ($tramite->fecha_entrega)

                            <div class="rounded-lg bg-gray-100 py-1 px-2">

                                <p><strong>Fecha de entrega:</strong> {{ $tramite->fecha_entrega->format('d-m-Y') }}</p>

                            </div>

                        @endif

                        @if ($tramite->limite_de_pago)

                            <div class="rounded-lg bg-gray-100 py-1 px-2">

                                <p><strong>Límite de pago:</strong> {{ $tramite->limite_de_pago }}</p>

                            </div>

                        @endif

                        <div class="rounded-lg bg-gray-100 py-1 px-2">

                            <p><strong>Registrado por:</strong> {{ $tramite->creadoPor->name }} el {{ $tramite->created_at }}</p>

                        </div>

                    </div>

                    @if ($tramite->observaciones)

                        <div class="rounded-lg bg-gray-100 py-1 px-2 my-3">

                            <strong>Observaciones:</strong>

                            <p>{{ $tramite->observaciones }}</p>

                        </div>

                    @endif

                    <div class="mt-4 text-right">

                        @if ($tramite->estado == 'nuevo' || $tramite->estado == 'rechazado')

                            <button
                                wire:click="editarTramite"
                                wire:loading.attr="disabled"
                                wire:target="editarTramite"
                                type="button"
                                class="bg-green-400 hover:shadow-lg text-white font-bold px-4 py-2 rounded text-sm hover:bg-green-700 focus:outline-none ">
                                <img wire:loading wire:target="editarTramite" class="mx-auto h-4 mr-1" src="{{ asset('storage/img/loading3.svg') }}" alt="Loading">
                                Editar
                            </button>

                        @endif

                        @if (!$tramite->fecha_pago)

                            <button
                                wire:click="reimprimir"
                                wire:loading.attr="disabled"
                                wire:target="reimprimir"
                                type="button"
                                class="bg-blue-400 hover:shadow-lg text-white font-bold px-4 py-2 rounded text-sm hover:bg-blue-700 focus:outline-none ">
                                <img wire:loading wire:target="reimprimir" class="mx-auto h-4 mr-1" src="{{ asset('storage/img/loading3.svg') }}" alt="Loading">
                                Reimprimir
                            </button>

                            @can('Validar pago')

                                <button
                                    wire:click="validarPago"
                                    wire:loading.attr="disabled"
                                    wire:target="validarPago"
                                    type="button"
                                    class="bg-red-400 hover:shadow-lg text-white font-bold px-4 py-2 rounded text-sm hover:bg-red-700 focus:outline-none ">
                                    <img wire:loading wire:target="validarPago" class="mx-auto h-4 mr-1" src="{{ asset('storage/img/loading3.svg') }}" alt="Loading">
                                    Validar
                                </button>

                            @endif

                        @endif

                    </div>

                </div>

            @else

                <div class="bg-white p-3 rounded-lg  shadow-md">

                    <h1 class="text-lg tracking-widest rounded-xl border-gray-500 text-center mb-5">Trámite Nuevo</h1>

                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-2">

                        <div class="rounded-lg bg-gray-100 py-1 px-2">

                            <p><strong>Categoría:</strong> {{ $servicio['categoria']['nombre'] }}</p>

                        </div>

                        <div class="rounded-lg bg-gray-100 py-1 px-2">

                            <p><strong>Servicio:</strong> {{ $servicio['nombre'] }}</p>

                        </div>

                        <div class="rounded-lg bg-gray-100 py-1 px-2">

                            <p><strong>Tipo de servicio:</strong> {{ Str::ucfirst($modelo_editar->tipo_servicio) }}</p>

                        </div>

                        <div class="rounded-lg bg-gray-100 py-1 px-2">

                            <p><strong>Solicitante:</strong>{{ $modelo_editar->solicitante }} / {{ $modelo_editar->nombre_solicitante }}</p>

                        </div>

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

                                <p><strong>Distrito:</strong> {{ $modelo_editar->distrito }}</p>

                            </div>

                        @endif

                        @if ($modelo_editar->seccion)

                            <div class="rounded-lg bg-gray-100 py-1 px-2">

                                <p><strong>Sección:</strong> {{ $modelo_editar->seccion }}</p>

                            </div>

                        @endif

                        @if ($modelo_editar->numero_oficio)

                            <div class="rounded-lg bg-gray-100 py-1 px-2">

                                <p><strong>Número de oficio:</strong> {{ $modelo_editar->numero_oficio }}</p>

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

                        @if ($modelo_editar->cantidad)

                            <div class="rounded-lg bg-gray-100 py-1 px-2">

                                <p><strong>Cantidad:</strong> {{ $modelo_editar->cantidad }}</p>

                            </div>

                        @endif

                        <div class="rounded-lg bg-gray-100 py-1 px-2">

                            <p><strong>Monto:</strong> ${{ number_format($modelo_editar->monto, 2) }}</p>

                        </div>

                    </div>

                    @if ($modelo_editar->observaciones)

                        <div class="rounded-lg bg-gray-100 py-1 px-2 my-3">

                            <strong>Observaciones:</strong>

                            <p>{{ $modelo_editar->observaciones }}</p>

                        </div>

                    @endif

                    <div class="mt-4 text-right">

                        <div class="flex space-x-4 items-center">

                            <x-checkbox wire:model="mantener"></x-checkbox>

                            <Label>Mantener información</Label>

                        </div>

                        @if ($editar)

                            <button
                                wire:click="actualizar"
                                wire:loading.attr="disabled"
                                wire:target="actualizar"
                                type="button"
                                class="bg-blue-400 hover:shadow-lg text-white font-bold px-4 py-2 rounded text-sm hover:bg-blue-700 focus:outline-blue-400 focus:outline-offset-2 flex items-center ml-auto">
                                <img wire:loading wire:target="actualizar" class="mx-auto h-4 mr-1" src="{{ asset('storage/img/loading3.svg') }}" alt="Loading">
                                Actualizar trámite
                            </button>

                        @else

                            <button
                                wire:click="crear"
                                wire:loading.attr="disabled"
                                wire:target="crear"
                                type="button"
                                class="bg-blue-400 hover:shadow-lg text-white font-bold px-4 py-2 rounded text-sm hover:bg-blue-700 focus:outline-blue-400 focus:outline-offset-2 flex items-center ml-auto">
                                <img wire:loading wire:target="crear" class="mx-auto h-4 mr-1" src="{{ asset('storage/img/loading3.svg') }}" alt="Loading">
                                Crear nuevo trámite
                            </button>

                        @endif

                    </div>

                </div>

            @endif

        </div>

    </div>

</div>
