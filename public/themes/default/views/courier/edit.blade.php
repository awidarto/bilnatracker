@extends('layout.maketwo')

@section('left')
        {{ Former::hidden('_id')->value($formdata['_id']) }}

        <h5>Courier Information</h5>

        {{ Former::text('NAME','Full Name') }}

        <div class="row">
            <div class="col-xs-3 col-sm-3 col-md-3 col-lg-3">
                {{ Former::select('ID_TYPE')->options(array('KTP'=>'KTP','SIM'=>'SIM'))->label('ID Type') }}
            </div>
            <div class="col-xs-3 col-sm-3 col-md-3 col-lg-3">
                {{ Former::text('IDENTITY_NUMBER','ID Number') }}
            </div>
        </div>

        {{ Former::select('TYPE')->options(array('internal'=>'Internal','external'=>'External'))->label('Courier Type') }}

        {{ Former::select('status')->options(array('inactive'=>'Inactive','active'=>'Active'))->label('Status') }}

        <h5>Personal Contact Info</h5>

        {{ Former::text('ADDR','Address') }}

        <div class="row">
            <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                {{ Former::text('CITY','City') }}
            </div>
            <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                {{ Former::text('ZIP','ZIP / Kode Pos') }}
            </div>
        </div>

        <div class="row">
            <div class="col-xs-3 col-sm-3 col-md-3 col-lg-3">
                {{ Former::text('PHONE','Phone') }}
            </div>
            <div class="col-xs-3 col-sm-3 col-md-3 col-lg-3">
                {{ Former::text('MOBILE_1','Mobile 1') }}
            </div>
            <div class="col-xs-3 col-sm-3 col-md-3 col-lg-3">
                {{ Former::text('MOBILE_2','Mobile 2') }}
            </div>
            <div class="col-xs-3 col-sm-3 col-md-3 col-lg-3">
                {{ Former::text('MOBILE_3','Mobile 3') }}
            </div>
        </div>

        {{ Former::text('EMAIL','Email') }}


        {{ Former::select('COUNTRY')->id('country')->options(Config::get('country.countries'))->label('Country of Origin') }}


        {{ Form::submit('Save',array('class'=>'btn btn-primary'))}}&nbsp;&nbsp;
        {{ HTML::link($back,'Cancel',array('class'=>'btn'))}}

@stop

@section('right')
        <h5>Description & Support</h5>

        {{ Former::textarea('REMARK','Notes & Remarks')->rows(10)->columns(20) }}


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