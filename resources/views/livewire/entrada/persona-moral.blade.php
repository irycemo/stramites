<div class="">

    <div class="grid grid-cols-1 md:grid-cols-2 gap-3">

        {{-- Campos --}}
        <div>

            @if (!$tramite)

                <div>

                    @include('livewire.entrada.comun.solicitante')

                    @include('livewire.entrada.comun.documento_entrada')

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-3">

                        @if($servicio['nombre'] == 'Acta de asamblea')

                            @include('livewire.entrada.comun.folio_real_persona_moral')

                            @include('livewire.entrada.comun.tomo')

                            @include('livewire.entrada.comun.registro')

                        @endif

                        @include('livewire.entrada.comun.distrito')

                        @include('livewire.entrada.comun.numero_oficio')

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
