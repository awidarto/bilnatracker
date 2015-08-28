{{ HTML::script('js/zeroclipboard/ZeroClipboard.js') }}
<div class="control-group">
    <div class="controls">
        <div class="fileupload fileupload-new margin-none" data-provides="fileupload">
            <span class="btn btn-default btn-file">
                <span class="fileupload-new">{{ $title }}</span>
                <input id="{{ $element_id }}" type="file" name="files[]" {{ ($multi)?'multiple':''}}  >
            </span>
        </div>
        <br />
        <div id="{{ $element_id }}_progress" class="progress progress-mini">
            <div class="bar progress-bar progress-bar-danger"></div>
        </div>
        <br />
        <span id="loading-pictures" style="display:none;" ><img src="{{URL::to('/') }}/images/loading.gif" />loading existing pictures...</span>



        <div id="{{ $element_id }}_files" class="files">
            <ul style="margin-left:-25px">
                <?php

                    $allin = Input::old();
                    $showold = false;

                    if( count($allin) > 0){
                        $showold = true;
                    }

                    if( !is_null($formdata) && isset($formdata['files']) && $showold == false ){

                        /* external detail template */

                                                // display previously saved data
                        //for($t = 0; $t < count($filename);$t++){

                        foreach ($formdata['files'] as $k => $v) {

                            if($prefix != ''){
                                $detailview = $prefix.'.detail';
                            }else{
                                $detailview = 'fupload.detail';
                            }

                            if(isset($v['mediatitle'])){
                                $mediatitle = $v['mediatitle'];
                            }else{
                                $mediatitle = $v['filename'];
                            }

                            $thumb = View::make($detailview)
                                            ->with('filename',$v['filename'])
                                            ->with('thumbnail_url',$v['thumbnail_url'])
                                            ->with('full_url',$v['fileurl'])
                                            ->with('file_id',$v['file_id'])
                                            ->with('is_audio',$v['is_audio'])
                                            ->with('is_video',$v['is_video'])
                                            ->with('filetype',$v['filetype'])
                                            ->with('filetitle',$mediatitle)
                                            ->render();

                            if($v['ns'] == $prefix){
                                print $thumb;
                            }

                        }

                    }

                    // display re-populated data from error form

                    if($showold && isset( $allin['file_id'])){

                        $filename = $allin['filename'];
                        $thumbnail_url = $allin['thumbnail_url'];
                        $full_url = $allin['fileurl'];
                        $file_id = $allin['file_id'];
                        $is_audio = $allin['is_audio'];
                        $is_video = $allin['is_video'];
                        $filetype = $allin['filetype'];

                        if(isset($allin['mediatitle'])){
                            $mediatitle = $allin['mediatitle'];
                        }else{
                            $mediatitle = $allin['filename'];
                        }

                        $ns = $allin['ns'];

                        for($t = 0; $t < count($filename);$t++){

                            if($prefix != ''){
                                $detailview = $prefix.'.detail';
                            }else{
                                $detailview = 'fupload.detail';
                            }

                            $thumb = View::make($detailview)
                                            ->with('filename',$filename[$t])
                                            ->with('thumbnail_url',$thumbnail_url[$t])
                                            ->with('full_url',$full_url[$t])
                                            ->with('file_id',$file_id[$t])
                                            ->with('is_audio',$is_audio[$t])
                                            ->with('is_video',$is_video[$t])
                                            ->with('filetype',$filetype[$t])
                                            ->with('filetitle',$mediatitle[$t])
                                            ->render();

                            //if($ns[$t] == $prefix){
                                print $thumb;
                            //}
                        }

                    }
                ?>
            </ul>
        </div>
        <div id="{{ $element_id }}_uploadedform">
            <ul style="list-style:none">
            <?php

                if(isset( $formdata['filename'] )  && $showold == false ){

                    $count = 0;
                    $upcount = count($formdata['filename']);

                    $upl = '';
                    for($u = 0; $u < $upcount; $u++){

                        if($formdata['ns'][$u] == $prefix){

                            $upl .= '<li id="fdel_'.$formdata['file_id'][$u].'">';

                            $upl .= '<input type="hidden" name="ns[]" value="' . $formdata['ns'][$u] . '">';
                            $upl .= '<input type="hidden" name="role[]" value="' . $formdata['role'][$u] . '">';

                            $upl .= '<input type="hidden" name="delete_type[]" value="' . $formdata['delete_type'][$u] . '">';
                            $upl .= '<input type="hidden" name="delete_url[]" value="' . $formdata['delete_url'][$u] . '">';
                            $upl .= '<input type="hidden" name="filename[]" value="' . $formdata['filename'][$u]  . '">';
                            $upl .= '<input type="hidden" name="filesize[]" value="' . $formdata['filesize'][$u]  . '">';
                            $upl .= '<input type="hidden" name="temp_dir[]" value="' . $formdata['temp_dir'][$u]  . '">';

                            foreach(Config::get('picture.sizes') as $k=>$s ){
                                if(isset($formdata[$k.'_url'][$u])){
                                    $upl .= '<input type="hidden" name="'.$k.'_url[]" value="'. $formdata[$k.'_url'][$u].'">';

                                }else{
                                    $upl .= '<input type="hidden" name="'.$k.'_url[]" value="">';
                                }

                            }
                            /*
                            $upl .= '<input type="hidden" name="thumbnail_url[]" value="' . $formdata['thumbnail_url'][$u] . '">';
                            $upl .= '<input type="hidden" name="large_url[]" value="' . $formdata['large_url'][$u] . '">';
                            $upl .= '<input type="hidden" name="medium_url[]" value="' . $formdata['medium_url'][$u] . '">';
                            $upl .= '<input type="hidden" name="full_url[]" value="' . $formdata['full_url'][$u] . '">';
                            */
                            if(isset($formdata['is_image'])){
                                $upl .= '<input type="hidden" name="is_image[]" value="' . $formdata['is_image'][$u] . '">';
                                $upl .= '<input type="hidden" name="is_audio[]" value="' . $formdata['is_audio'][$u] . '">';
                                $upl .= '<input type="hidden" name="is_video[]" value="' . $formdata['is_video'][$u] . '">';
                                $upl .= '<input type="hidden" name="is_pdf[]" value="' . $formdata['is_pdf'][$u] . '">';
                                $upl .= '<input type="hidden" name="is_doc[]" value="' . $formdata['is_doc'][$u] . '">';
                            }

                            $upl .= '<input type="hidden" name="filetype[]" value="' . $formdata['filetype'][$u] . '">';
                            $upl .= '<input type="hidden" name="fileurl[]" value="' . $formdata['fileurl'][$u] . '">';
                            $upl .= '<input type="hidden" name="file_id[]" value="' . $formdata['file_id'][$u] . '">';
                            $upl .= '</li>';

                        }

                    }

                    //if($formdata['ns'][$u] == $prefix){
                        print $upl;
                    //}
                }

            ?>
            <?php

                if($showold && isset( $allin['filename'] )){

                    $count = 0;
                    $upcount = count($allin['filename']);

                    $upl = '';
                    for($u = 0; $u < $upcount; $u++){

                        if( $allin['ns'][$u] == $prefix){
                            $upl .= '<li id="fdel_'.$allin['file_id'][$u].'">';

                            $upl .= '<input type="hidden" name="ns[]" value="' . $allin['ns'][$u] . '">';
                            $upl .= '<input type="hidden" name="role[]" value="' . $allin['role'][$u] . '">';

                            $upl .= '<input type="hidden" name="delete_type[]" value="' . $allin['delete_type'][$u] . '">';
                            $upl .= '<input type="hidden" name="delete_url[]" value="' . $allin['delete_url'][$u] . '">';
                            $upl .= '<input type="hidden" name="filename[]" value="' . $allin['filename'][$u]  . '">';
                            $upl .= '<input type="hidden" name="filesize[]" value="' . $allin['filesize'][$u]  . '">';
                            $upl .= '<input type="hidden" name="temp_dir[]" value="' . $allin['temp_dir'][$u]  . '">';

                            foreach(Config::get('picture.sizes') as $k=>$s ){
                                if(isset($allin[$k.'_url'][$u])){
                                    $upl .= '<input type="hidden" name="'.$k.'_url[]" value="'. $allin[$k.'_url'][$u].'">';

                                }else{
                                    $upl .= '<input type="hidden" name="'.$k.'_url[]" value="">';

                                }

                            }
                            /*
                            $upl .= '<input type="hidden" name="thumbnail_url[]" value="' . $allin['thumbnail_url'][$u] . '">';
                            $upl .= '<input type="hidden" name="large_url[]" value="' . $allin['large_url'][$u] . '">';
                            $upl .= '<input type="hidden" name="medium_url[]" value="' . $allin['medium_url'][$u] . '">';
                            $upl .= '<input type="hidden" name="full_url[]" value="' . $allin['full_url'][$u] . '">';
                            */
                            $upl .= '<input type="hidden" name="is_image[]" value="' . $allin['is_image'][$u] . '">';
                            $upl .= '<input type="hidden" name="is_audio[]" value="' . $allin['is_audio'][$u] . '">';
                            $upl .= '<input type="hidden" name="is_video[]" value="' . $allin['is_video'][$u] . '">';
                            $upl .= '<input type="hidden" name="is_pdf[]" value="' . $allin['is_pdf'][$u] . '">';
                            $upl .= '<input type="hidden" name="is_doc[]" value="' . $allin['is_doc'][$u] . '">';

                            $upl .= '<input type="hidden" name="filetype[]" value="' . $allin['filetype'][$u] . '">';
                            $upl .= '<input type="hidden" name="fileurl[]" value="' . $allin['fileurl'][$u] . '">';
                            $upl .= '<input type="hidden" name="file_id[]" value="' . $allin['file_id'][$u] . '">';
                            $upl .= '</li>';

                        }

                    }
                    //if( $allin['ns'][$u] == $prefix){
                        print $upl;
                    //}
                }

            ?>
            </ul>
        </div>
    </div>
</div>

<style type="text/css">
    .file_del, .file_copy{
        cursor: pointer;
    }
</style>

<script type="text/javascript">

$(document).ready(function(){

    var url = '{{ URL::to($url) }}';

    var clip = new ZeroClipboard($('.file_copy').each(function(){ }),{
        moviePath: '{{ URL::to('js/zeroclipboard')}}/ZeroClipboard.swf'
    });

    $('#{{ $element_id }}_files').on('click',function(e){

        if ($(e.target).is('.file_del')) {
            var _id = e.target.id;
            var answer = confirm("Are you sure you want to delete this item ?");

            console.log($(e.target).parent());

            if (answer == true){
                $('#par_' + _id).remove();
                //$(e.target).parent().remove();
                $('#fdel_'+e.target.id).remove();
                /*
                $.post('',{'id':_id}, function(data) {
                    if(data.status == 'OK'){



                        alert("Item id : " + _id + " deleted");
                    }
                },'json');
                */
            }else{
                alert("Deletion cancelled");
            }
        }
    });

    $('#{{ $element_id }}').fileupload({
        url: url,
        dataType: 'json',
        done: function (e, data) {
            $('#{{ $element_id }}_progress .bar').css(
                'width',
                '0%'
            );

            if(data.result.status == 'OK'){

                $.each(data.result.files, function (index, file) {


                    @if($prefix == '')
                        {{ View::make('fupload.jsdetail') }}
                    @else
                        {{ View::make($prefix.'.jsdetail') }}
                    @endif

                    console.log(thumb);

                    @if($singlefile == true)
                        $('#{{ $element_id }}_files ul').html(thumb);
                    @else
                        $(thumb).prependTo('#{{ $element_id }}_files ul');
                    @endif

                    var upl = '<li id="fdel_' + file.file_id +'" ><input type="hidden" name="delete_type[]" value="' + file.delete_type + '">';

                    upl += '<input type="hidden" name="ns[]" value="' + file.ns  + '">';
                    upl += '<input type="hidden" name="role[]" value="' + data.result.role + '">';
                    upl += '<input type="hidden" name="delete_url[]" value="' + file.delete_url + '">';
                    upl += '<input type="hidden" name="filename[]" value="' + file.name  + '">';
                    upl += '<input type="hidden" name="filesize[]" value="' + file.size  + '">';
                    upl += '<input type="hidden" name="temp_dir[]" value="' + file.temp_dir  + '">';

                    @foreach(Config::get('picture.sizes') as $k=>$s )
                        upl += '<input type="hidden" name="{{ $k }}_url[]" value="' + file.{{ $k }}_url + '">';
                    @endforeach

                    upl += '<input type="hidden" name="is_image[]" value="' + file.is_image + '">';
                    upl += '<input type="hidden" name="is_audio[]" value="' + file.is_audio + '">';
                    upl += '<input type="hidden" name="is_video[]" value="' + file.is_video + '">';
                    upl += '<input type="hidden" name="is_pdf[]" value="' + file.is_pdf + '">';
                    upl += '<input type="hidden" name="is_doc[]" value="' + file.is_doc + '">';

                    {{--

                    upl += '<input type="hidden" name="thumbnail_url[]" value="' + file.thumbnail_url + '">';
                    upl += '<input type="hidden" name="large_url[]" value="' + file.large_url + '">';
                    upl += '<input type="hidden" name="medium_url[]" value="' + file.medium_url + '">';
                    upl += '<input type="hidden" name="full_url[]" value="' + file.full_url + '">';

                    --}}

                    upl += '<input type="hidden" name="filetype[]" value="' + file.type + '">';
                    upl += '<input type="hidden" name="fileurl[]" value="' + file.url + '">';
                    upl += '<input type="hidden" name="file_id[]" value="' + file.file_id + '"></li>';

                    @if($singlefile == true)
                        $('#{{ $element_id }}_uploadedform ul').html(upl);
                    @else
                        $(upl).prependTo('#{{ $element_id }}_uploadedform ul');
                    @endif
                    clip = new ZeroClipboard($('.file_copy').each(function(){ }),{
                        moviePath: '{{ URL::to('js/zeroclipboard')}}/ZeroClipboard.swf'
                    });

                });
                //$('audio').audioPlayer();
                //videojs(document.getElementsByClassName('video-js')[0], {}, function(){});
            }else{
                alert(data.result.message)
            }
        },
        progressall: function (e, data) {
            var progress = parseInt(data.loaded / data.total * 100, 10);
            $('#{{ $element_id }}_progress .bar').css(
                'width',
                progress + '%'
            );
        }
    })
    .prop('disabled', !$.support.fileInput)
        .parent().addClass($.support.fileInput ? undefined : 'disabled');


});


</script>

