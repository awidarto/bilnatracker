<table style="width:100%;">
    <thead>
        <tr>
            <th>Date</th>
            <th>Status</th>
            <th>Picked Up</th>
            <th>Delivered</th>
        </tr>
    </thead>
@foreach($status as $stat)
    <tr>
        <td>{{ $stat['timestamp'] }}</td>
        <td>{{ $stat['status'] }}</td>
        <td>{{ $stat['pickup_time'] }}</td>
        <td>{{ $stat['delivery_time'] }}</td>
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
