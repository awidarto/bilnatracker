<?php

return array(
        'CGKN00284'=>array('status'=>'multi',
                        'order'=>'ts',
                        'awb'=>'cn_no',
                        'group'=>array(),
                        'get'=>array()
                    ),
        '7735'=>array('status'=>'multi',
                        'order'=>'ts',
                        'awb'=>'awb',
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