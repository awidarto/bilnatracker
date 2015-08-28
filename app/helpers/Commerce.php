<?php

class Commerce{

    public static function updateStock($data, $positive = 'available', $negative = 'deleted'){

        //print_r($data);

        $outlets = $data['outlets'];
        $outletNames = $data['outletNames'];
        $addQty = $data['addQty'];
        $adjustQty = $data['adjustQty'];

        unset($data['outlets']);
        unset($data['outletNames']);
        unset($data['addQty']);
        unset($data['adjustQty']);

        $productDetail = Product::find($data['id'])->toArray();

        // year and month used fro batchnumber
        $year = date('Y', time());
        $month = date('m',time());


        for( $i = 0; $i < count($outlets); $i++)
        {

            $su = array(
                    'outletId'=>$outlets[$i],
                    'outletName'=>$outletNames[$i],
                    'productId'=>$data['id'],
                    'SKU'=>$data['SKU'],
                    'productDetail'=>$productDetail,
                    'status'=>$positive,
                    'createdDate'=>new MongoDate(),
                    'lastUpdate'=>new MongoDate()
                );

            if($addQty[$i] > 0){
                for($a = 0; $a < $addQty[$i]; $a++){
                    $su['_id'] = str_random(8);


                    $batchnumber = Prefs::GetBatchId($data['SKU'], $year, $month);

                    $su['_id'] = $data['SKU'].'|'.$batchnumber;

                    $history = array(
                        'datetime'=>new MongoDate(),
                        'action'=>'init',
                        'price'=>$productDetail['priceRegular'],
                        'status'=>$su['status'],
                        'outletName'=>$su['outletName']
                    );

                    $su['history'] = array($history);

                    Stockunit::insert($su);
                }
            }

            if($adjustQty[$i] > 0){
                $td = Stockunit::where('outletId',$outlets[$i])
                    ->where('productId',$data['id'])
                    ->where('SKU', $data['SKU'])
                    ->where('status','available')
                    ->orderBy('createdDate', 'asc')
                    ->take($adjustQty[$i])
                    ->get();

                foreach($td as $d){
                    $d->status = $negative;
                    $d->lastUpdate = new MongoDate();
                    $d->save();

                    $history = array(
                        'datetime'=>new MongoDate(),
                        'action'=>'delete',
                        'price'=>$d->priceRegular,
                        'status'=>$d->status,
                        'outletName'=>$d->outletName
                    );

                    $d->push('history', $history);
                }
            }

        }
    }

}