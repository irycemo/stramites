<div>

    <x-header>Reportes</x-header>

    <div class="p-4 mb-5 bg-white shadow-lg rounded-lg text-center">

        <div>

            <Label>Área</Label>

        </div>

        <div>

            <select class="bg-white rounded text-sm mb-3" wire:model.live="area">
                <option selected value="">Selecciona una área</option>
                <option value="tramites">Trámites</option>
                <option value="recaudacion">Recaudación</option>
                <option value="exentos">Exentos</option>
            </select>

        </div>

    </div>

    @if ($verTramites)

        @livewire('reportes.tramites', ['fecha1' => $this->fecha1, 'fecha2' => $this->fecha2, 'estado' => $this->estado])

    @endif

    @if ($verRecaudacion)

        @livewire('reportes.recaudacion', ['fecha1' => $this->fecha1, 'fecha2' => $this->fecha2, 'estado' => $this->estado])

    @endif

    @if ($exentos)

        @livewire('reportes.exentos', ['fecha1' => $this->fecha1, 'fecha2' => $this->fecha2, 'estado' => $this->estado])

    @endif

</div>
