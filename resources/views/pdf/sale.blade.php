<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>VENTA #{{ str_pad($sale->number, 5, '0', STR_PAD_LEFT) }}</title>
    <link rel="stylesheet" href="{{ public_path('css/pdf.utils.css') }}">
    <link rel="stylesheet" href="{{ public_path('css/pdf.css') }}">
</head>

<body>

    {{-- Header --}}
    <header class="header">
        <table class="w-full">
            <tr>
                <td class="logo-td" style="width: 160px">
                    <div class="logo" style="max-width: 160px">
                        <img src="{{ public_path('/storage/images/' . $company->logo) }}" alt="logo">
                    </div>
                </td>
                <td>
                    @include('pdf.partials.company')
                </td>
                <td class="" style="width: 160px">
                    <div class="inline-block border py-2 font-bold text-center" style="width: 160px">
                        <div>
                            {{ $sale->document->document_type_code == '01' ? 'FACTURA ELECTRÓNICA' : 'BOLETA DE VENTA ELECTRÓNICA' }}
                        </div>
                        <div>{{ $sale->document->serial->serial }}-{{ $sale->document->correlative }}</div>
                        <div> RUC:{{ $company->ruc }}</div>
                    </div>
                </td>
            </tr>
        </table>
    </header>

    {{-- Datos del documento --}}
    <table class="table-document-info">
        <tr>
            <td>Fecha de Emisión </td>
            <td>:</td>
            <td>{{ date('d/m/Y', strtotime($sale->created_at)) }}</td>
        </tr>
        <tr>
            <td>Señor(es)</td>
            <td>:</td>
            <td>{{ $entity->name }}</td>
        </tr>
        <tr>
            <td>Dirección del Cliente</td>
            <td>:</td>
            <td>{{ $entity->address }}</td>
        </tr>
        <tr>
            <td>Tipo de Moneda</td>
            <td>:</td>
            <td class="uppercase">{{ $currency->name }}</td>
        </tr>
        @if ($sale->purchase_order_number)
            <tr>
                <td>Orden de compra</td>
                <td>:</td>
                <td>{{ $sale->purchase_order_number }}</td>
            </tr>
        @endif
    </table>

    {{-- Tabla de detalles --}}
    <table class="table-details">
        <thead>
            <tr>
                <th>Pos</th>
                <th>Código</th>
                <th>Descripción</th>
                <th>Cantidad</th>
                <th>Unidad de Medida</th>
                <th class="text-right">Valor Unitario</th>
                <th class="text-right">Sub Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($details as $detail)
                @php
                    $detailUnitaryValue = $detail->price_base;
                    $detailSubTotal = $detail->price_base * (1 + $detail->tax->percentage) * $detail->quantity;
                @endphp
                <tr>
                    <td>1</td>
                    <td>{{ $detail->code }}</td>
                    <td>{{ $detail->description }}</td>
                    <td>{{ $detail->quantity }}</td>
                    <td>{{ $detail->unit->name }}</td>
                    <td class="text-right">
                        {{ number_format($detailUnitaryValue, 2) }}
                    </td>
                    <td class="text-right">
                        {{ number_format($detailSubTotal, 2) }}
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    {{-- Totales --}}
    <div class="relative" style="height: 90px;">
        <table class="absolute right-0 table-amounts">
            <tr>
                <td>Subtotal</td>
                <td>:</td>
                <td>{{ $currency->symbol }}
                    {{ $subTotal }}
                </td>
            </tr>
            {{-- @if ($sale->descuento_monto > 0)
                <tr>
                    <td>Descuentos</td>
                    <td>:</td>
                    <td>{{ $currency->symbol }}
                        {{ $descuentos }}
                    </td>
                </tr>
                <tr>
                    <td>Valor venta</td>
                    <td>:</td>
                    <td>{{ $currency->symbol }} {{ $valorVenta }}</td>
                </tr>
            @endif --}}
            <tr>
                <td>IGV</td>
                <td>:</td>
                <td>{{ $currency->symbol }} {{ $totalIgv }}</td>
            </tr>
            <tr>
                <td>Total</td>
                <td>:</td>
                <td>{{ $currency->symbol }} {{ $total }}</td>
            </tr>
        </table>
        <div class="text-amount absolute bottom-0 uppercase">
            SON: {{ $totalInLetters }}
        </div>

    </div>

    {{-- Pagos --}}
    <div style="margin-top: 2rem">
        <h3>Información del crédito</h3>
        <table>
            <tbody>
                <tr>
                    <td>Monto neto pendiente de pago</td>
                    <td>:</td>
                    <td>{{ $currency->symbol }} {{ $total }}</td>
                </tr>
                <tr>
                    <td>Total de Cuotas</td>
                    <td>:</td>
                    <td>{{ count($sale->payments) }}</td>
                </tr>
            </tbody>
        </table>
        <table class="table-payments text-center">
            <thead class="">
                <tr>
                    <th>Nro</th>
                    <th>Fecha</th>
                    <th>Monto</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($sale->payments as $idx => $payment)
                    <tr>
                        <td>{{ $idx + 1 }}</td>
                        <td>{{ date('d/m/Y', strtotime($payment->date)) }}</td>
                        <td>{{ $currency->symbol }} {{ $payment->amount }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    {{-- Observaciones --}}
    @if ($sale->observations)
        <div>
            <h3>Observaciones</h3>
            {{ $sale->observations }}
        </div>
    @endif
</body>

</html>
