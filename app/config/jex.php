<?php





return array(
        'buckets'=>array(
                'incoming'=>'Incoming',
                'dispatcher'=>'Dispatcher',
                'tracker'=>'Tracker'
            ),
        'move_options'=>array(
                'dispatched'=>'Dispatcher',
                'inprogress'=>'Tracker'
            ),

        'node_type'=>array(
            'hub'=>'Hub',
            'warehouse'=>'Warehouse',
            'courier'=>'Courier',
            '3pl'=>'3PL'),

        'default_heads'=>array(
            array('CREATED DATE',array('search'=>true,'sort'=>true, 'style'=>'min-width:90px;','daterange'=>true)),
            array('STATUS',array('search'=>true,'select'=>Ks::statusoptions() ,'style'=>'min-width:175px;','sort'=>true)),
            array('CURR POSITION',array('search'=>true,'select'=>Prefs::getPosition()->PositionToSelection('node_code','name') ,'sort'=>true)),
            array('LOGISTIC',array('search'=>true,'sort'=>true)),
            array('SERVICE',array('search'=>true,'sort'=>true)),
            array('CONSIG',array('search'=>true,'sort'=>true)),
            array('AWB',array('search'=>true,'sort'=>true)),
            array('CUST ID',array('search'=>true,'sort'=>true)),
            array('DELIVERY ID',array('search'=>true,'sort'=>true)),
            array('ORDER ID',array('search'=>true,'sort'=>true)),
            array('PICK UP DATE',array('search'=>true,'sort'=>true, 'style'=>'min-width:90px;','daterange'=>true)),
            array('FULFILLMENT ID',array('search'=>true,'sort'=>true)),
            array('NUMBER OF PACKAGE',array('search'=>true,'sort'=>true)),
            array('COD VALUE',array('search'=>true,'sort'=>true)),
            array('EMAIL',array('search'=>true,'sort'=>true)),
            array('NAME',array('search'=>true,'sort'=>true)),
            array('ADDR',array('search'=>true,'style'=>'min-width:250px;width:250px !important;','sort'=>true)),
            array('CITY',array('search'=>true,'sort'=>true)),
            array('REGION',array('search'=>true,'sort'=>true)),
            array('ZIP',array('search'=>true,'sort'=>true)),
            array('PHONE',array('search'=>true,'sort'=>true)),
            array('CONTACT',array('search'=>true,'sort'=>true)),
            array('DESC',array('search'=>true,'sort'=>true)),
            array('W/V',array('search'=>true,'sort'=>true)),
            array('INSURANCE',array('search'=>true,'sort'=>true))

        ),


        'default_fields'=>array(
            array('createdDate',array('kind'=>'daterange' , 'query'=>'like', 'pos'=>'both','show'=>true)),
            array('status',array('kind'=>'text','callback'=>'statusList','query'=>'like','pos'=>'both','show'=>true, 'multi'=>array('status','warehouse_status','pickup_status'), 'multirel'=>'OR'  )),
            array('position',array('kind'=>'text' , 'query'=>'like', 'pos'=>'both','show'=>true)),
            array('logistic',array('kind'=>'text' , 'query'=>'like', 'pos'=>'both','show'=>true)),
            array('consignee_olshop_service',array('kind'=>'text' , 'query'=>'like', 'pos'=>'both','show'=>true)),
            array('consig',array('kind'=>'text' , 'query'=>'like', 'pos'=>'both','show'=>true)),
            array('awb',array('kind'=>'text' , 'query'=>'like', 'pos'=>'both','show'=>true)),
            array('consignee_olshop_cust',array('kind'=>'text' , 'query'=>'like', 'pos'=>'both','show'=>true)),
            array('delivery_id',array('kind'=>'text' , 'query'=>'like', 'pos'=>'both','show'=>true)),
            array('no_sales_order',array('kind'=>'text' , 'query'=>'like', 'pos'=>'both','show'=>true)),
            array('pick_up_date',array('kind'=>'daterange', 'query'=>'like','pos'=>'both','show'=>true)),
            array('consignee_olshop_orderid',array('kind'=>'text' , 'query'=>'like', 'pos'=>'both','show'=>true)),
            array('number_of_package',array('kind'=>'numeric', 'query'=>'like','pos'=>'both','show'=>true)),
            array('cod',array('kind'=>'currency', 'query'=>'like','pos'=>'both','show'=>true)),
            array('email',array('kind'=>'text' , 'query'=>'like', 'pos'=>'both','show'=>true)),
            array('consignee_olshop_name',array('kind'=>'text' , 'query'=>'like', 'pos'=>'both','show'=>true)),
            array('consignee_olshop_addr',array('kind'=>'text' , 'query'=>'like', 'pos'=>'both','show'=>true)),
            array('consignee_olshop_city',array('kind'=>'text' , 'query'=>'like', 'pos'=>'both','show'=>true)),
            array('consignee_olshop_region',array('kind'=>'text' , 'query'=>'like', 'pos'=>'both','show'=>true)),
            array('consignee_olshop_zip',array('kind'=>'text' , 'query'=>'like', 'pos'=>'both','show'=>true)),
            array('consignee_olshop_phone',array('kind'=>'text' , 'query'=>'like', 'pos'=>'both','show'=>true)),
            array('contact',array('kind'=>'text' , 'query'=>'like', 'pos'=>'both','show'=>true)),
            array('consignee_olshop_desc',array('kind'=>'text' , 'query'=>'like', 'pos'=>'both','show'=>true)),
            array('w_V',array('kind'=>'numeric' , 'query'=>'like', 'pos'=>'both','show'=>true)),
            array('consignee_olshop_inst_amt',array('kind'=>'currency' , 'query'=>'like', 'pos'=>'both','show'=>true))

        ),

        'default_export_heads'=>array(
            array('Created DATE',array('search'=>true,'sort'=>true, 'style'=>'min-width:90px;','daterange'=>true)),
            array('Status',array('search'=>true,'select'=>Ks::statusoptions() ,'style'=>'min-width:250px;','sort'=>true)),
            array('Courier STATUS',array('search'=>true,'select'=>Ks::statusoptions() ,'style'=>'min-width:250px;','sort'=>true)),
            array('Pickup STATUS',array('search'=>true,'select'=>Ks::statusoptions() ,'style'=>'min-width:250px;','sort'=>true)),
            array('3PL STATUS',array('search'=>true,'select'=>Ks::statusoptions() ,'style'=>'min-width:250px;','sort'=>true)),
            array('Warehouse STATUS',array('search'=>true,'select'=>Ks::statusoptions() ,'style'=>'min-width:250px;','sort'=>true)),
            array('Consig',array('search'=>true,'sort'=>true)),
            array('Order ID',array('search'=>true,'sort'=>true)),
            array('Pick UP DATE',array('search'=>true,'sort'=>true, 'style'=>'min-width:90px;','daterange'=>true)),
            array('Fulfillment ID',array('search'=>true,'sort'=>true)),
            array('Number OF PACKAGE',array('search'=>true,'sort'=>true)),
            array('Cod VALUE',array('search'=>true,'sort'=>true)),
            array('Email',array('search'=>true,'sort'=>true)),
            array('Name',array('search'=>true,'sort'=>true)),
            array('Addr',array('search'=>true,'style'=>'min-width:120px;width:120px !important;','sort'=>true)),
            array('City',array('search'=>true,'sort'=>true)),
            array('Region',array('search'=>true,'sort'=>true)),
            array('Zip',array('search'=>true,'sort'=>true)),
            array('Phone',array('search'=>true,'sort'=>true)),
            array('Contact',array('search'=>true,'sort'=>true)),
            array('Desc',array('search'=>true,'sort'=>true)),
            array('W/V',array('search'=>true,'sort'=>true)),
            array('Awb',array('search'=>true,'sort'=>true)),
            array('Cust ID',array('search'=>true,'sort'=>true)),
            array('Service',array('search'=>true,'sort'=>true)),
            array('Insurance',array('search'=>true,'sort'=>true))

        ),

        'default_export_fields'=>array(
            array('createdDate',array('kind'=>'daterange' , 'query'=>'like', 'pos'=>'both','show'=>true)),
            array('status',array('kind'=>'text','query'=>'like','pos'=>'both','show'=>true, 'multi'=>array('status','warehouse_status','pickup_status'), 'multirel'=>'OR'  )),
            array('courier_status',array('kind'=>'text','query'=>'like','pos'=>'both','show'=>true, 'multi'=>array('status','warehouse_status','pickup_status'), 'multirel'=>'OR'  )),
            array('pickup_status',array('kind'=>'text','query'=>'like','pos'=>'both','show'=>true, 'multi'=>array('status','warehouse_status','pickup_status'), 'multirel'=>'OR'  )),
            array('3pl_status',array('kind'=>'text','query'=>'like','pos'=>'both','show'=>true, 'multi'=>array('status','warehouse_status','pickup_status'), 'multirel'=>'OR'  )),
            array('warehouse_status',array('kind'=>'text','query'=>'like','pos'=>'both','show'=>true, 'multi'=>array('status','warehouse_status','pickup_status'), 'multirel'=>'OR'  )),
            array('consig',array('kind'=>'text' , 'query'=>'like', 'pos'=>'both','show'=>true)),
            array('no_sales_order',array('kind'=>'text' , 'query'=>'like', 'pos'=>'both','show'=>true)),
            array('pick_up_date',array('kind'=>'daterange', 'query'=>'like','pos'=>'both','show'=>true)),
            array('consignee_olshop_orderid',array('kind'=>'text' , 'query'=>'like', 'pos'=>'both','show'=>true)),
            array('number_OF_PACKAGE',array('kind'=>'numeric', 'query'=>'like','pos'=>'both','show'=>true)),
            array('cod',array('kind'=>'currency', 'query'=>'like','pos'=>'both','show'=>true)),
            array('email',array('kind'=>'text' , 'query'=>'like', 'pos'=>'both','show'=>true)),
            array('consignee_olshop_name',array('kind'=>'text' , 'query'=>'like', 'pos'=>'both','show'=>true)),
            array('consignee_olshop_addr',array('kind'=>'text' , 'query'=>'like', 'pos'=>'both','show'=>true)),
            array('consignee_olshop_city',array('kind'=>'text' , 'query'=>'like', 'pos'=>'both','show'=>true)),
            array('consignee_olshop_region',array('kind'=>'text' , 'query'=>'like', 'pos'=>'both','show'=>true)),
            array('consignee_olshop_zip',array('kind'=>'text' , 'query'=>'like', 'pos'=>'both','show'=>true)),
            array('consignee_olshop_phone',array('kind'=>'text' , 'query'=>'like', 'pos'=>'both','show'=>true)),
            array('contact',array('kind'=>'text' , 'query'=>'like', 'pos'=>'both','show'=>true)),
            array('consignee_olshop_desc',array('kind'=>'text' , 'query'=>'like', 'pos'=>'both','show'=>true)),
            array('w_v',array('kind'=>'numeric' , 'query'=>'like', 'pos'=>'both','show'=>true)),
            array('awb',array('kind'=>'text' , 'query'=>'like', 'pos'=>'both','show'=>true)),
            array('consignee_olshop_cust',array('kind'=>'text' , 'query'=>'like', 'pos'=>'both','show'=>true)),
            array('consignee_olshop_service',array('kind'=>'text' , 'query'=>'like', 'pos'=>'both','show'=>true)),
            array('consignee_olshop_inst_amt',array('kind'=>'text' , 'query'=>'like', 'pos'=>'both','show'=>true))

        ),

        'default_zoning_heads'=>array(
            array('CREATED DATE',array('search'=>true,'sort'=>true, 'style'=>'min-width:90px;','daterange'=>true)),
            array('PICK UP DATE',array('search'=>true,'sort'=>true, 'style'=>'min-width:120px;','daterange'=>true)),
            array('CITY',array('search'=>true,'style'=>'min-width:120px;','sort'=>true)),
            //array('STATUS',array('search'=>true,'select'=>Ks::statusoptions() ,'style'=>'min-width:175px;','sort'=>true)),
            array('CURR POSITION',array('search'=>true,'select'=>Prefs::getPosition()->PositionToSelection('node_code','name') ,'sort'=>true)),
            array('LOGISTIC',array('search'=>true,'sort'=>true)),
            array('SERVICE',array('search'=>true,'sort'=>true)),
            array('CONSIG',array('search'=>true,'sort'=>true)),
            array('AWB',array('search'=>true,'sort'=>true)),
            array('CUST ID',array('search'=>true,'sort'=>true)),
            array('ORDER ID',array('search'=>true,'sort'=>true)),
            array('FULFILLMENT ID',array('search'=>true,'sort'=>true)),
            array('NUMBER OF PACKAGE',array('search'=>true,'sort'=>true)),
            array('COD VALUE',array('search'=>true,'sort'=>true)),
            array('EMAIL',array('search'=>true,'sort'=>true)),
            array('NAME',array('search'=>true,'sort'=>true)),
            array('ADDR',array('search'=>true,'style'=>'min-width:250px;width:250px !important;','sort'=>true)),
            array('CITY',array('search'=>true,'sort'=>true)),
            array('REGION',array('search'=>true,'sort'=>true)),
            array('ZIP',array('search'=>true,'sort'=>true)),
            array('PHONE',array('search'=>true,'sort'=>true)),
            array('CONTACT',array('search'=>true,'sort'=>true)),
            array('DESC',array('search'=>true,'sort'=>true)),
            array('W/V',array('search'=>true,'sort'=>true)),
            array('INSURANCE',array('search'=>true,'sort'=>true))

        ),


        'default_zoning_fields'=>array(
            array('createdDate',array('kind'=>'daterange' , 'query'=>'like', 'pos'=>'both','show'=>true)),
            array('pick_up_date',array('kind'=>'daterange', 'query'=>'like','pos'=>'both','show'=>true)),
            array('consignee_olshop_city',array('kind'=>'text' , 'query'=>'like', 'pos'=>'both','show'=>true)),
            //array('status',array('kind'=>'text','callback'=>'statusList','query'=>'like','pos'=>'both','show'=>true, 'multi'=>array('status','warehouse_status','pickup_status'), 'multirel'=>'OR'  )),
            array('position',array('kind'=>'text' , 'query'=>'like', 'pos'=>'both','show'=>true)),
            array('logistic',array('kind'=>'text' , 'query'=>'like', 'pos'=>'both','show'=>true)),
            array('consignee_olshop_service',array('kind'=>'text' , 'query'=>'like', 'pos'=>'both','show'=>true)),
            array('consig',array('kind'=>'text' , 'query'=>'like', 'pos'=>'both','show'=>true)),
            array('awb',array('kind'=>'text' , 'query'=>'like', 'pos'=>'both','show'=>true)),
            array('consignee_olshop_cust',array('kind'=>'text' , 'query'=>'like', 'pos'=>'both','show'=>true)),
            array('no_sales_order',array('kind'=>'text' , 'query'=>'like', 'pos'=>'both','show'=>true)),
            array('consignee_olshop_orderid',array('kind'=>'text' , 'query'=>'like', 'pos'=>'both','show'=>true)),
            array('number_of_package',array('kind'=>'numeric', 'query'=>'like','pos'=>'both','show'=>true)),
            array('cod',array('kind'=>'currency', 'query'=>'like','pos'=>'both','show'=>true)),
            array('email',array('kind'=>'text' , 'query'=>'like', 'pos'=>'both','show'=>true)),
            array('consignee_olshop_name',array('kind'=>'text' , 'query'=>'like', 'pos'=>'both','show'=>true)),
            array('consignee_olshop_addr',array('kind'=>'text' , 'query'=>'like', 'pos'=>'both','show'=>true)),
            array('consignee_olshop_city',array('kind'=>'text' , 'query'=>'like', 'pos'=>'both','show'=>true)),
            array('consignee_olshop_region',array('kind'=>'text' , 'query'=>'like', 'pos'=>'both','show'=>true)),
            array('consignee_olshop_zip',array('kind'=>'text' , 'query'=>'like', 'pos'=>'both','show'=>true)),
            array('consignee_olshop_phone',array('kind'=>'text' , 'query'=>'like', 'pos'=>'both','show'=>true)),
            array('contact',array('kind'=>'text' , 'query'=>'like', 'pos'=>'both','show'=>true)),
            array('consignee_olshop_desc',array('kind'=>'text' , 'query'=>'like', 'pos'=>'both','show'=>true)),
            array('w_v',array('kind'=>'numeric' , 'query'=>'like', 'pos'=>'both','show'=>true)),
            array('consignee_olshop_inst_amt',array('kind'=>'currency' , 'query'=>'like', 'pos'=>'both','show'=>true))

        ),

        'default_courierassign_heads'=>array(
            array('CREATED DATE',array('search'=>true,'sort'=>true, 'style'=>'min-width:90px;','daterange'=>true)),
            array('PICK UP DATE',array('search'=>true,'sort'=>true, 'style'=>'min-width:120px;','daterange'=>true)),
            array('DEVICE ID',array('search'=>true,'style'=>'min-width:120px;','sort'=>true)),
            //array('STATUS',array('search'=>true,'select'=>Ks::statusoptions() ,'style'=>'min-width:175px;','sort'=>true)),
            array('CURR POSITION',array('search'=>true,'select'=>Prefs::getPosition()->PositionToSelection('node_code','name') ,'sort'=>true)),
            array('LOGISTIC',array('search'=>true,'sort'=>true)),
            array('SERVICE',array('search'=>true,'sort'=>true)),
            array('CONSIG',array('search'=>true,'sort'=>true)),
            array('AWB',array('search'=>true,'sort'=>true)),
            array('CUST ID',array('search'=>true,'sort'=>true)),
            array('ORDER ID',array('search'=>true,'sort'=>true)),
            array('FULFILLMENT ID',array('search'=>true,'sort'=>true)),
            array('NUMBER OF PACKAGE',array('search'=>true,'sort'=>true)),
            array('COD VALUE',array('search'=>true,'sort'=>true)),
            array('EMAIL',array('search'=>true,'sort'=>true)),
            array('NAME',array('search'=>true,'sort'=>true)),
            array('ADDR',array('search'=>true,'style'=>'min-width:250px;width:250px !important;','sort'=>true)),
            array('CITY',array('search'=>true,'sort'=>true)),
            array('REGION',array('search'=>true,'sort'=>true)),
            array('ZIP',array('search'=>true,'sort'=>true)),
            array('PHONE',array('search'=>true,'sort'=>true)),
            array('CONTACT',array('search'=>true,'sort'=>true)),
            array('DESC',array('search'=>true,'sort'=>true)),
            array('W/V',array('search'=>true,'sort'=>true)),
            array('INSURANCE',array('search'=>true,'sort'=>true))

        ),


        'default_courierassign_fields'=>array(
            array('createdDate',array('kind'=>'daterange' , 'query'=>'like', 'pos'=>'both','show'=>true)),
            array('pick_up_date',array('kind'=>'daterange', 'query'=>'like','pos'=>'both','show'=>true)),
            array('device_id',array('kind'=>'text' , 'query'=>'like', 'pos'=>'both','show'=>true)),
            //array('status',array('kind'=>'text','callback'=>'statusList','query'=>'like','pos'=>'both','show'=>true, 'multi'=>array('status','warehouse_status','pickup_status'), 'multirel'=>'OR'  )),
            array('position',array('kind'=>'text' , 'query'=>'like', 'pos'=>'both','show'=>true)),
            array('logistic',array('kind'=>'text' , 'query'=>'like', 'pos'=>'both','show'=>true)),
            array('consignee_olshop_service',array('kind'=>'text' , 'query'=>'like', 'pos'=>'both','show'=>true)),
            array('consig',array('kind'=>'text' , 'query'=>'like', 'pos'=>'both','show'=>true)),
            array('awb',array('kind'=>'text' , 'query'=>'like', 'pos'=>'both','show'=>true)),
            array('consignee_olshop_cust',array('kind'=>'text' , 'query'=>'like', 'pos'=>'both','show'=>true)),
            array('no_sales_order',array('kind'=>'text' , 'query'=>'like', 'pos'=>'both','show'=>true)),
            array('consignee_olshop_orderid',array('kind'=>'text' , 'query'=>'like', 'pos'=>'both','show'=>true)),
            array('number_of_package',array('kind'=>'numeric', 'query'=>'like','pos'=>'both','show'=>true)),
            array('cod',array('kind'=>'currency', 'query'=>'like','pos'=>'both','show'=>true)),
            array('email',array('kind'=>'text' , 'query'=>'like', 'pos'=>'both','show'=>true)),
            array('consignee_olshop_name',array('kind'=>'text' , 'query'=>'like', 'pos'=>'both','show'=>true)),
            array('consignee_olshop_addr',array('kind'=>'text' , 'query'=>'like', 'pos'=>'both','show'=>true)),
            array('consignee_olshop_city',array('kind'=>'text' , 'query'=>'like', 'pos'=>'both','show'=>true)),
            array('consignee_olshop_region',array('kind'=>'text' , 'query'=>'like', 'pos'=>'both','show'=>true)),
            array('consignee_olshop_zip',array('kind'=>'text' , 'query'=>'like', 'pos'=>'both','show'=>true)),
            array('consignee_olshop_phone',array('kind'=>'text' , 'query'=>'like', 'pos'=>'both','show'=>true)),
            array('contact',array('kind'=>'text' , 'query'=>'like', 'pos'=>'both','show'=>true)),
            array('consignee_olshop_desc',array('kind'=>'text' , 'query'=>'like', 'pos'=>'both','show'=>true)),
            array('w_v',array('kind'=>'numeric' , 'query'=>'like', 'pos'=>'both','show'=>true)),
            array('consignee_olshop_inst_amt',array('kind'=>'currency' , 'query'=>'like', 'pos'=>'both','show'=>true))

        ),

    );