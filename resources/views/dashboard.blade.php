@extends('layouts.admin')

@section('content')

    @if(auth()->user()->hasRole('Administrador'))

        <div class=" mb-10">

            <h2 class="text-2xl tracking-widest py-3 px-6 text-gray-600 rounded-xl border-b-2 border-gray-500 font-semibold mb-6  bg-white">Estadisticas del mes actual (Todo el estado)</h2>

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

            <h2 class="text-2xl tracking-widest py-3 px-6 text-gray-600 rounded-xl border-b-2 border-gray-500 font-semibold mb-6  bg-white">Estadisticas del mes actual (Uruapan)</h2>

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

            <h2 class="text-2xl tracking-widest py-3 px-6 text-gray-600 rounded-xl border-b-2 border-gray-500 font-semibold mb-6  bg-white">Gráfica de trámites</h2>

            <div class="bg-white rounded-lg p-2 shadow-lg">

                <canvas id="tramitesChart" style="width: 100%; height: 400px;"></canvas>

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

            const colors = [
                ['#985F99', '#9684A1'],
                ['#595959', '#808F85'],
                ['#918868', '#CBD081'],
                ['#F7934C', '#CC5803'],
                ['#273043', '#9197AE'],
                ['#F02D3A', '#EFF6EE'],
                ['#000000', '#695B5C'],
                ['#4A5043', '#8AA1B1'],
                ['#A5B452', '#C8D96F'],
                ['#14591D', '#99AA38'],
                ['#003459', '#00A8E8'],
                ['#5C7457', '#C1BCAC'],
                ['#FF8360', '#E8E288'],
                ['#B96AC9', '#E980FC'],
                ['#63768D', '#8AC6D0'],
                ['#56445D', '#548687'],
                ['#8FBC94', '#C5E99B']
            ]

            const aux = {!! json_encode($data) !!}

            let dataArray = new Array();

            let aux2 = new Array();

            for(let key in aux){
                for (let key2 in aux[key]) {
                    aux2.push(aux[key][key2])
                }

                var color = colors[Math.floor(Math.random()*colors.length)]

                dataArray.push(
                    {
                        label: key,
                        data: aux2,
                        borderColor: color[0],
                        backgroundColor: color[1],
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

            const config = {
                type: 'line',
                data: data,
                options: {
                    locale:'es-MX',
                    responsive: true,
                    scales:{
                        y:{
                            ticks:{
                                callback:(value, index, values) => {
                                    return new Intl.NumberFormat('es-MX', {
                                        style: 'currency',
                                        currency: 'MXN',
                                    }).format(value);
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

