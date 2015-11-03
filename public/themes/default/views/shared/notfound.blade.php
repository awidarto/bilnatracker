@extends('layout.makeprint')

@section('content')
<div style="width:100%;text-align:center;">
    <img src="{{ URL::to('images/hamster.jpg')}}" alt="not found" />
    <h2>Oops, can not find your content...</h2>
    <a href="{{ URL::to($backlink)}}">Ok, let's go back </a>
</div>


@stop