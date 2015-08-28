@extends('layout.front')


@section('content')

<style type="text/css">
    th{
        background-color: #ccc;
    }

    table{
        margin-top: 10px;
    }
</style>

{{-- print_r($sheets); exit(); --}}

<?php
    $sheetcounter = 1;
    $rowcounter = 0;
?>

<button id="extractor" class="btn btn-primary">Extract Head</button>
<button id="commit" class="btn btn-primary">Commit Import</button>

@foreach($sheets as $name=>$sheet)
    <div class="row-fluid">
        <table class="table-striped">
            <thead>
                <tr>
                    <th colspan="100"><h4 class="pull-left">{{ Former::checkbox('')->name('selecsheet')->label('')->value($sheetcounter)}} Sheet : {{ $name }}</h4></th>
                </tr>
            </thead>
            @foreach($sheet as $key=>$row)
                @if(implode($row) != '')
                <tr id="{{ $sheetcounter.'_'.$key }}">
                    <td class="col-md-2" style="min-width:40px;">{{ Former::radio('head')->class('head-select')->label('header')->value($sheetcounter.'_'.$key)}}</td>
                    <td class="col-md-2" style="min-width:40px;">{{ Former::checkbox('')->name('selector')->label('')->value($sheetcounter.'_'.$key)}}</td>
                    @foreach($row as $r)
                        <td>
                            {{ $r }}
                        </td>
                    @endforeach
                </tr>
                <?php
                    $rowcounter++;
                ?>
                @endif
            @endforeach
        </table>
    <?php
        $sheetcounter++;
    ?>
    </div>

@endforeach

<script type="text/javascript">

$(document).ready(function(){

    $('#extractor').on('click',function(){
        var sel = $('input[type="radio"].head-select:checked').val();
        var heads = [];
        $('tr#'+sel+' td').each(function(){
            heads.push($(this).html());
        });
        $.post('{{ URL::to($extract) }}',{'ext': heads}, function(data) {
            if(data.status == 'OK'){
                alert("Heads extracted");
            }
        },'json');

    });


});

</script>

@stop