@extends('layout.make')

@section('content')

<style type="text/css">
    .form-horizontal .controls {
        margin-left: 0px;
    }
</style>

<script type="text/javascript">
    $(document).ready(function(){
        $('#select_all').on('click',function(){
            if($('#select_all').is(':checked')){
                $('.selector').attr('checked', true);
            }else{
                $('.selector').attr('checked', false);
            }
        });

        $('#edit_select_all').on('click',function(){
            if($('#edit_select_all').is(':checked')){
                $('.edit_selector').attr('checked', true);
            }else{
                $('.edit_selector').attr('checked', false);
            }
        });

    });

</script>

{{Former::open_for_files_vertical($submit,'POST',array('class'=>'custom addAttendeeForm'))}}
<div class="container">
    <h5>Import {{ $title }} Preview</h5>
    <div class="row">
        <div class="col-md-4">
            {{ Former::select('edit_key')->label('Edit Key')->options($headselect)->id('importkey')->class('form-control importkey')->help('select to set which field used for update id') }}
        </div>
        <div class="col-md-3" style="padding-top:12px;">
            {{ Former::submit('Commit Import')->id('execute')->class('btn btn-primary') }}&nbsp;&nbsp;
            {{ HTML::link($back,'Cancel',array('class'=>'btn'))}}
        </div>
        <div class="col-xs-2 col-sm-2 col-md-2 col-lg-2" style="padding-top:22px;">
            <span class="badge badge-primary">Duplicated in this import</span>
            <br /><br />
            <span class="badge badge-danger">Already exists in database</span>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-12">

        <table class="table table-condensed">
            <thead>
                <tr>
                    <th>
                        <label>Select All</label>
                        <input id="select_all" type="checkbox">
                    </th>
                    <th>
                        <label>Force Update</label>
                        <input id="edit_select_all" type="checkbox">
                    </th>
                    <th>
                        _id
                    </th>
                    <?php $head_id = 0; ?>
                    @foreach($heads as $head)
                        <th>
                            {{ Former::select()->name('headers[]')->label('')->options($headselect)->id($head)->class('heads form-control')->value($head) }}
                            <?php $head_id++; ?>
                        </th>
                    @endforeach
                </tr>
            </thead>
            <tbody>
                @foreach($imports->toArray() as $row)
                <tr>
                    <td>
                        <input class="selector" name="selector[]" value="{{ $row['_id'] }}" type="checkbox">
                    </td>
                    <td>
                        <input class="edit_selector" name="edit_selector[]" value="{{ $row['_id'] }}" type="checkbox">
                    </td>
                    @foreach($row as $d)
                        <td>
                            {{ $d }}
                            @if(isset($inval[$d]) && $inval[$d] > 1 )
                                <span class="badge badge-primary">{{ $inval[$d] - 1}}</span>
                            @endif
                            @if(isset($dbval[$d]) && $dbval[$d] > 0)
                                <span class="badge badge-danger">{{ $dbval[$d]}}</span>
                            @endif
                        </td>
                    @endforeach
                </tr>
                @endforeach
            </tbody>
        </table>

    </div>
</div>
{{ Former::close() }}
@stop