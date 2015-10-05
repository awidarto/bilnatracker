@extends('layout.maketwo')

@section('left')
        {{ Former::hidden('_id')->value($formdata['_id']) }}

        <h5>Coverage Area Information</h5>

        {{ Former::text('district','Kecamatan / District') }}
        {{ Former::text('area','Area') }}
        {{ Former::text('city','City') }}
        {{ Former::text('zips','ZIPs') }}
        {{ Former::text('province','Province') }}
        {{ Former::text('country','Country') }}

        {{ Form::submit('Save',array('class'=>'btn btn-primary'))}}&nbsp;&nbsp;
        {{ HTML::link($back,'Cancel',array('class'=>'btn'))}}

@stop

@section('right')
        <h5>Metadata</h5>

        <div class="row">
            <div class="col-xs-4 col-sm-4 col-md-4 col-lg-4">
                {{ Former::text('leadtime','Lead Time')->class('form-control') }}
            </div>
            <div class="col-xs-4 col-sm-4 col-md-4 col-lg-4">
                {{ Former::select('is_on','Active')->options(array('1'=>'Yes','0'=>'No'))->class('form-control input-sm') }}
            </div>
        </div>

@stop

@section('modals')

@stop

@section('aux')
{{ HTML::style('css/summernote.css') }}
{{ HTML::style('css/summernote-bs3.css') }}

{{ HTML::script('js/summernote.min.js') }}

<script type="text/javascript">


$(document).ready(function() {


    $('.pick-a-color').pickAColor();

    $('#name').keyup(function(){
        var title = $('#name').val();
        var slug = string_to_slug(title);
        $('#permalink').val(slug);
    });

    $('.editor').summernote({
        height:500
    });

    $('#location').on('change',function(){
        var location = $('#location').val();
        console.log(location);

        $.post('{{ URL::to('asset/rack' ) }}',
            {
                loc : location
            },
            function(data){
                var opt = updateselector(data.html);
                $('#rack').html(opt);
            },'json'
        );

    })


});

</script>

@stop