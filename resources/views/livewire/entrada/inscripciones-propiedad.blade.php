<div class="">

    <div class="grid grid-cols-1 md:grid-cols-2 gap-3">

        {{-- Campos --}}
        <div>

            @if (!$tramite)

                <div>

                    @include('livewire.entrada.comun.adiciona')

                    @include('livewire.entrada.comun.solicitante')

                    @include('livewire.entrada.comun.antecedente')

                    @if($this->servicio['clave_ingreso'] != 'D118')

                        @include('livewire.entrada.comun.documento_entrada')

                    @endif

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-3">

                        @include('livewire.entrada.comun.distrito')

                        @include('livewire.entrada.comun.cantidad')

                        @include('livewire.entrada.comun.valor_propiedad')

                        @include('livewire.entrada.comun.numero_inmuebles')

                        @include('livewire.entrada.comun.numero_oficio')

                        @include('livewire.entrada.comun.tipo_servicio')

                        @include('livewire.entrada.comun.tipo_tramite')

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
