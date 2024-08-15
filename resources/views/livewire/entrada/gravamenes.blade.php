<div class="">

    <div class="grid grid-cols-1 md:grid-cols-2 gap-3">

        {{-- Campos --}}
        <div>

            @if (!$tramite)

                <div>

                    {{-- Solicitante - Nombre del solicitante --}}
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

                    @if ($flags['antecedente'])

                        <x-h4>Antecedente de propiedad</x-h4>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-3 bg-white p-4 rounded-lg mb-3 shadow-md" wire:loading.class.delay.longest="opacity-50">

                            <x-input-group for="modelo_editar.folio_real" label="Folio real" :error="$errors->first('modelo_editar.folio_real')" class="">

                                <x-input-text type="number" id="modelo_editar.folio_real" wire:model.lazy="modelo_editar.folio_real" />

                            </x-input-group>

                            <x-input-group for="modelo_editar.tomo" label="Tomo" :error="$errors->first('modelo_editar.tomo')" class="">

                                <x-input-text type="number" id="modelo_editar.tomo" wire:model.lazy="modelo_editar.tomo" :readonly="$modelo_editar->folio_real != null"/>

                            </x-input-group>

                            <x-input-group for="modelo_editar.registro" label="Registro" :error="$errors->first('modelo_editar.registro')" class="">

                                <x-input-text type="number" id="modelo_editar.registro" wire:model.lazy="modelo_editar.registro" :readonly="$modelo_editar->folio_real != null"/>

                            </x-input-group>

                            <x-input-group for="modelo_editar.numero_propiedad" label="Número de propiedad" :error="$errors->first('modelo_editar.numero_propiedad')" class="">

                                <x-input-text type="number" id="modelo_editar.numero_propiedad" wire:model.lazy="modelo_editar.numero_propiedad" :readonly="$modelo_editar->folio_real != null"/>

                            </x-input-group>

                            <x-input-group for="modelo_editar.distrito" label="Distrito" :error="$errors->first('modelo_editar.distrito')" class="">

                                <x-input-select id="modelo_editar.distrito" wire:model.lazy="modelo_editar.distrito" class="w-full" :disabled="$modelo_editar->folio_real != null ">

                                    <option value="" selected>Seleccione una opción</option>

                                    @foreach ($distritos as $key => $item)

                                        <option value="{{  $key }}">{{  $item }}</option>

                                    @endforeach

                                </x-input-select>

                            </x-input-group>

                            <x-input-group for="modelo_editar.seccion" label="Seccion" :error="$errors->first('modelo_editar.seccion')" class="">

                                <x-input-select id="modelo_editar.seccion" wire:model.lazy="modelo_editar.seccion" class="w-full" :disabled="$modelo_editar->folio_real != null ">

                                    <option value="" selected>Seleccione una opción</option>

                                    @foreach ($secciones as $seccion)

                                        <option value="{{ $seccion }}">{{ $seccion }}</option>

                                    @endforeach

                                </x-input-select>

                            </x-input-group>

                        </div>

                    @endif

                    @if ($flags['documento'])

                        <x-h4>Documento de entrada</x-h4>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-3 bg-white p-4 rounded-lg mb-3 shadow-md">

                            <x-input-group for="modelo_editar.tipo_documento" label="Tipo de documento" :error="$errors->first('modelo_editar.tipo_documento')" class="w-full">

                                <x-input-select id="modelo_editar.tipo_documento" wire:model="modelo_editar.tipo_documento" class="w-full">

                                    <option value="">Seleccione una opción</option>
                                    <option value="escritura">Escritura</option>
                                    <option value="oficio">Oficio</option>
                                    <option value="contrato">Contrato</option>

                                </x-input-select>

                            </x-input-group>

                            <x-input-group for="modelo_editar.autoridad_cargo" label="Autoridad cargo" :error="$errors->first('modelo_editar.autoridad_cargo')" class="w-full">

                                <x-input-select id="modelo_editar.autoridad_cargo" wire:model.live="modelo_editar.autoridad_cargo" class="w-full">

                                    <option value="">Seleccione una opción</option>
                                    <option value="notario">Notario(a)</option>
                                    <option value="foraneo">Notario(a) foraneo</option>
                                    <option value="juez">Juez(a)</option>
                                    <option value="funcionario">Funcionario</option>

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

                                <x-input-group for="modelo_editar.nombre_autoridad" label="Número y nombre de la autoridad" :error="$errors->first('modelo_editar.nombre_autoridad')" class="w-full">

                                    <x-input-text id="modelo_editar.nombre_autoridad" wire:model="modelo_editar.nombre_autoridad" />

                                </x-input-group>

                            @endif

                            <x-input-group for="modelo_editar.numero_documento" label="Número de documento" :error="$errors->first('modelo_editar.numero_documento')" class="w-full">

                                <x-input-text type="number" id="modelo_editar.numero_documento" wire:model="modelo_editar.numero_documento" />

                            </x-input-group>

                            <x-input-group for="modelo_editar.fecha_emision" label="Fecha de emisión" :error="$errors->first('modelo_editar.fecha_emision')" class="w-full">

                                <x-input-text type="date" id="modelo_editar.fecha_emision" wire:model="modelo_editar.fecha_emision" />

                            </x-input-group>

                            <x-input-group for="modelo_editar.procedencia" label="Procedencia" :error="$errors->first('modelo_editar.procedencia')" class="w-full">

                                <x-input-text id="modelo_editar.procedencia" wire:model="modelo_editar.procedencia" />

                            </x-input-group>

                        </div>

                    @endif

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-3 relative" wire:loading.class.delay.longest="opacity-50">

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
