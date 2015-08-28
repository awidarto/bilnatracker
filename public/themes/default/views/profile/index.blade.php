@extends('layout.fixedstatic')

@section('left')
    <dl>
        <dt>Full Name</dt>
            <dd>{{ Auth::user()->salutation }} {{ Auth::user()->fullname }}</dd>
        <dt>Email</dt><dd>{{ Auth::user()->email }}</dd>
        <dt>Mobile</dt><dd>{{ Auth::user()->mobile }}</dd>
        <dt>Address</dt><dd>{{ Auth::user()->address_1 }}
            @if(Auth::user()->address_2 != '')
                <br />{{ Auth::user()->address_2 }}
            @endif
        </dd>
        <dt>City</dt><dd>{{ Auth::user()->city }}</dd>
        <dt>State / Provice</dt><dd>{{ Auth::user()->state }}</dd>
        <dt>Country</dt><dd>{{ Auth::user()->countryOfOrigin }}</dd>
    </dl>
@stop

@section('right')
    <img src="{{ Auth::user()->avatar}}" alt="avatar" class="avatar-medium" />
@stop