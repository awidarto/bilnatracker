<?php





return array(
        'default_heads'=>array(

            array('CONSIG',array('search'=>true,'sort'=>true)),
            array('ORDER ID',array('search'=>true,'sort'=>true)),
            array('PICK UP DATE',array('search'=>true,'sort'=>true, 'style'=>'min-width:90px;','daterange'=>true)),
            array('FULFILLMENT ID',array('search'=>true,'sort'=>true)),
            array('NUMBER OF PACKAGE',array('search'=>true,'sort'=>true)),
            array('COD VALUE',array('search'=>true,'sort'=>true)),
            array('EMAIL',array('search'=>true,'sort'=>true)),
            array('NAME',array('search'=>true,'sort'=>true)),
            array('ADDR',array('search'=>true,'sort'=>true)),
            array('CITY',array('search'=>true,'sort'=>true)),
            array('REGION',array('search'=>true,'sort'=>true)),
            array('ZIP',array('search'=>true,'sort'=>true)),
            array('PHONE',array('search'=>true,'sort'=>true)),
            array('CONTACT',array('search'=>true,'sort'=>true)),
            array('DESC',array('search'=>true,'sort'=>true)),
            array('W/V',array('search'=>true,'sort'=>true)),
            array('AWB',array('search'=>true,'sort'=>true)),
            array('CUST ID',array('search'=>true,'sort'=>true)),
            array('SERVICE',array('search'=>true,'sort'=>true)),
            array('INSURANCE',array('search'=>true,'sort'=>true))

            /*
            array('Timestamp',array('search'=>true,'sort'=>true, 'style'=>'min-width:90px;','daterange'=>true)),
            array('Status',array('search'=>true,'sort'=>true)),
            array('PU Time',array('search'=>true,'sort'=>true, 'style'=>'min-width:100px;','daterange'=>true)),
            array('PU Person & Device',array('search'=>true,'style'=>'min-width:100px;','sort'=>true)),
            array('Box',array('search'=>true,'style'=>'','sort'=>true)),
            array('Delivery Date',array('search'=>true,'style'=>'min-width:125px;','sort'=>true, 'daterange'=>true )),
            array('Slot',array('search'=>true,'sort'=>true)),
            array('Zone',array('search'=>true,'sort'=>true)),
            array('City',array('search'=>true,'sort'=>true)),
            array('Shipping Address',array('search'=>true,'sort'=>true, 'style'=>'min-width:200px;width:200px;' )),
            array('No Kode Penjualan Toko',array('search'=>true,'sort'=>true)),
            array('Fulfillment ID',array('search'=>true,'sort'=>true)),
            array('Type',array('search'=>true,'sort'=>true,'select'=>Config::get('jayon.deliverytype_selector_legacy') )),
            array('Merchant & Shop Name',array('search'=>true,'sort'=>true)),
            array('Delivery ID',array('search'=>true,'sort'=>true)),
            array('Directions',array('search'=>true,'sort'=>true)),
            array('TTD Toko',array('search'=>true,'sort'=>true)),
            array('Delivery Charge',array('search'=>true,'sort'=>true)),
            array('COD Surcharge',array('search'=>true,'sort'=>true)),
            array('COD Value',array('search'=>true,'sort'=>true)),
            array('Buyer',array('search'=>true,'sort'=>true)),
            array('ZIP',array('search'=>true,'sort'=>true)),
            array('Phone',array('search'=>true,'sort'=>true)),
            array('W x H x L = V',array('search'=>true,'sort'=>true)),
            array('Weight Range',array('search'=>true,'sort'=>true)),
            */
        ),


        'default_fields'=>array(
            array('CONSIG',array('kind'=>'text' , 'query'=>'like', 'pos'=>'both','show'=>true)),
            array('NO_SALES_ORDER',array('kind'=>'text' , 'query'=>'like', 'pos'=>'both','show'=>true)),
            array('PICK_UP_DATE',array('kind'=>'daterange', 'query'=>'like','pos'=>'both','show'=>true)),
            array('CONSIGNEE_OLSHOP_ORDERID',array('kind'=>'text' , 'query'=>'like', 'pos'=>'both','show'=>true)),
            array('NUMBER_OF_PACKAGE',array('kind'=>'numeric', 'query'=>'like','pos'=>'both','show'=>true)),
            array('COD',array('kind'=>'currency', 'query'=>'like','pos'=>'both','show'=>true)),
            array('EMAIL',array('kind'=>'text' , 'query'=>'like', 'pos'=>'both','show'=>true)),
            array('CONSIGNEE_OLSHOP_NAME',array('kind'=>'text' , 'query'=>'like', 'pos'=>'both','show'=>true)),
            array('CONSIGNEE_OLSHOP_ADDR',array('kind'=>'text' , 'query'=>'like', 'pos'=>'both','show'=>true)),
            array('CONSIGNEE_OLSHOP_CITY',array('kind'=>'text' , 'query'=>'like', 'pos'=>'both','show'=>true)),
            array('CONSIGNEE_OLSHOP_REGION',array('kind'=>'text' , 'query'=>'like', 'pos'=>'both','show'=>true)),
            array('CONSIGNEE_OLSHOP_ZIP',array('kind'=>'text' , 'query'=>'like', 'pos'=>'both','show'=>true)),
            array('CONSIGNEE_OLSHOP_PHONE',array('kind'=>'text' , 'query'=>'like', 'pos'=>'both','show'=>true)),
            array('CONTACT',array('kind'=>'text' , 'query'=>'like', 'pos'=>'both','show'=>true)),
            array('CONSIGNEE_OLSHOP_DESC',array('kind'=>'text' , 'query'=>'like', 'pos'=>'both','show'=>true)),
            array('W_V',array('kind'=>'numeric' , 'query'=>'like', 'pos'=>'both','show'=>true)),
            array('AWB',array('kind'=>'text' , 'query'=>'like', 'pos'=>'both','show'=>true)),
            array('CONSIGNEE_OLSHOP_CUST',array('kind'=>'text' , 'query'=>'like', 'pos'=>'both','show'=>true)),
            array('CONSIGNEE_OLSHOP_SERVICE',array('kind'=>'text' , 'query'=>'like', 'pos'=>'both','show'=>true)),
            array('CONSIGNEE_OLSHOP_INST_AMT',array('kind'=>'text' , 'query'=>'like', 'pos'=>'both','show'=>true))

        /*
            array('ordertime',array('kind'=>'daterange', 'query'=>'like','pos'=>'both','show'=>true)),
            array('status',array('kind'=>'text','callback'=>'statusList','query'=>'like','pos'=>'both','show'=>true, 'multi'=>array('status','warehouse_status','pickup_status'), 'multirel'=>'OR'  )),
            array('pickuptime',array('kind'=>'daterange', 'query'=>'like','pos'=>'both','show'=>true)),
            array('pickup_person',array('kind'=>'text', 'callback'=>'puDisp' ,'query'=>'like','pos'=>'both','show'=>true, 'multi'=>array('pickup_dev_id','pickup_person'), 'multirel'=>'OR' )),
            array('box_count',array('kind'=>'numeric', 'query'=>'like','pos'=>'both','show'=>true)),
            array('buyerdeliverytime',array('kind'=>'daterange','query'=>'like','pos'=>'both','show'=>true)),
            array('buyerdeliveryslot',array('kind'=>'text' , 'query'=>'like', 'pos'=>'both','show'=>true)),
            array('buyerdeliveryzone',array('kind'=>'text','query'=>'like','pos'=>'both','show'=>true)),
            array('buyerdeliverycity',array('kind'=>'text', 'query'=>'like','pos'=>'both','show'=>true)),
            array('shipping_address',array('kind'=>'text', 'query'=>'like','pos'=>'both','show'=>true)),
            array('merchant_trans_id',array('kind'=>'text','query'=>'like','pos'=>'both','show'=>true)),
            array('fulfillment_code',array('kind'=>'text','callback'=>'dispFBar' ,'query'=>'like','pos'=>'both','show'=>true)),
            array('delivery_type',array('kind'=>'text','callback'=>'colorizetype' ,'query'=>'like','pos'=>'both','show'=>true)),
            array(Config::get('jayon.jayon_members_table').'.merchantname',array('kind'=>'text','alias'=>'merchant_name','query'=>'like','callback'=>'merchantInfo','pos'=>'both','show'=>true)),
            array('delivery_id',array('kind'=>'text','query'=>'like','pos'=>'both','show'=>true)),
            array('directions',array('kind'=>'text','query'=>'like','pos'=>'both','show'=>true)),
            array('delivery_id',array('kind'=>'text','query'=>'like','pos'=>'both','show'=>true)),
            array('delivery_cost',array('kind'=>'text','query'=>'like','pos'=>'both','show'=>true)),
            array('cod_cost',array('kind'=>'text','query'=>'like','pos'=>'both','show'=>true)),
            array('total_price',array('kind'=>'text','query'=>'like','pos'=>'both','show'=>true)),
            array('buyer_name',array('kind'=>'text','query'=>'like','pos'=>'both','show'=>true)),
            array('shipping_zip',array('kind'=>'text','query'=>'like','pos'=>'both','show'=>true)),
            array('phone',array('kind'=>'text','query'=>'like','pos'=>'both','show'=>true)),
            array('volume',array('kind'=>'numeric','query'=>'like','pos'=>'both','show'=>true)),
            array('weight',array('kind'=>'text','query'=>'like','pos'=>'both','show'=>true)),
        */
        ),

    );