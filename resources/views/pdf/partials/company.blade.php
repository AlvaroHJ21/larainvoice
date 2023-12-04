<table class="text-sm table-empresa">
    <tr>
        <td colspan="3">
            <div class="font-bold uppercase">{{ $company->business_name }}</div>
        </td>
    </tr>
    <tr>
        <td>WhatsApp</td>
        <td style="width: 10px">:</td>
        <td>{{ $company->phone }}</td>
    </tr>
    <tr>
        <td>E-Mail</td>
        <td>:</td>
        <td>{{ $company->email }}</td>
    </tr>
    <tr>
        <td>Webside</td>
        <td>:</td>
        <td>{{ $company->website }}</td>
    </tr>
</table>
