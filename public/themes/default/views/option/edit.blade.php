@extends('layout.front')


@section('content')

<h3>{{$title}}</h3>

{{Former::open_for_files_horizontal($submit,'POST',array('class'=>'custom addAttendeeForm'))}}

{{ Former::hidden('id')->value($formdata['_id']) }}

<div class="row-fluid">
    <div class="col-md-6">
        {{ Former::text('value', $formdata['label'] ) }}

        {{ Form::submit('Save',array('class'=>'btn btn-primary'))}}&nbsp;&nbsp;
        {{ HTML::link($back,'Cancel',array('class'=>'btn'))}}

    </div>
</div>

{{Former::close()}}

<script type="text/javascript">

$(document).ready(function() {

    $('#title').keyup(function(){
        var title = $('#title').val();
        var slug = string_to_slug(title);
        $('#permalink').val(slug);
    });

});

</script>

@stop


