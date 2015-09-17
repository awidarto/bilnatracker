@extends('layout.maketwo')


@section('left')

<h5>Import {{ $title }}</h5>

{{Former::open_for_files_vertical($submit,'POST',array('class'=>'custom addAttendeeForm'))}}
        <div class="row">
            <div class="col-md-6">
                {{ Former::select('locationId','Location')->options( Assets::getLocation()->LocationToSelection('_id','name',true) )->class('form-control') }}

                {{ Former::file('inputfile','Select file ( .xls, .xlsx )')->class('form-control') }}

                {{ Former::hidden( 'controller',$back ) }}
                {{ Former::hidden( 'importkey',$importkey ) }}
                {{ Former::text('headindex','Row containing header')->class('form-control')->value(1) }}
                {{ Former::text('firstdata','Data starting at row')->class('form-control')->value(2) }}
            </div>
        </div>

        {{ Form::submit('Save',array('class'=>'btn btn-primary'))}}&nbsp;&nbsp;
        {{ HTML::link($back,'Cancel',array('class'=>'btn'))}}

{{Former::close()}}

@stop

@section('aux')

<script type="text/javascript">


$(document).ready(function() {

});

</script>

@stop