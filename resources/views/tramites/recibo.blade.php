<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Recibo</title>
</head>

<style>

    @page{
        size:58mm 200mm;
        margin: 5;
    }

    #wrapper{
        color: #000;
        font-family: Arial,Helvetica;
    }

    .receipt-header{
        margin-bottom: 20px;
    }

    .receipt-header h1{
        font-family: Arial,Helvetica;
        font-size: 12px;
        text-align: center;
    }

    .content{
        font-size: 14px;
    }

    .content p{
        margin: 0;
        margin-bottom: 5px;
    }

    .title{
        text-align: center;
    }

    .total{
        font-size: 14px;
    }

    .footer p{
        margin:0;
        font-size: 10px;
    }

</style>

<body>
    <div id="wrapper">

        <div class="receipt-header">

            <h1>GOBIERNO DEL ESTADO DE MICHOACÁN DE OCAMPO DIRECCIÓN DEL REGISTRO PÚBLICO DE LA PROPIEDAD</h1>

        </div>

        <div class="content">

            <p class="title">CALIFICACIÓN DE DOCUMENTO</p>
            <p><strong>Fecha:</strong> {{Carbon\Carbon::now()->format('d-m-Y')}}</p>
            <p><strong>No. Control:</strong> {{ $tramite->año }}-{{ $tramite->numero_control }}-{{ $tramite->usuario }} @if($tramite->adiciona) / {{ $tramite->adicionaAlTramite->año }}-{{ $tramite->adicionaAlTramite->numero_control }}-{{ $tramite->adicionaAlTramite->usuario }} @endif</p>
            <p><strong>Servicio:</strong> {{ $tramite->servicio->nombre }}
                @if($tramite->adiciona)
                    / {{ $tramite->adicionaAlTramite->servicio->nombre }}
                @endif
            </p>
            <p><strong>Solicitante:</strong> {{ $tramite->nombre_solicitante }}</p>
            <p><strong>Tipo de servicio:</strong> {{ $tramite->tipo_servicio }}</p>
            @if($tramite->folio_real)
                <p><strong>Folio real:</strong> {{ $tramite->folio_real }},</p>
            @else
                @if($tramite->tomo) <p><strong>Tomo:</strong> {{ $tramite->tomo }}, <strong>Registro:</strong> {{ $tramite->registro}}</p>@endif
                @if($tramite->distrito)<p><strong>Distrito:</strong> {{ App\Constantes\Constantes::DISTRITOS[$tramite->distrito] }}</p>@endif
                @if($tramite->seccion)<p><strong>Sección:</strong> {{ $tramite->seccion }}</p>@endif
            @endif
            @if($tramite->tomo_gravamen) <p><strong>Tomo gravamen:</strong> {{ $tramite->tomo_gravamen }}, <strong>Registro gravamen:</strong> {{ $tramite->registro_gravamen}}</p>@endif
            @if($tramite->cantidad) <p><strong>Cantidad:</strong> {{ $tramite->cantidad}} </p>@endif
            @if($tramite->tipo_documento) <p><strong>Tipo de documento:</strong> {{ $tramite->tipo_documento}}</p>@endif
            @if($tramite->numero_documento)  <p><strong>Número de documento:</strong> {{ $tramite->numero_documento}}</p>@endif
            @if($tramite->valor_propiedad) <p><strong> Valor de la propiedad:</strong> {{ $tramite->valor_propiedad}} </p>@endif
            <p><strong>Orden de pago:</strong> {{ $tramite->orden_de_pago }}</p>
            <p><strong>Linea de captura:</strong> {{ $tramite->linea_de_captura }}</p>
            <p><strong>Precalificó:</strong> {{ $tramite->creadoPor->name }}</p>

        </div>

        <div class="total">

            <p>
                Total a pagar: ${{ number_format($tramite->monto, 2) }}
            </p>

        </div>

        <div class="footer">
            <p style="margin-bottom: 5px">LA VIGENCIA PARA EL PAGO DE ESTE TRÁMITE ES: {{ $tramite->limite_de_pago->format('d-m-Y') }}.</p>
            {{-- <p>Pago en OXXO. Cod. Banco: 012. Cod. Convenio: 50001</p> --}}
            <hr>
            <p>VERIFICAR LOS DATOS ANTES DE PAGAR</p>
        </div>

    </div>

</body>
</html>
