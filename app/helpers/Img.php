<?php

class Img {

    public static function getAvatarUrl($_id){

        $files = Uploaded::where('parent_id',$_id )
            ->where('parent_class','user')
            ->where('deleted',0)
            ->orderBy('createdDate','desc')
            ->first();

        if($files){
            return $files->medium_url;
        }else{
            return '';
        }

    }

    public static function getPictures($_id){

        $files = Uploaded::where('parent_id',$_id )
            ->where('parent_class','user')
            ->where('deleted',0)
            ->orderBy('createdDate','desc')
            ->get();

        $pics = array();

        if($files){

            foreach ($files as $file) {
                $s = new stdClass();
                foreach (Config::get('picture.sizes') as $key=>$value){
                    $s->{$key.'_url'} = $file->{$key.'_url'};
                }
                $s->url = $file->url;

                $pics[] = $s;
            }

        }

        return $pics;

    }

}
