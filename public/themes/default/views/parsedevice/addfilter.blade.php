<a class="btn btn-transparent" id="push_message"><i class="fa fa-send"></i> Push Message</a>
<a class="btn btn-transparent" id="sync_legacy"><i class="fa fa-refresh"></i> Sync Parse Data</a>
<span class="syncing" style="display:none;">Processing...</span>

<div id="push-message-modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
        <h3 id="myModalLabel">Push Message</span></h3>
    </div>
    <div class="modal-body" >
        {{ Former::text('title','Title')->id('title') }}
        {{ Former::textarea('message','Message')->id('message') }}
    </div>
    <div class="modal-footer">
        <button class="btn" data-dismiss="modal" aria-hidden="true">Cancel</button>
        <button class="btn btn-primary" id="do-send">Send</button>
    </div>
</div>

<div id="push-device-modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
        <h3 id="myModalLabel">Push Device ID</span></h3>
    </div>
    <div class="modal-body" id="push-modal-body" >
        {{ Former::text('parse_id','Parse ID')->id('parse-device-id') }}
        {{ Former::text('device_name','Device Identifier')->id('device-name')->class('auto_device form-control') }}
        {{ Former::text('device_key','Device Key')->id('device-key') }}
    </div>
    <div class="modal-footer">
        <button class="btn" data-dismiss="modal" aria-hidden="true">Cancel</button>
        <button class="btn btn-primary" id="do-send-device">Send</button>
    </div>
</div>

<style type="text/css">

.modal.large {
    width: 80%; /* respsonsive width */
    margin-left:-40%; /* width/2) */
}

.modal.large .modal-body{
    max-height: 800px;
    height: 500px;
    overflow: auto;
}

button#label_refresh{
    margin-top: 27px;
}

button#label_default{
    margin-top: 28px;
}

</style>

<script type="text/javascript">
    $(document).ready(function(){
        $('#assigned-product-filter').select2('destroy');

        $('#assigned-product-filter').on('change',function(){
            oTable.draw();
        });

        $('#push_message').on('click',function(e){
            $('#push-message-modal').modal();
            e.preventDefault();
        });

        $('#assign-product').on('click',function(e){
            $('#assign-modal').modal();
            e.preventDefault();
        });

        $('#do-send').on('click',function(){

            $.post('{{ URL::to('parsedevice/push')}}',
                {
                    message : $('#message').val(),
                    title : $('#title').val()
                },
                function(data){
                    $('#push-message-modal').modal('hide');
                    oTable.draw();
                }
                ,'json');

        });

        $('#do-send-device').on('click',function(){

            $.post('{{ URL::to('parsedevice/push')}}',
                {
                    message : 'Device ID Assignment',
                    title : 'New ID ' + $('#device-name').val(),
                    device_name : $('#device-name').val(),
                    device_key : $('#device-key').val(),
                    pinstall : $('#parse-device-id').val()
                },
                function(data){
                    $('#push-device-modal').modal('hide');
                    $('#device-name').val("");
                    $('#device-key').val("");
                }
                ,'json');

        });

        $('#do-assign').on('click',function(){
            var props = $('.selector:checked');
            var ids = [];
            $.each(props, function(index){
                ids.push( $(this).val() );
            });

            console.log(ids);

            if(ids.length > 0){
                $.post('{{ URL::to('ajax/assignshopcat')}}',
                    {
                        category : $('#assigned-category').val(),
                        product_ids : ids
                    },
                    function(data){
                        $('#assign-modal').modal('hide');
                        oTable.draw();
                    }
                    ,'json');

            }else{
                alert('No shop selected.');
                $('#assign-modal').modal('hide');
            }

        });

        $('#sync_legacy').on('click',function(e){
            $('.syncing').show();
            $.post('{{ URL::to( $sync_url )}}',
                {},
                function(data){
                    if(data.result == 'OK'){
                        alert('Parse data synced. ' + data.count + ' records updated' );
                        oTable.draw();
                    }else{
                        alert('Sync failed, nothing is changed');
                    }
                    $('.syncing').hide();
                }
                ,'json');
                e.preventDefault();
        });

        $('.auto_device').autocomplete({
            appendTo:'#push-modal-body',
            source: base + 'ajax/device',
            select: function(event, ui){
                $('#device-name').val(ui.item.value);
                $('#device-key').val(ui.item.id);
            }
        });


    });
</script>