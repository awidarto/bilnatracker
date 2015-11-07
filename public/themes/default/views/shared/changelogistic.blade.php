<a class="btn btn-transparent btn-info btn-sm" id="change_logistic"><i class="fa fa-calendar"></i> Change Logistic</a>

<div id="change-logistic-modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
        <h3 id="myModalLabel">Change Logistic</span></h3>
    </div>
    <div class="modal-body" >
        {{ Former::select('logistic', "Logistic")->options( Prefs::getLogistic()->LogisticToSelection('logistic_code','name') )->id('logistic-code')->class('input-sm input-white form-control') }}
        {{ Former::textarea('change_logistic_reason', 'Reason' )->id('change-logistic-reason')->class('form-control') }}
    </div>
    <div class="modal-footer">
        <button class="btn" data-dismiss="modal" aria-hidden="true">Cancel</button>
        <button class="btn btn-primary" id="do-change-logistic">Change Logistic</button>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function(){

        $('#change_logistic').on('click',function(e){
            $('#change-logistic-modal').modal();
            e.preventDefault();
        });

        $('#do-change-logistic').on('click',function(){
            var logistic = $('#logistic-code').val();
            var props = $('.selector:checked');
            var ids = [];
            var reason = $('#change-logistic-reason').val();

            $.each(props, function(index){
                ids.push( $(this).val() );
            });

            console.log(ids);

            if(ids.length > 0){
                if(reason == '' ){

                    alert('Please specify reason for changing logistic carrier');
                }else{
                    $.post('{{ URL::to('ajax/changelogistic')}}',
                        {
                            logistic : logistic,
                            ids : ids,
                            reason : reason,
                            url : '{{ URL::current() }}'
                        },
                        function(data){
                            $('#change-logistic-modal').modal('hide');
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