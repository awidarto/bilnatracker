@extends('layout.fixed')

@section('content')

{{ HTML::script('js/Chart.min.js') }}

<style type="text/css">
.act{
    cursor: pointer;
}

.pending{
    padding: 4px;
    background-color: yellow;
}

.canceled{
    padding: 4px;
    background-color: red;
    color:white;
}

.sold{
    padding: 4px;
    background-color: green;
    color:white;
}

th{
    border-right:thin solid #eee;
    border-top: thin solid #eee;
}

th:first-child{
    border-left:thin solid #eee;
}

.del,.upload,.upinv,.outlet,.action{
    cursor:pointer;
}

td.group{
    background-color: #AAA;
}

.ingrid.styled-select select{
    width:100px;
}

.table-responsive{
    overflow-x: auto;
}

th.action{
    min-width: 150px !important;
    max-width: 200px !important;
    width: 175px !important;
}

td i.fa{
    font-size: 18px;
    line-height: 20px;
}

td a{
    line-height: 22px;
}
select.input-sm {
    height: 30px;
    line-height: 30px;
    padding-top: 0px !important;
}

.panel-heading{
    font-size: 20px;
    font-weight: bold;
}

.tag{
    padding: 2px 4px;
    margin: 2px;
    background-color: #CCC;
    display:inline-block;
}

.calendar{
    box-shadow: none;
}

.form-vertical input[type=submit], .form-vertical a.btn{
    margin-top: 0px;
}

.responsive-chart{
    margin: 8px;
}


</style>


{{--
<div class="row-fluid box">
   <div class="col-md-12 box-content">
        <table class="table table-condensed dataTable">--}}
<div class="container">
    <div class="row">
        <div class="col-md-6 command-bar">
            {{ $additional_filter }}


         </div>
         <div class="col-md-6 command-bar">
            @if(isset($can_add) && $can_add == true)
                <a href="{{ URL::to($addurl) }}" class="btn btn-sm btn-primary">Add</a>
                <a href="{{ URL::to($importurl) }}" class="btn btn-sm btn-primary">Import Excel</a>
            @endif

            <a class="btn btn-info btn-sm" id="download-xls">Download Excel</a>
            <a class="btn btn-info btn-sm" id="download-csv">Download CSV</a>

            @if(isset($is_report) && $is_report == true)
                {{ $report_action }}
            @endif
            @if(isset($is_additional_action) && $is_additional_action == true)
                {{ $additional_action }}
            @endif

         </div>
    </div>
</div>
<div class="container">

    <div class="row">
        <div class="col-md-6">
            <?php
                $chart01 = new Chartjs();
            ?>
            {{ $chart01->id('myChart01')
                    ->setLabel($data['labels'])
                    ->addDataArray($data['series01'])
                    ->addDataArray($data['series03'])
                    ->setType('bar')->make() }}
        </div>
        <div class="col-md-6">
            <?php
                $chart02 = new Chartjs();

                $options = array(
                        'responsive'=>true,
                        'bezierCurve'=>false
                    );
            ?>
            {{ $chart02->id('myChart02')
                    ->setOptions($options)
                    ->setLabel($data['labels'])
                    ->addDataArray($data['series02'])
                    ->addDataArray($data['series03'])
                    ->setType('line')->make() }}

        </div>
    </div>

</div>



<div id="blueimp-gallery" class="blueimp-gallery blueimp-gallery-controls">
    <div class="slides"></div>
    <h3 class="title"></h3>
    <a class="prev">‹</a>
    <a class="next">›</a>
    <a class="close">×</a>
    <a class="play-pause"></a>
    <ol class="indicator"></ol>
</div>

<script type="text/javascript">
    $(document).ready(function(){
    });

</script>

@stop

@section('modals')

{{ $modal_sets }}

@stop