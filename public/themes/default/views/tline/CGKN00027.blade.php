<?php

$bt = array();

foreach($status as $s=>$v){
    $bt[ strtotime( $v['time'] ) ] = $v;
}

krsort($bt);

//print_r($bt);
?>
<table style="width:100%;vertical-align:top;">
    <thead>
        <tr>
            <th>Date</th>
            <th>Status</th>
        </tr>
    </thead>
@foreach($bt as $s=>$v)
    <tr>
        <td style="vertical-align:top;" >{{ $v['time'] }}</td>
        <td style="vertical-align:top;" >
            <h3>{{ $v['status'] }}&nbsp;
            @if($v['receiver'] !='' && !is_null($v['receiver']))
              {{ $v['receiver'] }}
            @endif
            </h3>

            @if($v['description'] !='' && !is_null($v['description']))
            <p>
              {{ $v['description'] }}
            </p>
            @endif
        </td>
    </tr>
@endforeach
</table>

{{--

array (
  '_id' => new MongoId("56b424993ed3b17b5f8b512c"),
  'reference_no' => '426555',
  'cn_no' => '8016001300083',
  'cn_date' => '22-01-2016 00:00',
  'origin' => 'JAKARTA-BILNA.COM',
  'destination' => 'JAKARTA-vivi vivi',
  'status' => 'KIRIMAN DITERIMA OLEH',
  'time' => '23-01-2016 13:45',
  'location' => 'wiwie',
  'receiver' => 'wiwie',
  'description' => 'ibu nya',
  'ts' => new MongoDate(1453531500, 0),
  'consignee_logistic_id' => 'JET(Jaya expres)',
  'consignee_olshop_cust' => 'CGKN00027',
)

    --}}
