@extends('layout.makeprint')

@section('content')

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
    vertical-align: top;
    text-align: center;
    padding: 4px;
}

th:first-child{
    border-left:thin solid #eee;
}

.table th{
    padding: 4px 2px !important;
    font-size: 12px !important;
    background-color: white !important;
}

.table td{
    background-color: white !important;
    border-bottom: thin solid #eee !important;
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

td{
    font-size: 11px;
    padding: 4px 6px 6px 4px !important;
    hyphens:none !important;
    border: none !important;
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

.calendar-date thead th{
    border: none;
}

.column-amt{
    text-align: right;
}

.column-nowrap{
    white-space: nowrap !important;
}

tr.row-underline {
    border-bottom: thin solid #BBB !important;
    background-color: #FFF;
}

tr.row-underline td{
    background-color: transparent;
}

tr.row-overline {
    border-top: thin solid #BBB !important;
    background-color: #FFF;
}

tr.row-overline td{
    background-color: transparent;
}

tr.row-doubleunderline {
    background-color: #FFF;
}

tr.row-doubleunderline td.column-amt{
    border-bottom: double #BBB !important;
    background-color: transparent;
}

thead, tfoot { display: table-row-group !important }

</style>

@if(@additional_filter != '')
{{ $additional_filter }}
@endif

@foreach($tables as $table)

    {{ $table }}

@endforeach

@if($pdf == false)

<script type="text/javascript">

    $(document).ready(function(){
        window.print();
    });
  </script>

@endif

@stop

@section('modals')

@stop