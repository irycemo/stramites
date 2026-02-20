<div class="">

    <div class="mb-5">

        <x-header>Entrega</x-header>

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

        </div>

    </div>

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
                <x-table.heading sortable wire:click="sortBy('created_at')" :direction="$sort === 'created_at' ? $direction : null">Registro</x-table.heading>
                <x-table.heading sortable wire:click="sortBy('updated_at')" :direction="$sort === 'updated_at' ? $direction : null">Actualizado</x-table.heading>
                <x-table.heading >Acciones</x-table.heading>

            </x-slot>

            <x-slot name="body">

                @forelse ($tramites as $tramite)

                    <x-table.row wire:loading.class.delaylongest="opacity-50" wire:key="row-{{ $tramite->id }}">

                        <x-table.cell title="Número de control">

                            {{ $tramite->año }}-{{ $tramite->numero_control }}-{{ $tramite->usuario }}

                        </x-table.cell>

                        <x-table.cell title="Servicio">

                            {{ $tramite->servicio->nombre }}

                        </x-table.cell>

                        <x-table.cell title="ServiSolicitantecio">

                            {{ $tramite->solicitante }}

                            {{ $tramite->nombre_solicitante ? '/ ' . $tramite->nombre_solicitante : ''}}

                        </x-table.cell>

                        <x-table.cell title="Folio real">

                            {{ $tramite->folio_real ?? 'N/A' }}

                        </x-table.cell>

                        <x-table.cell title="Tomo">

                            {{ $tramite->tomo ?? 'N/A' }}

                        </x-table.cell>

                        <x-table.cell title="Registro">

                            {{ $tramite->registro ?? 'N/A' }}

                        </x-table.cell>

                        <x-table.cell title="Tipo servicio">

                            {{ $tramite->tipo_servicio }}

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

                            <div class="flex md:flex-col justify-center lg:justify-start gap-2">

                                @if($tramite->recibido_por == null)

                                    @can('Preentregar')

                                        <x-button-blue
                                            wire:click="abrirModalRecibir({{ $tramite->id }})"
                                            wire:loading.attr="disabled"
                                        >
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" class="w-4 h-4 mr-2">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                            </svg>

                                            <span>Recibir</span>

                                        </x-button-blue>

                                    @endcan

                                @endif

                                @if($tramite->recibido_por != null)

                                    @can('Entregar')

                                        <x-button-green
                                            wire:click="abrirModalFinalizar({{ $tramite->id }})"
                                            wire:loading.attr="disabled"
                                        >
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" class="w-4 h-4 mr-2">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                            </svg>

                                            <span>Entregar</span>

                                        </x-button-green>

                                    @endcan

                                @endif

                            </div>

                        </x-table.cell>

                    </x-table.row>

                @empty

                    <x-table.row>

                        <x-table.cell colspan="12">

                            <div class="bg-white text-gray-500 text-center p-5 rounded-full text-lg">

                                No hay resultados.

                            </div>

                        </x-table.cell>

                    </x-table.row>

                @endforelse

            </x-slot>

            <x-slot name="tfoot">

                <x-table.row>

                    <x-table.cell colspan="12" class="bg-gray-50">

                        {{ $tramites->links()}}

                    </x-table.cell>

                </x-table.row>

            </x-slot>

        </x-table>

    </div>

    <x-confirmation-modal wire:model="modalRecibir" maxWidth="sm">

        <x-slot name="title">
            Recibir documentación
        </x-slot>

        <x-slot name="content">
            ¿Esta seguro que desea recibir documentación?
        </x-slot>

        <x-slot name="footer">

            <x-secondary-button
                wire:click="$toggle('modalRecibir')"
                wire:loading.attr="disabled"
            >
                No
            </x-secondary-button>

            <x-danger-button
                class="ml-2"
                wire:click="recibir"
                wire:loading.attr="disabled"
                wire:target="recibir"
            >
                Si
            </x-danger-button>

        </x-slot>

    </x-confirmation-modal>

    <x-confirmation-modal wire:model="modalFinalizar" maxWidth="sm">

        <x-slot name="title">
            Finalizar trámite
        </x-slot>

        <x-slot name="content">
            ¿Esta seguro que desea finalizar el trámite?
        </x-slot>

        <x-slot name="footer">

            <x-secondary-button
                wire:click="$toggle('modalFinalizar')"
                wire:loading.attr="disabled"
            >
                No
            </x-secondary-button>

            <x-danger-button
                class="ml-2"
                wire:click="finalizar"
                wire:loading.attr="disabled"
                wire:target="finalizar"
            >
                Si
            </x-danger-button>

        </x-slot>

    </x-confirmation-modal>

</div>
