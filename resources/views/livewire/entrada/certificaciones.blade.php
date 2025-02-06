<div class="">
{{ $errors }}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-3">

        {{-- Campos --}}
        <div>

            @if (!$tramite)

                <div>

                    @include('livewire.entrada.comun.adiciona')

                    @include('livewire.entrada.comun.solicitante')

                    @include('livewire.entrada.comun.antecedente')

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-3 relative" wire:loading.class.delay.longest="opacity-50">

                        @include('livewire.entrada.comun.folio_real')

                        @include('livewire.entrada.comun.movimiento_registral')

                        @include('livewire.entrada.comun.tomo')

                        @include('livewire.entrada.comun.registro')

                        @include('livewire.entrada.comun.distrito')

                        @include('livewire.entrada.comun.seccion')

                        @include('livewire.entrada.comun.cantidad')

                        @include('livewire.entrada.comun.numero_oficio')

                        @include('livewire.entrada.comun.tipo_servicio')

                        @include('livewire.entrada.comun.tipo_tramite')

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
