<?php

$bt = array();

foreach($status as $s=>$v){
    $bt[ strtotime( $v['datetime'] ) ] = $v;
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
        <td style="vertical-align:top;" >{{ $v['datetime'] }}</td>
        <td style="vertical-align:top;" >
            <h3>{{ $v['status'] }}</h3>
            @if($v['status_note'] !='' && !is_null($v['status_note']))
            <p>
              {{ $v['status_note'] }}
            </p>
            @endif
        </td>
    </tr>
@endforeach
</table>

{{--


array (
  '_id' => new MongoId("56bc41c23ed3b1182b8b539a"),
  'datetime' => '2016-02-11 14:50:49',
  'status' => 'DELIVERY',
  'branch_id' => 'SUB',
  'branch' => 'SURABAYA, 21 EXP',
  'status_by' => 'AGEN MADIUN',
  'status_note' => NULL,
  'ts' => new MongoDate(1455177049, 0),
  'raw' => new MongoInt32(0),
  'awb' => '101410007792',
  'consignee_logistic_id' => '21Express ',
  'consignee_olshop_cust' => 'B234-JKT',
)
    --}}
