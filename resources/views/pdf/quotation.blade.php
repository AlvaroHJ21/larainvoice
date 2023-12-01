<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
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
                        {{-- <img src="{{ public_path('/storage/images/' . $company->logo) }}" alt="logo"> --}}
                    </div>
                </td>
                <td>
                    <table class="text-sm table-empresa">
                        <tr>
                            <td colspan="3">
                                {{-- <div class="font-bold uppercase">{{ $empresa->razon_social }}</div> --}}
                                <div class="font-bold uppercase">RAZON SOCIAL</div>
                            </td>
                        </tr>
                        <tr>
                            <td>WhatsApp</td>
                            <td style="width: 10px">:</td>
                            {{-- <td>{{ $empresa->telefono_movil }}</td> --}}
                            <td>999999999</td>
                        </tr>
                        <tr>
                            <td>E-Mail</td>
                            <td>:</td>
                            {{-- <td>{{ $empresa->email }}</td> --}}
                            <td>someemail@email.com</td>
                        </tr>
                        <tr>
                            <td>Webside</td>
                            <td>:</td>
                            {{-- <td>{{ $empresa->web }}</td> --}}
                            <td>www.some.com</td>
                        </tr>
                    </table>
                </td>
                <td class="" style="width: 160px">
                    <div class="inline-block border py-2 font-bold text-center" style="width: 160px">
                        <div>COTIZACIÓN</div>
                        <div>#{{ str_pad($quotation->number, 5, '0', STR_PAD_LEFT) }}</div>
                        {{-- <div> RUC:{{ $empresa->ruc }}</div> --}}
                        <div> RUC:11111111111</div>
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
            <td>{{ date('d/m/Y', strtotime($quotation->created_at)) }}</td>
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
            <td class="uppercase">{{ $currency->nombre }}</td>
        </tr>
    </table>

    {{-- Tabla de detalles --}}
    <table class="table-details">
        <thead>
            <tr>
                <th>Pos</th>
                <th>Código</th>
                <th>Descripción</th>
                <th>Imagen Referencial</th>
                <th>Cantidad</th>
                <th>Unidad de Medida</th>
                <th class="text-right">Valor Unitario</th>
                <th class="text-right">Sub Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($details as $detail)
                @php
                    // $subTotal += $detail->selling_price * $detail->cantidad;
                    // $totalIgv += $detail->selling_price * $detail->cantidad * $detail->tax->percentage;
                    // $total += $detail->selling_price * (1 + $detail->tax->percentage) * $detail->cantidad;

                    $detailUnitaryValue = $detail->price_base;
                    $detailSubTotal = $detail->price_base * (1 + $detail->tax->percentage) * $detail->quantity;
                @endphp
                <tr>
                    <td>1</td>
                    <td>{{ $detail->code }}</td>
                    <td>{{ $detail->description }}</td>
                    <td>
                        @if ($detail->product->image == null)
                            <img src="{{ public_path('/storage/images/static/no-image.jpg') }}" width="100">
                        @else
                            <img src="{{ public_path('/storage/images/' . $detail->product->image) }}" width="100">
                        @endif
                    </td>
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
            {{-- @if ($quotation->descuento_monto > 0)
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

    {{-- <pre>
        {{ json_encode($empresa, JSON_PRETTY_PRINT) }}
        {{ public_path('storage/' . $empresa->logo) }}
    </pre> --}}
</body>

</html>
