<div class="">

    <div class="grid grid-cols-1 md:grid-cols-2 gap-3">

        {{-- Campos --}}
        <div>

            @if (!$tramite)

                <div>

                    @include('livewire.entrada.comun.solicitante')

                    @if ($servicio['clave_ingreso'] == 'D127')

                        @include('livewire.entrada.comun.antecedente')

                    @endif

                    @include('livewire.entrada.comun.documento_entrada_moral')

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-3">

                        @include('livewire.entrada.comun.numero_inmuebles')

                        @if ($servicio['clave_ingreso'] != 'D127')

                            @include('livewire.entrada.comun.folio_real')

                        @endif

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
