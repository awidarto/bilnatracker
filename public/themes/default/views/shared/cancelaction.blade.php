<a class="btn btn-transparent btn-info btn-sm" id="cancel_data"><i class="fa fa-calendar"></i> Cancel Data</a>

<div id="cancel-data-modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
        <h3 id="myModalLabel">Cancel Data</span></h3>
    </div>
    <div class="modal-body" >
        {{ Former::textarea('cancel_reason', 'Reason' )->id('cancel-reason')->class('form-control') }}
    </div>
    <div class="modal-footer">
        <button class="btn" data-dismiss="modal" aria-hidden="true">Cancel</button>
        <button class="btn btn-primary" id="do-cancel-data">Save</button>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function(){

        $('#cancel_data').on('click',function(e){
            $('#cancel-data-modal').modal();
            e.preventDefault();
        });

        $('#do-cancel-data').on('click',function(){
            var props = $('.selector:checked');
            var ids = [];
            var reason = $('#cancel-reason').val();

            $.each(props, function(index){
                ids.push( $(this).val() );
            });

            console.log(ids);

            if(ids.length > 0){
                if(reason == '' ){

                    alert('Please specify reason for cancelation');
                }else{
                    $.post('{{ URL::to('ajax/canceldata')}}',
                        {
                            ids : ids,
                            reason : reason
                        },
                        function(data){
                            $('#cancel-data-modal').modal('hide');
                            oTable.draw();
                        }
                        ,'json');
                }

            }else{
                alert('No item selected.');
            }

        });


    });

</script>