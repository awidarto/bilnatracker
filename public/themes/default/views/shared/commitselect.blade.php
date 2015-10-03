@extends('layout.make')

@section('content')

<style type="text/css">
    .form-horizontal .controls {
        margin-left: 0px;
    }

    table{
        font-size: 12px;
    }
</style>

<script type="text/javascript">
    $(document).ready(function(){
        /*
        $('#select_all').on('click',function(){
            if($('#select_all').is(':checked')){
                $('.selector').prop('checked', true);
            }else{
                $('.selector').prop('checked',false);
            }
        });

        $('#edit_select_all').on('click',function(){
            if($('#edit_select_all').is(':checked')){
                $('.edit_selector').attr('checked', true);
            }else{
                $('.edit_selector').attr('checked', false);
            }
        });

        */

        $('#select_all').on('ifChecked',function(){
            $('.selector').iCheck('check');
            //$('.selector').prop('checked', true);
        });

        $('#select_all').on('ifUnchecked',function(){
            $('.selector').iCheck('uncheck');
            //$('.selector').prop('checked', false);
        });


        $('#edit_select_all').on('ifChecked',function(){
            $('.edit_selector').iCheck('check');
            //$('.selector').prop('checked', true);
        });

        $('#edit_select_all').on('ifUnchecked',function(){
            $('.edit_selector').iCheck('uncheck');
            //$('.selector').prop('checked', false);
        });


    });

</script>

{{Former::open_for_files_vertical($submit,'POST',array('class'=>'custom addAttendeeForm'))}}
<div class="container">
    <h5>Import {{ $title }} Preview</h5>
    <div class="row">
        <div class="col-md-4">
            {{ Former::select('force_all')->label('Commit All Records')->options(array(0=>'No', 1=>'Yes'))->id('importkey')->class('form-control importkey input-sm')->help('Disregard selection checkbox and commit all rows to import. Including all data not shown in current preview page.') }}
            {{ Former::select('edit_key')->label('Edit Key')->options($headselect)->id('importkey')->class('form-control importkey input-sm')->help('select to set which field used for update id') }}
        </div>
        <div class="col-md-5" style="padding-top:25px;">
            {{ Former::submit('Commit Import')->id('execute')->class('btn btn-primary input-sm') }}&nbsp;&nbsp;
            {{ HTML::link($back,'Cancel',array('class'=>'btn input-sm'))}}
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-12">


        <table class="table table-condensed">
            <thead>
                <tr>
                    <th>
                        #
                    </th>
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
                            {{ Former::select()->name('headers[]')->label('')->options($headselect)->id($head)->class('heads form-control input-sm')->value($head) }}
                            <?php $head_id++; ?>
                        </th>
                    @endforeach
                </tr>
            </thead>
            <tbody>
                <?php
                    $counter = 1;
                ?>
                @foreach($imports->toArray() as $row)
                <tr>
                    <td>
                        {{ $counter }}
                    </td>
                    <td>
                        <input class="selector" name="selector[]" value="{{ $row['_id'] }}" type="checkbox">
                    </td>
                    <td>
                        <input class="edit_selector" name="edit_selector[]" value="{{ $row['_id'] }}" type="checkbox">
                    </td>
                    @foreach($row as $d)
                        <td>
                            @if( $d instanceof Carbon || $d instanceof MongoDate )
                                {{ $d->toRfc822String() }}
                            @else
                                {{ $d }}
                            @endif
                        </td>
                    @endforeach
                </tr>
                <?php
                    $counter++;
                ?>
                @endforeach
            </tbody>
        </table>
        <p>This preview only show max of 200 first data records to commit, therefore the selection checkbox only effective for imports with less than or equal to 200 data records.</p>
    </div>
</div>
{{ Former::close() }}
@stop