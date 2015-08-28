<?php
use Jenssegers\Mongodb\Model as Eloquent;

class Sequence extends Eloquent {

    protected $collection = 'sequences';

    public function getNewId($entity)
    {   $seq = DB::collection($this->collection)->raw();

        $new_id = $seq->findAndModify(
                array('_id'=>$entity),
                array('$inc'=>array('seq'=>1)),
                null,
                array(
                    'new' => true
                )
            );

        return $new_id['seq'];
    }

    public function getLastId($entity)
    {
        $last_id = $this->find($entity);

        $last_id = $last_id['seq'];

        return $last_id;
    }

    public function setInitialValue($entity,$initial = 0)
    {
        if($this->find($entity)){
            return false;
        }else{

            $initial = new MongoInt32($initial);

            return $this->insert(array('_id'=>$entity,'seq'=>$initial), array('upsert'=>1));
        }
    }

}