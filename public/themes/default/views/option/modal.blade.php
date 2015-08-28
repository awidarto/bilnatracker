<div id="upload-modal" class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title" id="myModalLabel">Quick Upload</h4>
      </div>
      <div class="modal-body">
        <h4 id="upload-title-id"></h4>
        {{ Former::open()->id('upload-form') }}
        {{ Former::hidden('upload_id')->id('upload-id') }}

        <?php
            $fupload = new Fupload();
        ?>

        {{ $fupload->id('pictureupload')->title('Select Images')->label('Upload Images')->make() }}

        {{ Former::close() }}
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary" id="do-upload" >Save changes</button>
      </div>
    </div>
  </div>
</div>

<script type="text/javascript">
  $(document).ready(function(){
    $('#upload-modal').on('hidden',function(){
      $('#pictureupload_files ul').html('');
      $('#pictureupload_uploadedform ul').html('');
    });

    $('#do-upload').on('click',function(){
      var form = $('#upload-form');
      console.log(form.serialize());

      $.post(
        '{{ URL::to('ajax/documentfiles')}}',
          form.serialize(),
          function(data){
            if(data.result == 'OK:UPLOADED'){
              $('#upload-modal').modal('hide');
              oTable.fnDraw();
            }else if( data.result == 'ERR:UPDATEFAILED' ){
              alert('Upload failed');
            }
          },
          'json'
        );

    });

  });

</script>