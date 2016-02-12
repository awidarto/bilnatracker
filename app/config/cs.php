<?php

return array(
        'CGKN00284'=>array('status'=>'multi',
                        'order'=>'ts',
                        'awb'=>'cn_ref',
                        'order_awb'=>'fulfillment_code',
                        'group'=>array(),
                        'get'=>array()
                    ),
        '7735'=>array('status'=>'multi',
                        'order'=>'ts',
                        'awb'=>'awb',
                        'order_awb'=>'awb',
                        'group'=>array(),
                        'get'=>array('timestamp','status','pickup_time','delivery_time','pod','note')
                    ),
        'default'=>array('status'=>'multi',
                        'order'=>'ts',
                        'awb'=>'awb',
                        'group'=>array('status','timestamp'),
                        'get'=>array('status','timestamp','pickup_time','delivery_time','pod','note')
                    ),
    );