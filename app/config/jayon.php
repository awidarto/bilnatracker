<?php

$config = array();

$config['site_title']   = 'Jayon Express Admin';

$config['infinite_scroll'] = false;

$config['deliverytype_selector'] = array(
    'noid'=>'All',
    'COD'=>'COD/CCOD',
    'DO'=>'DO',
    'PS'=>'PS'
    );

$config['deliverytype_selector_legacy'] = array(
    'noid'=>'All',
    'COD'=>'COD/CCOD',
    'Delivery Only'=>'DO',
    'PS'=>'PS'
    );

$config['main_db'] = 'jayonexpress_main';
/*table names*/
$config['incoming_delivery_table'] = 'delivery_order_active';
$config['assigned_delivery_table'] = 'delivery_order_active';
$config['delivered_delivery_table'] = 'delivery_order_active';
$config['archived_delivery_table'] = 'delivery_order_archive';

$config['delivery_details_table'] = 'delivery_order_details';

$config['applications_table'] = 'applications';
$config['delivery_log_table'] = 'delivery_log';
$config['location_log_table'] = 'location_log';
$config['access_log_table'] = 'api_access_log';
$config['sequence_table'] = 'applications';
$config['device_assignment_table'] = 'device_courier_log';

$config['invoice_table'] = 'released_invoices';
$config['manifest_table'] = 'released_manifests';
$config['docs_table'] = 'released_docs';
$config['deliverytime_table'] = 'released_deliverytime';
$config['phototag_table'] = 'delivery_photos';



$config['jayon_delivery_fee_table'] = 'weight_tariff';
$config['jayon_cod_fee_table'] = 'cod_surcharge';
$config['jayon_pickup_fee_table'] = 'pickup_tariff';

$config['jayon_prepaid_table'] = 'prepaid_delivery';

$config['jayon_buyers_table'] = 'buyers';
$config['jayon_members_table'] = 'members';
$config['jayon_couriers_table'] = 'couriers';
$config['jayon_holidays_table'] = 'holidays';
$config['jayon_devices_table'] = 'devices';
$config['jayon_options_table'] = 'options';
$config['jayon_zones_table'] = 'districts';
$config['jayon_timeslots_table'] = 'time_slots';
$config['jayon_email_outbox_table'] = 'email_outbox';

$config['jayon_revenue_table'] = 'revenue_aggregate';
$config['jayon_devicerecap_table'] = 'device_aggregate';

//test only
$config['jayon_mobile_table'] = 'mobile_orders';

/* Buckets */
$config['bucket_incoming'] = 'incoming';
$config['bucket_dispatcher'] = 'dispatcher';
$config['bucket_tracker'] = 'tracker';



/* Delivery status strings */

$config['trans_status_new'] = 'pending';
$config['trans_status_tobeconfirmed'] = 'to_be_confirmed';
$config['trans_status_purged'] = 'purged';
$config['trans_status_archived'] = 'archived';
$config['trans_status_confirmed'] = 'confirmed';
$config['trans_status_canceled'] = 'canceled';
$config['trans_status_rescheduled'] = 'rescheduled';
$config['trans_status_inprogress'] = 'inprogress';


$config['trans_status_mobile_pending'] = 'pending';
$config['trans_status_mobile_dispatched'] = 'dispatched';
$config['trans_status_mobile_departure'] = 'departed';
$config['trans_status_mobile_return'] = 'returned';
$config['trans_status_mobile_pending'] = 'pending';
$config['trans_status_mobile_pickedup'] = 'pickedup';
$config['trans_status_mobile_enroute'] = 'enroute';
$config['trans_status_mobile_location'] = 'loc_update';
$config['trans_status_mobile_rescheduled'] = 'rescheduled';
$config['trans_status_mobile_delivered'] = 'delivered';
$config['trans_status_mobile_revoked'] = 'revoked';
$config['trans_status_mobile_noshow'] = 'noshow';
$config['trans_status_mobile_keyrequest'] = 'keyrequest';
$config['trans_status_mobile_syncnote'] = 'syncnote';

$config['trans_status_admin_zoned'] = 'zone_assigned';
$config['trans_status_admin_dated'] = 'date_assigned';
$config['trans_status_admin_devassigned'] = 'dev_assigned';
$config['trans_status_admin_courierassigned'] = 'cr_assigned';
$config['trans_status_admin_dispatched'] = 'dispatched';



$config['trans_status_tobepickup'] = 'to_be_picked_up';
$config['trans_status_pickup'] = 'picked_up';
$config['trans_status_pickup_canceled'] = 'canceled';

$config['trans_wh_atmerchant'] = 'at_merchant_premise';
$config['trans_wh_pu2wh'] = 'accepted_warehouse';
$config['trans_wh_inwh'] = 'in_warehouse';
$config['trans_wh_wh2ds'] = 'on_delivery';
$config['trans_wh_ds2wh'] = 'back_to_warehouse';
$config['trans_wh_return2merchant'] = 'return_to_merchant';
$config['trans_wh_canceled'] = 'canceled';


$config['trans_cr_atmerchant'] = 'at_merchant_premise';
$config['trans_cr_inwh'] = 'in_warehouse';
$config['trans_cr_oncr'] = 'on_courier';
$config['trans_cr_return2wh'] = 'return_to_warehouse';
$config['trans_cr_return2merchant'] = 'return_to_merchant';
$config['trans_cr_canceled'] = 'canceled';


/* Translation and Look Up */

$config['dispatcher_status'] = array(
    $config['trans_status_admin_zoned'] => 'Zone Assigned',
    $config['trans_status_admin_dated'] => 'Date Assigned',
    $config['trans_status_admin_devassigned'] => 'Device Assigned',
    $config['trans_status_admin_courierassigned'] => 'Courier Assigned',
    $config['trans_status_admin_dispatched'] => 'Dispatched',
);


$config['delivery_status'] = array(
    $config['trans_status_new'] => 'Pending',
    $config['trans_status_tobeconfirmed'] => 'To be Confirmed',
    $config['trans_status_purged'] => 'Purged',
    $config['trans_status_archived'] => 'Archived',
    $config['trans_status_confirmed'] => 'Confirmed',
    $config['trans_status_canceled'] => 'Canceled',
    $config['trans_status_rescheduled'] => 'Rescheduled',
    $config['trans_status_inprogress'] => 'In Progress',
);

$config['pickup_status'] = array(
    $config['trans_status_tobepickup'] => 'To Be Picked Up',
    $config['trans_status_pickup'] => 'Picked Up',
    $config['trans_status_pickup_canceled'] => 'Canceled',
);

$config['warehouse_status'] = array(
    $config['trans_wh_atmerchant'] => 'At Merchant Premise',
    $config['trans_wh_pu2wh'] => 'Accepted Warehouse',
    $config['trans_wh_inwh'] => 'In Warehouse',
    $config['trans_wh_wh2ds'] => 'On Delivery',
    $config['trans_wh_ds2wh'] => 'Back to Warehouse',
    $config['trans_wh_return2merchant'] => 'Return to Merchant',
    $config['trans_wh_canceled'] => 'Canceled',
);


$config['courier_status'] = array(
    $config['trans_cr_atmerchant'] => 'At Merchant Premise',
    $config['trans_cr_inwh'] => 'In Warehouse',
    $config['trans_cr_oncr'] => 'On Courier',
    $config['trans_cr_return2wh'] => 'Return to Warehouse',
    $config['trans_cr_return2merchant'] => 'Return to Merchant',
    $config['trans_cr_canceled'] => 'Canceled',
);




$config['status_list'] = array(
    'pending'=>'Pending',
    'delivered'=>'Delivered',
    'canceled'=>'Canceled',
    'returned'=>'Returned'
);

/* status colors */

$config['status_colors'] = array(
    $config['trans_status_new'] => 'orange',
    $config['trans_status_tobeconfirmed'] => 'orange',
    $config['trans_status_purged'] => 'red',
    $config['trans_status_archived'] => 'brown',
    $config['trans_status_confirmed'] => 'green',
    $config['trans_status_canceled'] => 'red',
    $config['trans_status_rescheduled'] => 'green',

    $config['trans_status_mobile_departure'] => 'green',
    $config['trans_status_mobile_return'] => 'red',
    $config['trans_status_mobile_pickedup'] => 'green',
    $config['trans_status_mobile_enroute'] => 'orange',
    $config['trans_status_mobile_location'] => 'black',
    $config['trans_status_mobile_rescheduled'] => 'brown',
    $config['trans_status_mobile_delivered'] => 'green',
    $config['trans_status_mobile_revoked'] => 'red',
    $config['trans_status_mobile_noshow'] => 'orange',
    $config['trans_status_mobile_keyrequest'] => 'black',

    $config['trans_status_admin_zoned'] => 'brown',
    $config['trans_status_admin_dated'] => 'blue',
    $config['trans_status_admin_devassigned'] => 'black',
    $config['trans_status_admin_courierassigned'] => 'black',
    $config['trans_status_admin_dispatched'] => 'green',

    $config['trans_status_tobepickup'] => 'maroon',
    $config['trans_status_pickup'] => 'green',

    $config['trans_wh_atmerchant'] => 'maroon',
    $config['trans_wh_pu2wh'] => 'green',
    $config['trans_wh_inwh'] => 'black',
    $config['trans_wh_wh2ds'] => 'orange',
    $config['trans_wh_ds2wh'] => 'brown',
    $config['trans_wh_return2merchant'] => 'maroon',
    $config['trans_wh_canceled'] => 'red',

    $config['trans_cr_atmerchant'] => 'maroon',
    $config['trans_cr_inwh'] => 'brown',
    $config['trans_cr_oncr'] => 'green',
    $config['trans_cr_return2wh'] => 'brown',
    $config['trans_cr_return2merchant'] => 'maroon',
    $config['trans_cr_canceled'] => 'red',


);

$config['delivery_status_changes'] = array(
    $config['trans_status_new'] => 'orange',
    $config['trans_status_tobeconfirmed'] => 'orange',
    $config['trans_status_purged'] => 'red',
    $config['trans_status_archived'] => 'brown',
    $config['trans_status_confirmed'] => 'green',
    $config['trans_status_canceled'] => 'red',

    $config['trans_status_mobile_return'] => 'red',
    $config['trans_status_mobile_rescheduled'] => 'brown',
    $config['trans_status_mobile_delivered'] => 'green',
    $config['trans_status_mobile_noshow'] => 'orange',
    /*
    $config['trans_status_mobile_revoked'] => 'red',
    $config['trans_status_mobile_departure'] => 'green',
    $config['trans_status_admin_zoned'] => 'brown',
    $config['trans_status_admin_dated'] => 'blue',
    $config['trans_status_admin_devassigned'] => 'black',
    $config['trans_status_admin_courierassigned'] => 'black',
    $config['trans_status_admin_dispatched'] => 'green',
    */
);

$config['pickup_status_changes'] = array(

    $config['trans_status_canceled'] => 'red',
    $config['trans_status_tobepickup'] => 'maroon',
    $config['trans_status_pickup'] => 'green',
);

$config['warehouse_status_changes'] = array(

    $config['trans_wh_atmerchant'] => 'maroon',
    $config['trans_wh_pu2wh'] => 'green',
    $config['trans_wh_inwh'] => 'black',
    $config['trans_wh_wh2ds'] => 'orange',
    $config['trans_wh_ds2wh'] => 'brown',
    $config['trans_wh_return2merchant'] => 'maroon',
    $config['trans_wh_canceled'] => 'red',
);

$config['courier_status_changes'] = array(

    $config['trans_cr_atmerchant'] => 'maroon',
    $config['trans_cr_inwh'] => 'brown',
    $config['trans_cr_oncr'] => 'green',
    $config['trans_cr_return2wh'] => 'brown',
    $config['trans_cr_return2merchant'] => 'maroon',
    $config['trans_cr_canceled'] => 'red',
);

$config['max_lat'] = -6.288176;
$config['min_lat'] = -6.286224;
$config['max_lon'] = 106.703041;
$config['min_lon'] = 106.699688;

$config['origin_lat'] =  -6.28776600000;
$config['origin_lon'] =  106.69635800000;

$config['actors_code'] = array(
    'mobile'=>'MB',
    'admin'=>'AD',
    'buyer'=>'BY',
    'merchant'=>'MC'
);

$config['actors_title'] = array(
    'MB'=>'mobile',
    'AD'=>'admin',
    'BY'=>'buyer',
    'MC'=>'merchant'
);


$config['fetch_method'] = array(
    'GET'=>'GET',
    'URL'=>'URL Segment'
);

$config['path_colors'] = array(
    '#FF0000',
    '#00FF00',
    '#0000FF',
    '#0F0F0F',
    '#FF0000',
    '#00FF00',
    '#0000FF',
    '#0F0F0F'
);

$config['smtp_host'] = 'ssl://smtp.googlemail.com';
$config['smtp_port'] = '465';

$config['notify_username'] = 'notification@jayonexpress.com';
$config['notify_password'] = 'NotiFier987';
//$config['notify_username'] = 'bolongsox@gmail.com';
//$config['notify_password'] = 'masukajadeh';


$config['admin_username'] = 'admin@jayonexpress.com';
$config['admin_password'] = 'JayonAdmin234';

//for test only

$config['api_url'] = 'http://localhost/beta2/jayonadmin/api/v1/';

$config['year_sequence_pad'] = 8;
$config['merchant_id_pad'] = 6;

$config['master_key'] = '7e931g6628S59A0sJ4pYVqAjdo0v66Wb';

$config['unlimited_order_time'] = true;

if(isset($_SERVER) && isset($_SERVER['HTTP_HOST']) ){

    if($_SERVER['HTTP_HOST'] == 'localhost'){
        $config['public_path'] = '/var/www/pro/jayonadmin/public/';
        $config['picture_path'] = '/var/www/pro/jayonadmin/public/receiver/';
        $config['pickuppic_path'] = '/var/www/pro/jayonadmin/public/pickup/';
        $config['thumbnail_path'] = '/var/www/pro/jayonadmin/public/receiver_thumb/';
        $config['api_url'] = 'http://localhost/jayonapidev/v2';
    }else{
        //online version should redirect to main site
        $config['public_path'] = '/var/www/pro/jayonadmin/public/';
        $config['picture_path'] = '/var/www/pro/jayonadmin/public/receiver/';
        $config['pickuppic_path'] = '/var/www/pro/jayonadmin/public/pickup/';
        $config['thumbnail_path'] = '/var/www/pro/jayonadmin/public/receiver_thumb/';
        $config['api_url'] = 'http://localhost/beta2/jayonapi/v2';
    }

}else{
        //online version should redirect to main site
        $config['public_path'] = '/var/www/pro/jayonadmin/public/';
        $config['picture_path'] = '/var/www/pro/jayonadmin/public/receiver/';
        $config['pickuppic_path'] = '/var/www/pro/jayonadmin/public/pickup/';
        $config['thumbnail_path'] = '/var/www/pro/jayonadmin/public/receiver_thumb/';
        $config['api_url'] = 'http://localhost/beta2/jayonapi/v2';
}

$config['import_label_default'] = 4;
$config['import_header_default'] = 7;
$config['import_data_default'] = 8;


return $config;