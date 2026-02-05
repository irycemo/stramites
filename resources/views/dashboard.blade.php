@extends('layouts.admin')

@section('content')

    @if(auth()->user()->hasRole('Administrador'))

        <div class=" mb-10">

            <x-header>Estadisticas del mes actual (Todo el estado)</x-header>

            <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-5 gap-4 mb-5">

                <div class="flex md:block justify-evenly items-center space-x-2 border-t-4 border-blue-400 p-4 shadow-xl text-gray-600 rounded-xl bg-white text-center">

                    <div class="  mb-2 items-center">

                        <span class="font-bold text-2xl text-blueGray-600 mb-2">

                            @if($tramtiesEstado->where('estado', 'nuevo')->count())
                                {{ $tramtiesEstado->where('estado', 'nuevo')->first()->count }}
                            @else
                                0
                            @endif

                        </span>

                        <h5 class="text-blueGray-400 uppercase font-semibold text-center  tracking-widest md:tracking-normal">Nuevos</h5>

                    </div>

                </div>

                <div class="flex md:block justify-evenly items-center space-x-2 border-t-4 border-green-400 p-4 shadow-xl text-gray-600 rounded-xl bg-white text-center">

                    <div class="  mb-2 items-center">

                        <span class="font-bold text-2xl text-blueGray-600 mb-2">

                            @if($tramtiesEstado->where('estado', 'pagado')->count())
                                {{ $tramtiesEstado->where('estado', 'pagado')->first()->count }}
                            @else
                                0
                            @endif

                        </span>

                        <h5 class="text-blueGray-400 uppercase font-semibold text-center  tracking-widest md:tracking-normal">Pagados</h5>

                    </div>

                </div>

                <div class="flex md:block justify-evenly items-center space-x-2 border-t-4 border-gray-400 p-4 shadow-xl text-gray-600 rounded-xl bg-white text-center">

                    <div class="  mb-2 items-center">

                        <span class="font-bold text-2xl text-blueGray-600 mb-2">

                            @if($tramtiesEstado->where('estado', 'concluido')->count())
                                {{ $tramtiesEstado->where('estado', 'concluido')->first()->count }}
                            @else
                                0
                            @endif

                        </span>

                        <h5 class="text-blueGray-400 uppercase font-semibold text-center  tracking-widest md:tracking-normal">Concluidos</h5>

                    </div>

                </div>

                <div class="flex md:block justify-evenly items-center space-x-2 border-t-4 border-red-400 p-4 shadow-xl text-gray-600 rounded-xl bg-white text-center">

                    <div class="  mb-2 items-center">

                        <span class="font-bold text-2xl text-blueGray-600 mb-2">

                            @if($tramtiesEstado->where('estado', 'rechazado')->count())
                                {{ $tramtiesEstado->where('estado', 'rechazado')->first()->count }}
                            @else
                                0
                            @endif

                        </span>

                        <h5 class="text-blueGray-400 uppercase font-semibold text-center  tracking-widest md:tracking-normal">Rechazados</h5>

                    </div>

                </div>

                <div class="flex md:block justify-evenly items-center space-x-2 border-t-4 border-red-400 p-4 shadow-xl text-gray-600 rounded-xl bg-white text-center">

                    <div class="  mb-2 items-center">

                        <span class="font-bold text-2xl text-blueGray-600 mb-2">

                            @if($tramtiesEstado->where('estado', 'expirado')->count())
                                {{ $tramtiesEstado->where('estado', 'expirado')->first()->count }}
                            @else
                                0
                            @endif

                        </span>

                        <h5 class="text-blueGray-400 uppercase font-semibold text-center  tracking-widest md:tracking-normal">Expirados</h5>

                    </div>

                </div>

            </div>

            <x-header>Estadisticas del mes actual (Uruapan)</x-header>

            <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-5 gap-4">

                <div class="flex md:block justify-evenly items-center space-x-2 border-t-4 border-blue-400 p-4 shadow-xl text-gray-600 rounded-xl bg-white text-center">

                    <div class="  mb-2 items-center">

                        <span class="font-bold text-2xl text-blueGray-600 mb-2">

                            @if($tramtiesUruapan->where('estado', 'nuevo')->count())
                                {{ $tramtiesUruapan->where('estado', 'nuevo')->first()->count }}
                            @else
                                0
                            @endif

                        </span>

                        <h5 class="text-blueGray-400 uppercase font-semibold text-center  tracking-widest md:tracking-normal">Nuevos</h5>

                    </div>

                </div>

                <div class="flex md:block justify-evenly items-center space-x-2 border-t-4 border-green-400 p-4 shadow-xl text-gray-600 rounded-xl bg-white text-center">

                    <div class="  mb-2 items-center">

                        <span class="font-bold text-2xl text-blueGray-600 mb-2">

                            @if($tramtiesUruapan->where('estado', 'pagado')->count())
                                {{ $tramtiesUruapan->where('estado', 'pagado')->first()->count }}
                            @else
                                0
                            @endif

                        </span>

                        <h5 class="text-blueGray-400 uppercase font-semibold text-center  tracking-widest md:tracking-normal">Pagados</h5>

                    </div>

                </div>

                <div class="flex md:block justify-evenly items-center space-x-2 border-t-4 border-gray-400 p-4 shadow-xl text-gray-600 rounded-xl bg-white text-center">

                    <div class="  mb-2 items-center">

                        <span class="font-bold text-2xl text-blueGray-600 mb-2">

                            @if($tramtiesUruapan->where('estado', 'concluido')->count())
                                {{ $tramtiesUruapan->where('estado', 'concluido')->first()->count }}
                            @else
                                0
                            @endif

                        </span>

                        <h5 class="text-blueGray-400 uppercase font-semibold text-center  tracking-widest md:tracking-normal">Concluidos</h5>

                    </div>

                </div>

                <div class="flex md:block justify-evenly items-center space-x-2 border-t-4 border-red-400 p-4 shadow-xl text-gray-600 rounded-xl bg-white text-center">

                    <div class="  mb-2 items-center">

                        <span class="font-bold text-2xl text-blueGray-600 mb-2">

                            @if($tramtiesUruapan->where('estado', 'rechazado')->count())
                                {{ $tramtiesUruapan->where('estado', 'rechazado')->first()->count }}
                            @else
                                0
                            @endif

                        </span>

                        <h5 class="text-blueGray-400 uppercase font-semibold text-center  tracking-widest md:tracking-normal">Rechazados</h5>

                    </div>

                </div>

                <div class="flex md:block justify-evenly items-center space-x-2 border-t-4 border-red-400 p-4 shadow-xl text-gray-600 rounded-xl bg-white text-center">

                    <div class="  mb-2 items-center">

                        <span class="font-bold text-2xl text-blueGray-600 mb-2">

                            @if($tramtiesUruapan->where('estado', 'expirado')->count())
                                {{ $tramtiesUruapan->where('estado', 'expirado')->first()->count }}
                            @else
                                0
                            @endif

                        </span>

                        <h5 class="text-blueGray-400 uppercase font-semibold text-center  tracking-widest md:tracking-normal">Expirados</h5>

                    </div>

                </div>

            </div>

        </div>

        <div class="mb-10">

            <x-header>Gráfica de trámites</x-header>

            <div class="bg-white rounded-lg p-2 shadow-lg">

                <canvas id="tramitesChart" style="width: 100%; height: 400px;"></canvas>

            </div>

        </div>

    @elseif(auth()->user()->ubicacion == 'Regional 4')

        <x-header>Estadisticas del mes actual</x-header>

        <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-5 gap-4">

            <div class="flex md:block justify-evenly items-center space-x-2 border-t-4 border-blue-400 p-4 shadow-xl text-gray-600 rounded-xl bg-white text-center">

                <div class="  mb-2 items-center">

                    <span class="font-bold text-2xl text-blueGray-600 mb-2">

                        @if($tramtiesUruapan->where('estado', 'nuevo')->count())
                            {{ $tramtiesUruapan->where('estado', 'nuevo')->first()->count }}
                        @else
                            0
                        @endif

                    </span>

                    <h5 class="text-blueGray-400 uppercase font-semibold text-center  tracking-widest md:tracking-normal">Nuevos</h5>

                </div>

            </div>

            <div class="flex md:block justify-evenly items-center space-x-2 border-t-4 border-green-400 p-4 shadow-xl text-gray-600 rounded-xl bg-white text-center">

                <div class="  mb-2 items-center">

                    <span class="font-bold text-2xl text-blueGray-600 mb-2">

                        @if($tramtiesUruapan->where('estado', 'pagado')->count())
                            {{ $tramtiesUruapan->where('estado', 'pagado')->first()->count }}
                        @else
                            0
                        @endif

                    </span>

                    <h5 class="text-blueGray-400 uppercase font-semibold text-center  tracking-widest md:tracking-normal">Pagados</h5>

                </div>

            </div>

            <div class="flex md:block justify-evenly items-center space-x-2 border-t-4 border-gray-400 p-4 shadow-xl text-gray-600 rounded-xl bg-white text-center">

                <div class="  mb-2 items-center">

                    <span class="font-bold text-2xl text-blueGray-600 mb-2">

                        @if($tramtiesUruapan->where('estado', 'concluido')->count())
                            {{ $tramtiesUruapan->where('estado', 'concluido')->first()->count }}
                        @else
                            0
                        @endif

                    </span>

                    <h5 class="text-blueGray-400 uppercase font-semibold text-center  tracking-widest md:tracking-normal">Concluidos</h5>

                </div>

            </div>

            <div class="flex md:block justify-evenly items-center space-x-2 border-t-4 border-red-400 p-4 shadow-xl text-gray-600 rounded-xl bg-white text-center">

                <div class="  mb-2 items-center">

                    <span class="font-bold text-2xl text-blueGray-600 mb-2">

                        @if($tramtiesUruapan->where('estado', 'rechazado')->count())
                            {{ $tramtiesUruapan->where('estado', 'rechazado')->first()->count }}
                        @else
                            0
                        @endif

                    </span>

                    <h5 class="text-blueGray-400 uppercase font-semibold text-center  tracking-widest md:tracking-normal">Rechazados</h5>

                </div>

            </div>

            <div class="flex md:block justify-evenly items-center space-x-2 border-t-4 border-red-400 p-4 shadow-xl text-gray-600 rounded-xl bg-white text-center">

                <div class="  mb-2 items-center">

                    <span class="font-bold text-2xl text-blueGray-600 mb-2">

                        @if($tramtiesUruapan->where('estado', 'expirado')->count())
                            {{ $tramtiesUruapan->where('estado', 'expirado')->first()->count }}
                        @else
                            0
                        @endif

                    </span>

                    <h5 class="text-blueGray-400 uppercase font-semibold text-center  tracking-widest md:tracking-normal">Expirados</h5>

                </div>

            </div>

        </div>

    @else

        <div class="mx-auto flex justify-center items-center h-full">
            <img src="{{ asset('storage/img/logo.png') }}" alt="Logo" class="w-96">
        </div>

    @endif

@endsection

@if(auth()->user()->hasRole('Administrador'))

    @push('scripts')

        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

        <script>

        function generateRandomHexColor() {

        // Generate a random number between 0 and 16777215 (0xFFFFFF)
        const randomColor = Math.floor(Math.random() * 16777215).toString(16);

        // Pad the hex string with leading zeros if necessary to ensure 6 characters
        return `#${randomColor.padStart(6, '0')}`;

        }

        function getInverseHexColor(hexColor) {

        // Remove the '#' if present
        const cleanHex = hexColor.startsWith('#') ? hexColor.slice(1) : hexColor;

        // Convert the hex color to a decimal integer
        const num = parseInt(cleanHex, 16);

        // Invert the color by XORing with 0xFFFFFF (white)
        const invertedNum = 0xFFFFFF ^ num;

        // Convert the inverted decimal back to a hex string
        const invertedHex = invertedNum.toString(16);

        // Pad with leading zeros and add '#'
        return `#${invertedHex.padStart(6, '0')}`;

        }

        const aux = {!! json_encode($data) !!}

        let dataArray = new Array();

        let aux2 = new Array();

        for(let key in aux){

        for (let key2 in aux[key]) {

            aux2.push(aux[key][key2])

        }

        var color = generateRandomHexColor();

        var inverse_color = getInverseHexColor(color);

        dataArray.push(
            {
                label: key,
                data: aux2,
                borderColor: color,
                backgroundColor: inverse_color,
                pointStyle: 'circle',
                pointRadius: 5,
                pointHoverRadius: 10
            }
        )

        aux2 = new Array();

        }

        const labels=  ["Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre"];

        const data = {
        labels: labels,
        datasets:dataArray
        }

        console.log(window.screen.width);

        const config = {
        type: 'line',
        data: data,
        options: {
            locale:'es-MX',
            responsive: true,
            scales:{
                y:{
                    ticks:{
                            display: window.screen.width > 500,
                            callback:(value, index, values) => {
                                return new Intl.NumberFormat('es-MX', {
                                                                        style: 'currency',
                                                                        currency: 'MXN',
                                                                    }
                                                            ).format(value);
                            }
                    },
                    beginAtZero: true
                }
            },
            plugins: {
                legend: {
                    position: 'top',
                },
                title: {
                    display: false,
                    text: 'Gráfica de entradas'
                },
                tooltip: {
                    callbacks: {
                        label: function(context){
                            return `${context.dataset.label}: $${context.formattedValue}`;
                        }
                    }
                }
            }
        },
        };

        const myChart = new Chart(
        document.getElementById('tramitesChart'),
        config
        );

        </script>

    @endpush

@endif

