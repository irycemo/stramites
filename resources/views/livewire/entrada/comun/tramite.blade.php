<div class="grid grid-cols-1 lg:grid-cols-2 gap-2">

    <div class="rounded-lg bg-gray-100 py-1 px-2">

        <p><strong>Número de control:</strong> {{ $tramite->año }}-{{ $tramite->numero_control }}-{{ $tramite->usuario }} </p>

    </div>

    <div class="rounded-lg bg-gray-100 py-1 px-2">

        <p><strong>Estado:</strong> {{ Str::ucfirst($tramite->estado) }}</p>

    </div>

    <div class="rounded-lg bg-gray-100 py-1 px-2">

        <p><strong>Categoría:</strong> {{ $tramite->servicio->categoria->nombre }}</p>

    </div>

    <div class="rounded-lg bg-gray-100 py-1 px-2">

        <p><strong>Servicio:</strong> {{ $tramite->servicio->nombre }}</p>

    </div>

    <div class="rounded-lg bg-gray-100 py-1 px-2">

        <p><strong>Tipo de servicio:</strong> {{ Str::ucfirst($tramite->tipo_servicio) }}</p>

    </div>

    @if ($tramite->tomo)

        <div class="rounded-lg bg-gray-100 py-1 px-2">

            <p><strong>Tomo:</strong> {{ $tramite->tomo }}</p>

        </div>

    @endif

    @if ($tramite->registro)

        <div class="rounded-lg bg-gray-100 py-1 px-2">

            <p><strong>Registro:</strong> {{ $tramite->registro }}</p>

        </div>

    @endif

    @if ($tramite->distrito)

        <div class="rounded-lg bg-gray-100 py-1 px-2">

            <p><strong>Distrito:</strong> {{ $tramite->distrito }}</p>

        </div>

    @endif

    @if ($tramite->seccion)

        <div class="rounded-lg bg-gray-100 py-1 px-2">

            <p><strong>Sección:</strong> {{ $tramite->seccion }}</p>

        </div>

    @endif

    @if ($tramite->numero_oficio)

        <div class="rounded-lg bg-gray-100 py-1 px-2">

            <p><strong>Número de oficio:</strong> {{ $tramite->numero_oficio }}</p>

        </div>

    @endif

    @if ($tramite->numero_notaria)

        <div class="rounded-lg bg-gray-100 py-1 px-2">

            <p><strong>Número de notaría:</strong> {{ $tramite->numero_notaria }}</p>

        </div>

    @endif

    @if ($tramite->nombre_notario)

        <div class="rounded-lg bg-gray-100 py-1 px-2">

            <p><strong>Nombre del notarío:</strong> {{ $tramite->nombre_notario }}</p>

        </div>

    @endif

    @if ($tramite->cantidad)

        <div class="rounded-lg bg-gray-100 py-1 px-2">

            <p><strong>Cantidad:</strong> {{ $tramite->cantidad }}</p>

        </div>

    @endif

    @if ($tramite->numero_inmuebles)

        <div class="rounded-lg bg-gray-100 py-1 px-2">

            <p><strong>Número de inmuebles:</strong> {{ $tramite->numero_inmuebles }}</p>

        </div>

    @endif

    <div class="rounded-lg bg-gray-100 py-1 px-2">

        <p><strong>Solicitante:</strong>{{ $tramite->solicitante }} / {{ $tramite->nombre_solicitante }}</p>

    </div>

    <div class="rounded-lg bg-gray-100 py-1 px-2">

        <p><strong>Monto:</strong> ${{ number_format($tramite->monto, 2) }}</p>

    </div>

    @if ($tramite->fecha_entrega)

        <div class="rounded-lg bg-gray-100 py-1 px-2">

            <p><strong>Fecha de entrega:</strong> {{ $tramite->fecha_entrega->format('d-m-Y') }}</p>

        </div>

    @endif

    @if ($tramite->limite_de_pago)

        <div class="rounded-lg bg-gray-100 py-1 px-2">

            <p><strong>Límite de pago:</strong> {{ $tramite->limite_de_pago }}</p>

        </div>

    @endif

    <div class="rounded-lg bg-gray-100 py-1 px-2">

        <p><strong>Registrado por:</strong> {{ $tramite->creadoPor->name }} el {{ $tramite->created_at }}</p>

    </div>

</div>
