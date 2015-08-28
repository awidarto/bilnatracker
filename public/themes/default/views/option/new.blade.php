@extends('layout.front')


@section('content')

<h3>{{$title}}</h3>

{{Former::open_for_files($submit,'POST',array('class'=>'custom addAttendeeForm'))}}

<div class="row-fluid">
    <div class="col-md-6">

        {{ Former::text('title','Title') }}
        {{ Former::text('permalink', 'URL friendly name')->id('permalink')}}
        {{ Former::text('docDepartment','Department') }}
        {{ Former::text('tags','Tags (visible)')->class('tag_keyword col-md-6') }}

        <?php
            $fupload = new Fupload();
        ?>

        {{ $fupload->id('filesupload')->title('Select File')->label('Upload File')->make() }}


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


