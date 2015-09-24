@extends('layout.maketwo')

@section('left')
        <h5>Node Information</h5>

        {{ Former::text('NAME','Node Name') }}
        {{ Former::text('NODE_CODE','Node Code') }}

        {{ Former::select('TYPE')->options(Config::get('jex.node_type'))->label('Type') }}

        {{ Former::select('status')->options(array('inactive'=>'Inactive','active'=>'Active'))->label('Status') }}

        {{ Former::select('default')->options(array('1'=>'Yes','0'=>'No'))->label('Is Default') }}

        <h5>Person In Charge Contact Info</h5>

        {{ Former::text('REP_NAME','Full Name') }}

        {{ Former::text('REP_EMAIL','Email') }}

        <div class="row">
            <div class="col-xs-3 col-sm-3 col-md-3 col-lg-3">
                {{ Former::text('REP_PHONE','Phone') }}
            </div>
            <div class="col-xs-3 col-sm-3 col-md-3 col-lg-3">
                {{ Former::text('REP_MOBILE_1','Mobile 1') }}
            </div>
            <div class="col-xs-3 col-sm-3 col-md-3 col-lg-3">
                {{ Former::text('REP_MOBILE_2','Mobile 2') }}
            </div>
            <div class="col-xs-3 col-sm-3 col-md-3 col-lg-3">
                {{ Former::text('REP_MOBILE_3','Mobile 3') }}
            </div>
        </div>


        {{ Former::text('REP_ADDR','Address') }}

        <div class="row">
            <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                {{ Former::text('REP_CITY','City') }}
            </div>
            <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                {{ Former::text('REP_ZIP','ZIP / Kode Pos') }}
            </div>
        </div>

        {{ Former::select('REP_COUNTRY')->id('country')->options(Config::get('country.countries'))->label('Country of Origin') }}


        {{ Form::submit('Save',array('class'=>'btn btn-primary'))}}&nbsp;&nbsp;
        {{ HTML::link($back,'Cancel',array('class'=>'btn'))}}

@stop

@section('right')
        <h5>Description & Support</h5>

        {{ Former::text('SUPPORT_URL','Support URL') }}

        {{ Former::textarea('NODE_DESC','Node Description')->rows(10)->columns(20) }}

        {{ Former::text('LATITUDE','Latitude') }}

        {{ Former::text('LONGITUDE','Longitude') }}

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

    $('.auto_merchant').autocomplete({
        source: base + 'ajax/merchant',
        select: function(event, ui){
            $('#merchant-id').val(ui.item.id);
        }
    });

    function updateselector(data){
        var opt = '';
        for(var k in data){
            opt += '<option value="' + k + '">' + data[k] +'</option>';
        }
        return opt;
    }


});

</script>

@stop