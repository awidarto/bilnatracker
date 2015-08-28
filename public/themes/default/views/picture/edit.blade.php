@extends('layout.front')


@section('content')
{{ HTML::style('css/cropper.min.css') }}

{{ HTML::script('js/cropper.min.js') }}

<h3>{{$title}}</h3>

<style type="text/css">
    fieldset{
        margin-top: 10px;
        border: solid thin #ccc;
        padding: 5px;
    }

    .property label.control-label{
        width:50px;
    }

    .property .controls{
        margin-left:55px;
    }

    img{
        max-width: none;
    }

    .action-box{
        margin-top: 6px;
        padding: 4px;
        border: thin solid #ccc;
    }

</style>

<script type="text/javascript">
    $(document).ready(function(){

        var $image = $('.img-container img'),
            $dataX1 = $('#data-x1'),
            $dataY1 = $('#data-y1'),
            $dataX2 = $('#data-x2'),
            $dataY2 = $('#data-y2'),
            $dataRatio = $('#data-ratio'),
            $dataHeight = $('#data-height'),
            $dataWidth = $('#data-width');

        var preview_width = $('.img-preview').width();
        var preview_height;

        var lastdata = {};

        var mode = 'crop';

        setLastData();

        $image.cropper({
            preview: ".img-preview",
            done: function(data) {
                $dataX1.val(data.x1);
                $dataY1.val(data.y1);
                $dataX2.val(data.x2);
                $dataY2.val(data.y2);
                $dataHeight.val(data.height);
                $dataWidth.val(data.width);

                setPreviewRatio(data);

                var cdata = $image.cropper('getImgInfo');
            }
        });

        $image.cropper('disable');
        $('#crop_original').hide();
        $('#expand_original').hide();

        $('.mode').on('click',function(){
            mode = $('.mode:checked').val();

            console.log(mode);

            if(mode == 'crop'){
                $('.img-preview').show();
                $('.img-container img').attr('src',$('#original').val());

                $image.cropper('enable');

                var currentcrop = $image.cropper('getData');
                console.log(currentcrop);
                setPreviewRatio(currentcrop);

                $('#is-crop').html('On');
                $('#is-expand').html('Off');

            }else{
                $('.img-container img').attr('src',$('#original').val());
                $('.img-preview').hide();
                $image.cropper('disable');
                $('#is-crop').html('Off');
                $('#is-expand').html('On');
            }
        });

        $('.aspect-ratio').on('click',function(){
            var aspect = $('.aspect-ratio:checked').val();

            if(aspect == 'none'){
                $image.cropper('setAspectRatio','auto');
            }else{
                $image.cropper('setAspectRatio',aspect);
            }

            var currentcrop = $image.cropper('getData');

            console.log(currentcrop);
            setPreviewRatio(currentcrop);

        });

        $('#crop_preview').on('click',function(){
            if( mode == 'crop'){
                setLastData();

                $.post('{{URL::to('picture/crop')}}',
                {
                    filename: $('#filename').val(),
                    id: $('#_id').val(),
                    mode : 'preview',
                    image : $image.cropper('getData')
                },
                function(data){
                    $image.cropper('disable');
                    $('.img-container img').attr('src',data.url);
                    $('#crop_preview').hide();
                    $('#crop_original').show();
                },
                'json');

                console.log(lastdata);

            }else{
                alert('You are in expand mode, no crop preview available');
            }
        })

        $('#crop_original').on('click',function(){
            console.log(lastdata);

            $('.img-container img').attr('src',$('#original').val());
            $('#crop_preview').show();
            $('#crop_original').hide();
            $image.cropper('enable');
            $image.cropper('setData',lastdata);
        });

        //expand action

        $('#expand_preview').on('click',function(){
            if( mode == 'expand'){

                var image = {
                    width: $('#x-width').val(),
                    height: $('#x-height').val()
                };

                $.post('{{URL::to('picture/expand')}}',
                {
                    filename: $('#filename').val(),
                    id: $('#_id').val(),
                    mode : 'preview',
                    image : image
                },
                function(data){
                    $image.cropper('disable');
                    $('.img-container img').attr('src',data.url);
                    $('#expand_preview').hide();
                    $('#expand_original').show();
                },
                'json');

                console.log(lastdata);

            }else{
                alert('You are in expand mode, no crop preview available');
            }
        })

        $('#expand_original').on('click',function(){
            console.log(lastdata);

            $('.img-container img').attr('src',$('#original').val());
            $('#expand_preview').show();
            $('#expand_original').hide();
        });


        function setLastData(){
            lastdata = {
                x1: $dataX1.val(),
                y1: $dataX2.val(),
                width: $dataWidth.val(),
                height: $dataHeight.val()
            };
        }

        function getLastData(){
            return lastdata;
        }

        function setPreviewRatio(data){
            if(data.height < data.width){
                preview_height = preview_width * ( data.height / data.width );
            }else if(data.height > data.width){
                preview_height = preview_width * ( data.width / data.height );
            }else if(data.height == data.width){
                preview_height = preview_width;
            }

            $('.img-preview').height( preview_height );
        }

        $('#save').on('click',function(){


            var applyto = [];

            $('.apply-to:checked').each(function(){
                applyto.push( this.value );
            });

            if(mode == 'crop'){
                if(typeof $image == 'undefined' ){
                    var image = lastdata;
                }else{
                    var image = $image.cropper('getData');
                }
            }else{
                var image = {
                    width: $('#x-width').val(),
                    height: $('#x-height').val()
                };
            }

            $.post('{{URL::to('picture/apply')}}',
            {
                filename: $('#filename').val(),
                id: $('#_id').val(),
                mode : mode,
                image : image,
                apply : applyto
            },
            function(data){
                if(data.result == 'OK'){
                    window.location = '{{ $back}}';
                }else{
                    alert('Something is not right');
                }
            },
            'json');

        });

    });

</script>

{{Former::open_for_files($submit,'POST',array('class'=>'custom addAttendeeForm'))}}

{{ Former::hidden('id')->value($formdata['_id'])->id('_id') }}
{{ Former::hidden('filename')->value($pic_info['filename'])->id('filename') }}
{{ Former::hidden('original')->value($src_url)->id('original') }}
<div class="row">
    <div class="col-md-9" style="border:thin solid #ccc;">
        <div class="img-container" style="position: relative;">
            <img src="{{ URL::to($src_url)}}" >
        </div>
    </div>


    <div class="span3 property">
        {{-- print_r($pic_info) --}}

        {{ Former::text('data-width','width')->id('data-width')->value($pic_info['width'])->class('col-md-4')}}
        {{ Former::text('data-height','height')->id('data-height')->value($pic_info['height'])->class('col-md-4')}}
        {{ Former::text('data-ratio','ratio')->id('data-ratio')->value($pic_info['ratio'])->class('col-md-4')}}

        <div class="row">
            <div class="col-md-12">
                <div class="img-preview"></div>
            </div>
        </div>

        <div class="row action-box">
            <h5>Expand</h5>
            <input type="radio" class="mode" name="mode" value="expand"/> <span id="is-expand">Off</span>
            {{ Former::button('Preview')->class('btn pull-right')->id('expand_preview') }}
            {{ Former::button('Original')->class('btn pull-right')->id('expand_original') }}
            <br />
            {{ Former::text('x-width','width')->id('x-width')->class('col-md-4')->value(800)}}
            {{ Former::text('x-height','height')->id('x-height')->class('col-md-4')->value(600)}}

        </div>

        <div class="row action-box">
            <h5>Crop</h5>
            <input type="radio" class="mode" name="mode" value="crop" checked="checked" /> <span id="is-crop">On</span>
            {{ Former::button('Preview')->class('btn pull-right')->id('crop_preview') }}
            {{ Former::button('Original')->class('btn pull-right')->id('crop_original') }}
            <br />
            {{ Former::text('data-x1','x1')->id('data-x1')->class('col-md-4')}}
            {{ Former::text('data-y1','y1')->id('data-y1')->class('col-md-4')}}
            {{ Former::text('data-x2','x2')->id('data-x2')->class('col-md-4')}}
            {{ Former::text('data-y2','y2')->id('data-y2')->class('col-md-4')}}
            <h6>Aspect Ratio ( width : height )</h6>
                <ul style="list-style:none">
            <li><input type="radio" name='aspect-ratio' class="aspect-ratio" value="none" checked="checked" /> Free</li>
            <li><input type="radio" name='aspect-ratio' class="aspect-ratio" value="{{ 4/3 }}" /> 4:3</li>
            <li><input type="radio" name='aspect-ratio' class="aspect-ratio" value="{{ 16/9 }}" /> 16:9</li>
            <li><input type="radio" name='aspect-ratio' class="aspect-ratio" value="{{ 1 }}" /> 1:1</li>

            <li><h6>System images ratio</h6></li>
            @foreach(Config::get('picture.sizes') as $name=>$size)

            <input type="radio" name='aspect-ratio' class="aspect-ratio" value="{{ $size['width'] / $size['height']}}" /> {{ $name }}<br />

            @endforeach

                </ul>

        </div>
        <div class="row action-box">
            <div class="col-md-12">
                <h5>Apply to</h5>
                <ul style="list-style:none">
                    <li>
                        <input type="checkbox" name='apply-to' class="apply-to" value="all" /> All
                    </li>
                @foreach(Config::get('picture.sizes') as $name=>$size)
                    <li>
                        <input type="checkbox" name='apply-to' class="apply-to" value="{{ $name }}" /> {{ $name }}
                    </li>
                @endforeach

                </ul>

                {{ Former::button('Apply & Save')->class('btn btn-primary')->id('save') }}&nbsp;&nbsp;
                {{ HTML::link($back,'Cancel',array('class'=>'btn'))}}

            </div>

        </div>

    </div>
</div>

{{Former::close()}}

@stop