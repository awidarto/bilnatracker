{{Former::open_for_files( URL::to('settings/photo') ,'POST',array('class'=>'','id'=>'upload-form'))}}

    <div class="row">
        <div class="col-md-6">
            <div class="innerLR">
                <?php
                    $fupload = new Fupload();
                ?>

                {{ $fupload->id('photoupload')->title('Select Photo')->label('Upload Photo')
                    ->url('upload/avatar')
                    ->singlefile(true)
                    ->prefix('photo')
                    ->multi(false)->make() }}
            </div>
        </div>
        <div class="col-md-6">
            <div class="innerLR">


            </div>
        </div>
    </div>
    <!-- Form actions -->
    <div class="separator top">
        <button type="submit" class="btn btn-primary"><i class="fa fa-fw fa-check-square-o"></i> Save</button>
        <a type="button" href="{{ URL::to('settings') }}" class="btn btn-default"><i class="fa fa-fw fa-times"></i> Cancel</a>
    </div>
    <!-- // Form actions END -->


{{Former::close()}}
