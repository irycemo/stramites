@if($flags['tramite_foraneo'])

    <div class="flex-auto bg-white p-4 rounded-lg mb-3 shadow-md justify-between col-span-2">

        <div class="mb-2 text-center">

            <Label class="text-lg tracking-widest rounded-xl border-gray-500">Trámite foraneo</Label>
        </div>

        <div class="flex overflow-auto justify-center">

            <select class="bg-white rounded-l text-sm border border-r-transparent  focus:ring-0" wire:model="año_foraneo">
                @foreach ($años as $año)

                    <option value="{{ $año }}">{{ $año }}</option>

                @endforeach
            </select>

            <input type="number" class="bg-white text-sm w-16 focus:ring-0 @error('folio_foraneo') border-red-500 @enderror" wire:model="folio_foraneo">

            <input type="number" class="bg-white text-sm w-16 rounded-r border-l-0 focus:ring-0 @error('usuario_foraneo') border-red-500 @enderror" wire:model="usuario_foraneo">

        </div>

    </div>

@endif
