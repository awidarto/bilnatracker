@extends('layout.xls')

@section('content')

@foreach($tables as $table)

    {{ $table }}

@endforeach


@stop

