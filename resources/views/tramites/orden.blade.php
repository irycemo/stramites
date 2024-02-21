<!doctype html>
<html lang="es">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css"
        integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <title>Orden de Pago</title>
</head>

<style>

    body{
        font-size: 13px;
    }

    h1{
        font-size: 13;
        font:bold;
        margin: 0;
    }

    .header{
        margin-bottom: 5px;
        width: 100%;
        table-layout: fixed;
    }

    .data{
        text-align: right;
    }

    .table{
        width: 100%;
        table-layout: auto;
        margin: 5 0 5 0;
    }

    .th_table{
        background: #e2e2e2;
        padding: 5 0;
    }

    .content{
        border-bottom: 1px solid #ddd;
        padding: 5 20;
    }

    p{
        margin: 0;
    }

    img{
        width: 180px;
    }

    .text_center{
        text-align: center;
    }

    .footer{
        margin-top: 10px;
        width: 100%;
        font-size: 12px;
        font-weight: bold;
        text-align: center;

    }

    .footer tbody {
        vertical-align: top;
    }

    .linea{
        width: 50%;
        border-top: 1px solid black;
    }

    .page-break{
        page-break-after: always;
    }

    .titulo{
        font-size: 14px;
        font-weight: bold;
        margin: 0;
        text-align: center;
    }

    .leyenda{
        font-size: 9px;
    }

</style>

<body>

    <div>

        <table class="table">

            <thead>

                <tr>

                    <th width="10%">
                        <img src="{{ public_path('storage/img/logo2.png') }}" alt="Logotipo">
                    </th>

                    <th width="80%" style="vertical-align: middle">
                        <div >
                            <p style="font-size: 12px; text-align: center">GOBIERNO DEL ESTADO DE MICHOACÁN DE OCAMPO</p>
                            <p style="font-size: 12px; text-align: center">DIRECCIÓN DEL REGISTRO PÚBLICO DE LA PROPIEDAD</p>
                        </div>
                    </th>

                    <th width="10%" >
                        <p style="font-size: 10px; text-align: right;"><nobr>{{ now()->format('d-m-Y H:i:s') }}</nobr></p>
                    </th>

                </tr>

            </thead>

        </table>

        <h1 class="titulo">Orden de pago</h1>

        <table class="table">

            <thead>

                <tr>

                    <th>
                        @if($tramite->solicitante == 'Oficialia de partes')
                            <p>{{ $tramite->solicitante }}</p>
                        @endif
                        <p>Número de control: {{ $tramite->año }}-{{ $tramite->numero_control }} @if($tramite->adiciona) / {{ $tramite->adicionaAlTramite->año }}-{{ $tramite->adicionaAlTramite->numero_control }} @endif</p>
                        <p>Servicio: {{ $tramite->servicio->nombre }}
                            @if($tramite->adiciona)
                                / {{ $tramite->adicionaAlTramite->servicio->nombre }}
                            @endif
                        </p>
                        <p>Solicitante: {{ $tramite->nombre_solicitante }}</p>
                        <p>Tipo de servicio: {{ $tramite->tipo_servicio }}</p>
                        <p>Orden de pago: {{ $tramite->orden_de_pago }}</p>
                        <p>Total a pagar: ${{ number_format($tramite->monto, 2) }}</p>
                        @if($tramite->tomo) Tomo: {{ $tramite->tomo }}, @endif @if($tramite->registro) Registro: {{ $tramite->registro}} <br>@endif
                        <p>Distrito: {{ App\Constantes\Constantes::DISTRITOS[$tramite->distrito] }}, Sección: {{ $tramite->seccion}}</p>
                        @if($tramite->cantidad) Cantidad: {{ $tramite->cantidad}} <br>@endif
                        @if($tramite->observaciones)
                            <p>Observaciones:{{ $tramite->observaciones }}</p>
                        @endif
                    </th>

                    <th style="vertical-align: middle">

                        @if($tramite->solicitante != 'Oficialia de partes')

                            <div class="text-center" >

                                <p>La vigencia para el pago de este trámite es:</p>
                                <p>{{ $tramite->limite_de_pago->format('d-m-Y') }}.</p>

                                <p >Linea de captura:</p>
                                <img src="data:image/png;base64,{{ base64_encode($generatorPNG->getBarcode($tramite->linea_de_captura, $generatorPNG::TYPE_CODE_128)) }}">
                                <p>{{ $tramite->linea_de_captura }}</p>

                            </div>

                        @endif

                    </th>

                </tr>

            </thead>

        </table>

        <div class="footer">

            <p class="leyenda">EL USUARIO ACEPTA LOS DATOS QUE SE PLASMAN EN ESTA ORDEN DE PAGO, AL MOMENTO DE REALIZAR EL PAGO, SI DESPUES DE REALIZAR DICHO PAGO SE DETECTA ALGÚN ERROR EL SOLICITANTE DEBERÁ ACLARARLO Y TENDRÁ NUEVAMENTE QUE CUBRIR EL COSTO, ESTO DE CONFORMIDAD CON LO ESTABLECIDO EN EL ARTÍCULO 15, PÁRRAFO II DE LA LEY DE FUNCIÓN REGISTRAL Y CATASTRAL DEL ESTADO DE MICHOACÁN DE OCAMPO.</p>

            {{-- <p>Pago en OXXO. Cod. Banco: 012. Cod. Convenio: 50001</p> --}}

        </div>

    </div>

    <br>

    <div>

        <table class="table">

            <thead>

                <tr>

                    <th width="10%">
                        <img src="{{ public_path('storage/img/logo2.png') }}" alt="Logotipo">
                    </th>

                    <th width="80%" style="vertical-align: middle">
                        <div >
                            <p style="font-size: 12px; text-align: center">GOBIERNO DEL ESTADO DE MICHOACÁN DE OCAMPO</p>
                            <p style="font-size: 12px; text-align: center">DIRECCIÓN DEL REGISTRO PÚBLICO DE LA PROPIEDAD</p>
                        </div>
                    </th>

                    <th width="10%" >
                        <p style="font-size: 10px; text-align: right;"><nobr>{{ now()->format('d-m-Y H:i:s') }}</nobr></p>
                    </th>

                </tr>

            </thead>

        </table>

        <h1 class="titulo">Orden de pago</h1>

        <table class="table">

            <thead>

                <tr>

                    <th>
                        @if($tramite->solicitante == 'Oficialia de partes')
                            <p>{{ $tramite->solicitante }}</p>
                        @endif
                        <p>Número de control: {{ $tramite->año }}-{{ $tramite->numero_control }} @if($tramite->adiciona) / {{ $tramite->adicionaAlTramite->año }}-{{ $tramite->adicionaAlTramite->numero_control }} @endif</p>
                        <p>Servicio: {{ $tramite->servicio->nombre }}
                            @if($tramite->adiciona)
                                / {{ $tramite->adicionaAlTramite->servicio->nombre }}
                            @endif
                        </p>
                        <p>Solicitante: {{ $tramite->nombre_solicitante }}</p>
                        <p>Tipo de servicio: {{ $tramite->tipo_servicio }}</p>
                        <p>Orden de pago: {{ $tramite->orden_de_pago }}</p>
                        <p>Total a pagar: ${{ number_format($tramite->monto, 2) }}</p>
                        @if($tramite->tomo) Tomo: {{ $tramite->tomo }}, @endif @if($tramite->registro) Registro: {{ $tramite->registro}} <br>@endif
                        <p>Distrito: {{ App\Constantes\Constantes::DISTRITOS[$tramite->distrito] }}, Sección: {{ $tramite->seccion}}</p>
                        @if($tramite->cantidad) Cantidad: {{ $tramite->cantidad}} <br>@endif
                        @if($tramite->observaciones)
                            <p>Observaciones:{{ $tramite->observaciones }}</p>
                        @endif
                    </th>

                    <th style="vertical-align: middle">

                        @if($tramite->solicitante != 'Oficialia de partes')

                            <div class="text-center" >

                                <p>La vigencia para el pago de este trámite es:</p>
                                <p>{{ $tramite->limite_de_pago->format('d-m-Y') }}.</p>

                                <p >Linea de captura:</p>
                                <img src="data:image/png;base64,{{ base64_encode($generatorPNG->getBarcode($tramite->linea_de_captura, $generatorPNG::TYPE_CODE_128)) }}">
                                <p>{{ $tramite->linea_de_captura }}</p>

                            </div>

                        @endif

                    </th>

                </tr>

            </thead>

        </table>

        <div class="footer">

            <p class="leyenda">EL USUARIO ACEPTA LOS DATOS QUE SE PLASMAN EN ESTA ORDEN DE PAGO, AL MOMENTO DE REALIZAR EL PAGO, SI DESPUES DE REALIZAR DICHO PAGO SE DETECTA ALGÚN ERROR EL SOLICITANTE DEBERÁ ACLARARLO Y TENDRÁ NUEVAMENTE QUE CUBRIR EL COSTO, ESTO DE CONFORMIDAD CON LO ESTABLECIDO EN EL ARTÍCULO 15, PÁRRAFO II DE LA LEY DE FUNCIÓN REGISTRAL Y CATASTRAL DEL ESTADO DE MICHOACÁN DE OCAMPO.</p>

            {{-- <p>Pago en OXXO. Cod. Banco: 012. Cod. Convenio: 50001</p> --}}

        </div>

    </div>

</body>

</html>
