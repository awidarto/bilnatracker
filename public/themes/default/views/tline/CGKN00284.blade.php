<?php

$bt = array();
if(isset($status['connotes'])){
    foreach($status['connotes'] as $v){
        $bt[ strtotime( $v['cn_date'] ) ] = $v;
    }

    krsort($bt);
}


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
        <td style="vertical-align:top;" >{{ $v['cn_date'] }}</td>
        <td style="vertical-align:top;" >
            <h3>{{ $v['cn_status'] }}</h3>
            @if($v['cn_desc'] !='')
            <p>
              {{ preg_replace('/\].\[/', '<br />', $v['cn_desc'])  }}
            </p>
            @endif
        </td>
    </tr>
@endforeach
</table>
{{--
{{ print_r($status) }}

[KURIR :DIMAS AREA :SOLO] [PENERIMA :NOVIA HUB :PENERIMA LANGSUNG] [TANGGAL :16-12-2015 19:53] [STATUS :[DL] DITERIMA OLEH] [KETERANGAN :OK

array (
  '_id' => new MongoId("56bc3e393ed3b1df2a8b6917"),
  'cn_no' => '9026151800331',
  'cn_ref' => '453629',
  'cn_consignee_name' => '137933 YANSEN GUEST',
  'cn_date_str' => new MongoInt32(1455123600),
  'cn_date' => '11-02-2016 00:00',
  'origin' => 'JAKARTA',
  'destination' => 'MEDAN_KNO',
  'service' => 'REG',
  'laststatus' =>
  array (
    'cn_no' => '9026151800331',
    'status' => 'ENTRI',
    'status_alert' => 'ENTRI [TIME: 11-02-2016 10:33 LOCATION: CGK - KANTOR PUSAT]',
    'time' => '11-02-2016 10:33',
    'location' => 'CGK - KANTOR PUSAT',
  ),
  'connotes' =>
  array (
    0 =>
    array (
      'no' => new MongoInt32(1),
      'cn_no' => '9026151800331',
      'cn_status' => 'ENTRI VERIFIED',
      'cn_date_str' => new MongoInt32(1455170100),
      'cn_date' => '11-02-2016 12:55',
      'cn_desc' => 'DATA UPLOUD BILNA COD 11 FEB 2016 DATA 4.xlsx  ',
      'cn_locationby' => 'KANTOR PUSAT / SETO',
      'cn_receiveby' => NULL,
      'status' => NULL,
    ),
  ),
  'ts' => new MongoDate(1455161580, 0),
  'consignee_logistic_id' => 'SAP',
  'consignee_olshop_cust' => 'CGKN00284',
)

--}}