<?php

$st = array();

foreach ($status as $s) {
  if($s['status'] == 'pending'){
    $st[$s['status'].'_'.$s['pending'] ] = array('status'=>$s['status'].'_'.$s['pending'],'timestamp'=>$s['timestamp'],'pending'=>$s['pending'],'note'=>$s['note']);

  }else{
    $st[$s['status']] = array('status'=>$s['status'],'timestamp'=>$s['timestamp'],'pending'=>$s['pending'],'note'=>$s['note']);

  }

  if($s['pickup_time'] != '0000-00-00 00:00:00'){
    $st['picked_up'] = array('status'=>'picked_up','timestamp'=>$s['pickup_time'],'pending'=>$s['pending'],'note'=>$s['note']);
  }

}

$bt = array();

foreach($st as $s=>$v){
    $bt[ strtotime( $v['timestamp'] ) ] = $v;
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
        <td style="vertical-align:top;" >{{ $v['timestamp'] }}</td>
        <td style="vertical-align:top;" >
            <h3>{{ $v['status'] }}</h3>
            @if($v['note'] !='')
            <p>
              {{ $v['note'] }}
            </p>
            @endif
        </td>
    </tr>
@endforeach
</table>

{{--


'_id' => new MongoId("56bc3a673ed3b1062c8b4b42"),
  'awb' => '007735-31-102015-00142150',
  'timestamp' => '2016-02-11 07:38:15',
  'pending' => '0',
  'district' => 'Kalideres',
  'status' => 'canceled',
  'pickup_time' => '0000-00-00 00:00:00',
  'delivery_time' => NULL,
  'pod' =>
  array (
  ),
  'note' => '',
  'ts' => new MongoDate(1455151095, 0),
  'consignee_logistic_id'▼ => '7735',
  'consignee_olshop_cust' => 'JEX(Jayon)',
)

    --}}
