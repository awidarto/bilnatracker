@extends('layout.front')


@section('content')
<style type="text/css">
    ul.tablelist{
        list-style-type: none;
        text-align: center;
    }

    ul.tablelist li{
        float: left;
        margin: 5px;
    }

    .tablebox{
        width:60px;
        height: 60px;
        display: inline-block;
        text-align: center;
        position: relative;
        -webkit-border-radius: 8px;
        -moz-border-radius: 8px;
        border-radius: 8px;
        border:5px solid transparent;
    }

    .tablebox h3{
        width: 50%;
        height: 50%;
        overflow: auto;
        margin: auto;
        position: absolute;
        top: 0; left: 0; bottom: 0; right: 0;
    }

    .orange{
        background-color: orange;
        color: brown;
        font-weight: bold;
    }

    .green{
        background-color: green;
        color: white;
        font-weight: bold;
    }

    .scannerbox{
        display:block;
        text-align:center;
        margin-bottom:20px;
    }

    .scannerbox h3{
        font-weight: bold;
    }

    .hilite{
        border:5px solid blue;
    }

    h1#scanResult{
        display: inline-block;
        margin-bottom: 8px;
    }

</style>
<h3>{{$title}}</h3>

<div class="row-fluid">
    <div class="span7">
        <h1>Scanned Product</h1>

    </div>
    <div class="col-md-4">
        <div class="scannerbox">
            <img id="guest-photo" src="{{ URL::to('images/no-photo.png')}}">
            <h1 id="guest-name"></h1>

            {{ Former::text('barcode','')->id('barcode')->class('col-md-10') }}

            <div id="scanResult">
                Hello !
            </div>
        </div>
    </div>
</div>

{{ HTML::script('js/wysihtml5-0.3.0.min.js') }}
{{ HTML::script('js/parser_rules/advanced.js') }}

<script type="text/javascript">


$(document).ready(function() {

    $('select').select2({
      width : 'resolve'
    });

    $('#barcode').focus();

    $('#barcode').on('keyup',function(ev){
        if(ev.keyCode == '13'){
            onScanResult();
        }
        //$('#barcode').val($('#barcode').val() + event.keyCode);
    });

    function onScanResult(){
        var txtin = $("#barcode").val();

        $.post('{{ URL::to('ajax/scan') }}',
            { 'txtin':txtin },
            function(data){
                if(data.result == 'OK'){
                    $('#guest-name').html(data.attendee.name);
                    $('#guest-title').html(data.attendee.title);
                    $('#table-number').html(data.attendee.tableNumber);
                    $('#seat-number').html(data.attendee.seatNumber);

                    $('#scanResult').html(data.html);
                }else{
                    $('#guest-name').html(data.attendee.name);
                    $('#guest-title').html(data.attendee.title);
                    $('#table-number').html(data.attendee.tableNumber);
                    $('#seat-number').html(data.attendee.seatNumber);
                    $('#scanResult').html(data.html);
                }

                $('.tablebox').removeClass('hilite');

                $('#' + data.attendee.type +'-'+ data.attendee.tableNumber +'-box').addClass('hilite');

                var t = data.tabstat;

                $.each(t, function(key,val){
                    $('#' + key).html(val);
                });

                clearList();
            },'json'
        );
    }

    function clearList(){
        $('#barcode').val('').focus();
    }

});

</script>

@stop