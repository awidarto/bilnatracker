<style type="text/css">
    ul.note_list{
        list-style-type: none;
    }
    ul.note_list li{
        border-bottom: thin solid #ccc;
    }

    ul.pic_list li{
        list-style-type: none;
        display: inline;
        float: left;
        padding-right: 8px;
    }

    .statbox{
        display:inline-block;
    }

</style>

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
        <td>Total Jumlah Paket / Box</td>
        <td>{{ $order->number_of_package }}</td>
    </tr>
    <tr>
        <td>Status Internal Paling Akhir</td>
        <td>{{ Prefs::colorizestatus( $order->status, 'delivery') }}</td>
    </tr>
    <tr>
        <td>Status 3PL Paling Akhir</td>
        <td>{{ $order->logistic_status }}</td>
    </tr>
    <?php
        $logistic_status_ts  = '';
        if(isset($order->logistic_status_ts)){
            if($order->logistic_status_ts instanceOf MongoDate){
                $logistic_status_ts = date('Y-m-d H:i:s', $order0->logistic_status_ts->sec );
            }else{
                $logistic_status_ts = $order->$logistic_status_ts;
            }
        }
    ?>
    <tr>
        <td>Waktu Update 3PL Paling Akhir</td>
        <td>{{ $logistic_status_ts }}</td>
    </tr>
    <tr>
        <td>Posisi Paling Akhir</td>
        <td>{{ Prefs::getPosition('node_code',$order->position)->name .' ('. $order->position.')' }}</td>
    </tr>
    <tr>
        <td>Catatan Pengiriman</td>
        <td>{{ Prefs::getNotes( $order->delivery_id, false) }}</td>
    </tr>
    <tr>
        <td>Proof of Delivery</td>
        <td>
            {{ $order->picList }}

        </td>
    </tr>
</table>
