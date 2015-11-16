<table class="table" >
    <tr>
        <td>No Order</td>
        <td>{{ $order->no_sales_order }}</td>
    </tr>
    <tr>
        <td>No Fulfillment</td>
        <td>{{ $order->fulfillment_code }}</td>
    </tr>
    <tr>
        <td>Nama Customer</td>
        <td>{{ $order->consignee_olshop_name }}</td>
    </tr>
    <tr>
        <td>Alamat Customer</td>
        <td>{{ $order->consignee_olshop_addr }}</td>
    </tr>
    <tr>
        <td>Nama Shipper</td>
        <td>{{ $order->logistic }}</td>
    </tr>
    <tr>
        <td>Tipe Shipper</td>
        <td>{{ $order->logistic_type }}</td>
    </tr>
    <tr>
        <td>No AWB</td>
        <td>{{ $order->awb }}</td>
    </tr>
    <tr>
        <td>Status Paling Akhir</td>
        <td>{{ Prefs::colorizestatus( $order->status, 'delivery') }}</td>
    </tr>
    <tr>
        <td>Posisi Paling Akhir</td>
        <td>{{ Prefs::getPosition('node_code',$order->position)->name .' '. $order->position }}</td>
    </tr>
    <tr>
        <td>POD</td>
        <td>
            {{ $order->picList }}

        </td>
    </tr>
</table>