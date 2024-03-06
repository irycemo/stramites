<div>

    <div class="md:flex flex-col md:flex-row justify-between md:space-x-3 items-center bg-white rounded-xl mb-5 p-4">

        <div class="md:flex md:flex-row flex-col md:space-x-4 items-end bg-white rounded-xl">

            <div>

                <div>

                    <Label>Fecha inicial</Label>

                </div>

                <div>

                    <input type="date" class="bg-white rounded text-sm " wire:model.live="fecha1">

                </div>

                <div>

                    @error('fecha1') <span class="error text-sm text-red-500">{{ $message }}</span> @enderror

                </div>

            </div>

            <div class="mt-2 md:mt-0">

                <div>

                    <Label>Fecha final</Label>

                </div>

                <div>

                    <input type="date" class="bg-white rounded text-sm " wire:model.live="fecha2">

                </div>

                <div>

                    @error('fecha2') <span class="error text-sm text-red-500">{{ $message }}</span> @enderror

                </div>

            </div>

        </div>

        <div class="flex-auto ">

            <div>

                <Label>Categorías</Label>
            </div>

            <div>

                <select class="rounded text-sm w-full" wire:model.live="categoria">

                    <option value="" selected>Seleccione una opción</option>

                    @foreach ($categorias as $categoria)

                        <option value="{{$categoria->id}}" >{{$categoria->nombre}}</option>

                    @endforeach

                </select>

            </div>

            <div>

                @error('categoria') <span class="error text-sm text-red-500">{{ $message }}</span> @enderror

            </div>

        </div>

        <div class="flex-auto ">

            <div>

                <Label>Servicio</Label>
            </div>

            <div>

                <select class="rounded text-sm w-full" wire:model.live="servicio_id">

                    <option value="" selected>Seleccione una opción</option>

                    @foreach ($servicios as $servicio)

                        <option value="{{$servicio->id}}" >{{$servicio->nombre}}</option>

                    @endforeach

                </select>

            </div>

            <div>

                @error('servicio_id') <span class="error text-sm text-red-500">{{ $message }}</span> @enderror

            </div>

        </div>

        <div class="flex-auto ">

            <div>

                <Label>Tipo de servicio</Label>
            </div>

            <div>

                <select class="rounded text-sm w-full" wire:model.live="tipo_servicio">

                    <option value="" selected>Seleccione una opción</option>
                    <option value="ordinario" selected>Ordinario</option>
                    <option value="urgente" selected>Urgente</option>
                    <option value="extra_urgente" selected>Extra urgente</option>

                </select>

            </div>

            <div>

                @error('tipo_servicio') <span class="error text-sm text-red-500">{{ $message }}</span> @enderror

            </div>

        </div>

    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-3">

        @if($rpp)

            <div class="bg-white rounded-xl mb-5 p-4">

                <p class="text-center tracking-wider font-semibold">RPP</p>

                <table class="w-full overflow-x-auto table-fixed">

                    <tbody class="divide-y divide-gray-200">

                        @php
                            $total = 0;
                        @endphp

                        @foreach ($rpp as $key => $value)

                            <tr class="text-gray-500 text-sm leading-relaxed">
                                <td class=" px-2 w-full whitespace-nowrap"><p>{{ $key }}</p></td>
                                <td class=" px-2 w-full whitespace-nowrap text-right"><p>${{ number_format($value, 2) }}</p></td>
                            </tr>

                            @php

                                $total = $total + $value;

                            @endphp

                        @endforeach

                        @php

                            echo " <tr class='text-gray-500 text-sm leading-relaxed'>
                                        <td class='px-2 w-full whitespace-nowrap font-bold'>Total</td>
                                        <td class='px-2 w-full whitespace-nowrap font-bold text-right'><p>$"  . number_format($total, 2) . "</p></td>
                                    </tr>
                                ";
                        @endphp

                    </tbody>

                </table>

            </div>

        @endif

        @if($regional1)

            <div class="bg-white rounded-xl mb-5 p-4">

                <p class="text-center tracking-wider font-semibold">Regional 1</p>

                <table class="w-full overflow-x-auto table-fixed">

                    <tbody class="divide-y divide-gray-200">

                        @php
                            $total = 0;
                        @endphp

                        @foreach ($regional1 as $key => $value)

                            <tr class="text-gray-500 text-sm leading-relaxed">
                                <td class=" px-2 w-full whitespace-nowrap"><p>{{ $key }}</p></td>
                                <td class=" px-2 w-full whitespace-nowrap text-right"><p>${{ number_format($value, 2) }}</p></td>
                            </tr>

                            @php

                                $total = $total + $value;

                            @endphp

                        @endforeach

                        @php

                            echo " <tr class='text-gray-500 text-sm leading-relaxed'>
                                        <td class='px-2 w-full whitespace-nowrap font-bold'>Total</td>
                                        <td class='px-2 w-full whitespace-nowrap font-bold text-right'><p>$"  . number_format($total, 2) . "</p></td>
                                    </tr>
                                ";
                        @endphp

                    </tbody>

                </table>

            </div>

        @endif

        @if($regional2)

            <div class="bg-white rounded-xl mb-5 p-4">

                <p class="text-center tracking-wider font-semibold">Regional 2</p>

                <table class="w-full overflow-x-auto table-fixed">

                    <tbody class="divide-y divide-gray-200">

                        @php
                            $total = 0;
                        @endphp

                        @foreach ($regional2 as $key => $value)

                            <tr class="text-gray-500 text-sm leading-relaxed">
                                <td class=" px-2 w-full whitespace-nowrap"><p>{{ $key }}</p></td>
                                <td class=" px-2 w-full whitespace-nowrap text-right"><p>${{ number_format($value, 2) }}</p></td>
                            </tr>

                            @php

                                $total = $total + $value;

                            @endphp

                        @endforeach

                        @php

                            echo " <tr class='text-gray-500 text-sm leading-relaxed'>
                                        <td class='px-2 w-full whitespace-nowrap font-bold'>Total</td>
                                        <td class='px-2 w-full whitespace-nowrap font-bold text-right'><p>$"  . number_format($total, 2) . "</p></td>
                                    </tr>
                                ";
                        @endphp

                    </tbody>

                </table>

            </div>

        @endif

        @if($regional3)

            <div class="bg-white rounded-xl mb-5 p-4">

                <p class="text-center tracking-wider font-semibold">Regional 3</p>

                <table class="w-full overflow-x-auto table-fixed">

                    <tbody class="divide-y divide-gray-200">

                        @php
                            $total = 0;
                        @endphp

                        @foreach ($regional3 as $key => $value)

                            <tr class="text-gray-500 text-sm leading-relaxed">
                                <td class=" px-2 w-full whitespace-nowrap"><p>{{ $key }}</p></td>
                                <td class=" px-2 w-full whitespace-nowrap text-right"><p>${{ number_format($value, 2) }}</p></td>
                            </tr>

                            @php

                                $total = $total + $value;

                            @endphp

                        @endforeach

                        @php

                            echo " <tr class='text-gray-500 text-sm leading-relaxed'>
                                        <td class='px-2 w-full whitespace-nowrap font-bold'>Total</td>
                                        <td class='px-2 w-full whitespace-nowrap font-bold text-right'><p>$"  . number_format($total, 2) . "</p></td>
                                    </tr>
                                ";
                        @endphp

                    </tbody>

                </table>

            </div>

        @endif

        @if($regional4)

            <div class="bg-white rounded-xl mb-5 p-4">

                <p class="text-center tracking-wider font-semibold">Regional 4</p>

                <table class="w-full overflow-x-auto table-fixed">

                    <tbody class="divide-y divide-gray-200">

                        @php
                            $total = 0;
                        @endphp

                        @foreach ($regional4 as $key => $value)

                            <tr class="text-gray-500 text-sm leading-relaxed">
                                <td class=" px-2 w-full whitespace-nowrap"><p>{{ $key }}</p></td>
                                <td class=" px-2 w-full whitespace-nowrap text-right"><p>${{ number_format($value, 2) }}</p></td>
                            </tr>

                            @php

                                $total = $total + $value;

                            @endphp

                        @endforeach

                        @php

                            echo " <tr class='text-gray-500 text-sm leading-relaxed'>
                                        <td class='px-2 w-full whitespace-nowrap font-bold'>Total</td>
                                        <td class='px-2 w-full whitespace-nowrap font-bold text-right'><p>$"  . number_format($total, 2) . "</p></td>
                                    </tr>
                                ";
                        @endphp

                    </tbody>

                </table>

            </div>

        @endif

        @if($regional5)

            <div class="bg-white rounded-xl mb-5 p-4">

                <p class="text-center tracking-wider font-semibold">Regional 5</p>

                <table class="w-full overflow-x-auto table-fixed">

                    <tbody class="divide-y divide-gray-200">

                        @php
                            $total = 0;
                        @endphp

                        @foreach ($regional5 as $key => $value)

                            <tr class="text-gray-500 text-sm leading-relaxed">
                                <td class=" px-2 w-full whitespace-nowrap"><p>{{ $key }}</p></td>
                                <td class=" px-2 w-full whitespace-nowrap text-right"><p>${{ number_format($value, 2) }}</p></td>
                            </tr>

                            @php

                                $total = $total + $value;

                            @endphp

                        @endforeach

                        @php

                            echo " <tr class='text-gray-500 text-sm leading-relaxed'>
                                        <td class='px-2 w-full whitespace-nowrap font-bold'>Total</td>
                                        <td class='px-2 w-full whitespace-nowrap font-bold text-right'><p>$"  . number_format($total, 2) . "</p></td>
                                    </tr>
                                ";
                        @endphp

                    </tbody>

                </table>

            </div>

        @endif

        @if($regional6)

            <div class="bg-white rounded-xl mb-5 p-4">

                <p class="text-center tracking-wider font-semibold">Regional 6</p>

                <table class="w-full overflow-x-auto table-fixed">

                    <tbody class="divide-y divide-gray-200">

                        @php
                            $total = 0;
                        @endphp

                        @foreach ($regional6 as $key => $value)

                            <tr class="text-gray-500 text-sm leading-relaxed">
                                <td class=" px-2 w-full whitespace-nowrap"><p>{{ $key }}</p></td>
                                <td class=" px-2 w-full whitespace-nowrap text-right"><p>${{ number_format($value, 2) }}</p></td>
                            </tr>

                            @php

                                $total = $total + $value;

                            @endphp

                        @endforeach

                        @php

                            echo " <tr class='text-gray-500 text-sm leading-relaxed'>
                                        <td class='px-2 w-full whitespace-nowrap font-bold'>Total</td>
                                        <td class='px-2 w-full whitespace-nowrap font-bold text-right'><p>$"  . number_format($total, 2) . "</p></td>
                                    </tr>
                                ";
                        @endphp

                    </tbody>

                </table>

            </div>

        @endif

    </div>
</div>