<div class="">

    <div class="grid grid-cols-1 md:grid-cols-2 gap-3">

        {{-- Campos --}}
        <div>

            @if (!$tramite)

                <div>

                    @include('livewire.entrada.comun.solicitante')

                    @include('livewire.entrada.comun.antecedente')

                    @if($servicio['clave_ingreso'] === 'D153')

                        <x-h4>Antecedente de reestructura</x-h4>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-3 relative" wire:loading.class.delay.longest="opacity-50">

                            @include('livewire.entrada.comun.folio_real_extra')

                            @include('livewire.entrada.comun.movimiento_registral_extra')

                        </div>

                    @endif

                    @include('livewire.entrada.comun.documento_entrada')

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-3 relative" wire:loading.class.delay.longest="opacity-50">

                        @include('livewire.entrada.comun.numero_oficio')

                        @include('livewire.entrada.comun.tipo_servicio')

                        @include('livewire.entrada.comun.tramite_foraneo')

                    </div>

                    @include('livewire.entrada.comun.observaciones')

                </div>

            @endif

        </div>

        {{-- Tramtie --}}
        <div>

            @if($tramite)

                @include('livewire.entrada.comun.tramite_encontrado')

            @else

                @include('livewire.entrada.comun.tramite_nuevo')

            @endif

        </div>

    </div>

</div>
