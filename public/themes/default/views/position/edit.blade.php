@extends('layout.maketwo')

@section('left')
        {{ Former::hidden('_id')->value($formdata['_id']) }}

        <h5>Node Information</h5>

        {{ Former::text('name','Node Name') }}
        {{ Former::text('node_code','Node Code') }}

        {{ Former::select('type')->options(Config::get('jex.node_type'))->label('Type') }}

        {{ Former::select('status')->options(array('inactive'=>'Inactive','active'=>'Active'))->label('Status') }}

        {{ Former::select('default')->options(array('1'=>'Yes','0'=>'No'))->label('Is Default') }}

        <h5>Person In Charge Contact Info</h5>

        {{ Former::text('rep_name','Full Name') }}

        {{ Former::text('rep_email','Email') }}

        <div class="row">
            <div class="col-xs-3 col-sm-3 col-md-3 col-lg-3">
                {{ Former::text('rep_phone','Phone') }}
            </div>
            <div class="col-xs-3 col-sm-3 col-md-3 col-lg-3">
                {{ Former::text('rep_mobile_1','Mobile 1') }}
            </div>
            <div class="col-xs-3 col-sm-3 col-md-3 col-lg-3">
                {{ Former::text('rep_mobile_2','Mobile 2') }}
            </div>
            <div class="col-xs-3 col-sm-3 col-md-3 col-lg-3">
                {{ Former::text('rep_mobile_3','Mobile 3') }}
            </div>
        </div>


        {{ Former::text('rep_addr','Address') }}

        <div class="row">
            <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                {{ Former::text('rep_city','City') }}
            </div>
            <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                {{ Former::text('rep_zip','ZIP / Kode Pos') }}
            </div>
        </div>

        {{ Former::select('rep_country')->id('country')->options(Config::get('country.countries'))->label('Country of Origin') }}


        {{ Form::submit('Save',array('class'=>'btn btn-primary'))}}&nbsp;&nbsp;
        {{ HTML::link($back,'Cancel',array('class'=>'btn'))}}

@stop

@section('right')
        <h5>Description & Support</h5>

        {{ Former::text('support_url','Support URL') }}

        {{ Former::textarea('node_desc','Node Description')->rows(10)->columns(20) }}

        {{ Former::text('latitude','Latitude') }}

        {{ Former::text('longitude','Longitude') }}

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