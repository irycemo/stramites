<div>

    <h1 class="text-3xl tracking-widest py-3 px-6 text-gray-600 rounded-xl border-b-2 border-gray-500 font-thin mb-6  bg-white">Reportes</h1>

    <div class="p-4 mb-5 bg-white shadow-lg rounded-lg">

        <div>

            <Label>Área</Label>

        </div>

        <div>

            <select class="bg-white rounded text-sm mb-3" wire:model.live="area">
                <option selected value="">Selecciona una área</option>
                <option value="tramites">Trámites</option>
            </select>

        </div>

    </div>

    @if ($verTramites)

            @livewire('reportes.tramites', ['fecha1' => $this->fecha1, 'fecha2' => $this->fecha2, 'estado' => $this->estado])

    @endif

</div>
