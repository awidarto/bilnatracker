@extends('layout.plainprint')

@section('content')

<img src="{{ URL::to('images/hamster.jpg')}}" alt="not found" />

<h2>Oops, can not find your content...</h2>

@stop