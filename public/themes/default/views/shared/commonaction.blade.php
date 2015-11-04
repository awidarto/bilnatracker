<a class="btn btn-transparent btn-info btn-sm" id="reschedule_pickup"><i class="fa fa-calendar"></i> Reschedule</a>
<a class="btn btn-transparent btn-info btn-sm" id="reassign_to_device"><i class="fa fa-phone-square"></i> Reassign Device</a>
<a class="btn btn-transparent btn-info btn-sm" id="reassign_to_courier"><i class="fa fa-user"></i> Reassign Courier</a>
<br />


<div id="reassign-courier-modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
        <h3 id="myModalLabel">Re-Assign Courier</span></h3>
    </div>
    <div class="modal-body" id="push-modal-body" >

        {{ Former::text('re_courier_name','Reassign to Courier')->id('re-courier-name')->class('auto_courier_reassign form-control') }}
        {{ Former::text('re_courier_id','Courier ID')->id('re-courier-id') }}

        {{ Former::textarea('reason','Reason')->id('re-courier-reason') }}

    </div>
    <div class="modal-footer">
        <button class="btn" data-dismiss="modal" aria-hidden="true">Cancel</button>
        <button class="btn btn-primary" id="do-reassign-courier">Reassign Courier</button>
    </div>
</div>

<div id="device-reassign-modal" class="modal fade large" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
        <h3 id="myModalLabel">Re-Assign Selected</span></h3>
    </div>
    <div class="modal-body" >
        <div class="row">
            <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                {{ Former::textarea('reason','Reason')->id('re-device-reason') }}
                <br />

                <table id="shipment_list">
                    <thead>
                        <tr>
                            <th><input type="checkbox" id="dev_select_all" /></th>
                            <th>Order ID</th>
                            <th>Fulfillment</th>
                            <th>City</th>
                            <th>Package Count</th>
                        </tr>
                    </thead>
                    <tbody>
                        <td colspan="3">Loading shipment data...</td>
                    </tbody>
                </table>
            </div>
            <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6" style="scroll:auto;height:100%;">
                <table id="device_list">
                    <thead>
                        <tr>
                            <th></th>
                            <th>Device Name</th>
                            <th>Current Load</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td colspan="3">Loading available devices...</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <button class="btn" data-dismiss="modal" aria-hidden="true">Cancel</button>
        <button class="btn btn-primary" id="do-device-reassign">Reassign Device</button>
    </div>
</div>

<div id="reset-pickup-date-modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
        <h3 id="myModalLabel">Reschedule Delivery Date</span></h3>
    </div>
    <div class="modal-body" >
        {{ Former::text('pickup_date', 'Set Delivery Date' )->id('reschedule-pickup-date')->class('form-control d-datepicker') }}
        <?php
            $trip_count = Options::get('trip_per_day',1);
            $trips = array();
            for($t = 1; $t<= intval($trip_count);$t++ ){
                $trips[$t] = 'Trip '.$t;
            }

        ?>
        {{ Former::select('trip', 'Trip' )->id('reschedule-trip')->options( $trips ) }}
        {{ Former::textarea('reason','Reason')->id('re-reschedule-reason') }}

    </div>
    <div class="modal-footer">
        <button class="btn" data-dismiss="modal" aria-hidden="true">Cancel</button>
        <button class="btn btn-primary" id="do-reschedule">Reschedule</button>
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

</style>

<script type="text/javascript">
    $(document).ready(function(){
        $('#refresh_filter').on('click',function(){
            oTable.draw();
        });

        $('#outlet_filter').on('change',function(){
            oTable.draw();
        });

        $('#reassign_to_courier').on('click',function(e){
            $('#reassign-courier-modal').modal();
            e.preventDefault();
        });

        $('#do-reassign-courier').on('click',function(){
            var courier_name = $('#re-courier-name').val();
            var courier_id = $('#re-courier-id').val();
            var reason = $('#re-courier-reason').val();
            var ids = getSelected();

            //console.log(ids);

            if(courier_id != ''){
                $.post('{{ URL::to('ajax/reassigncourier')}}',
                    {

                        courier_name : courier_name,
                        courier_id : courier_id,
                        ids : ids,
                        reason : reason
                    },
                    function(data){
                        $('#reassign-courier-modal').modal('hide');
                        oTable.draw();
                    }
                    ,'json');

            }else{
                alert('Empty courier information.');
                $('#assign-courier-modal').modal('hide');
            }

        });




        $('#reassign_to_device').on('click',function(e){
            var ids = getSelected();

            if(ids.length == 0){
                alert('Please select item first');
            }else{
                $('#device-reassign-modal').modal();
            }

            e.preventDefault();
        });

        $('#device-reassign-modal').on('shown',function(){
            var ids = getSelected();

            $.post('{{ URL::to('ajax/shipmentlist')}}',
                {
                    ids : ids
                },
                function(data){
                    if(data.result == 'OK'){

                        var device_list = data.device;
                        var shipment_list = data.shipment;

                        $('table#shipment_list tbody').html('');
                        $('table#device_list tbody').html('');

                        if(shipment_list.length > 0){
                            $('table#shipment_list tbody').html('');
                        }

                        if(device_list.length > 0){
                            $('table#device_list tbody').html('');
                        }
                        $.each(device_list, function(index, val) {
                            $('table#device_list tbody').prepend('<tr><td><input type="radio" name="dev" class="devselect" value="' + val.key + '" ></td><td><b>' + val.identifier + '</b></td><td>' + val.count + '</td></tr>' + '<tr><td>&nbsp;</td><td colspan="2">' + val.city + '</td></tr>');
                        });

                        $.each(shipment_list, function(index, val) {

                            $('table#shipment_list tbody').prepend('<tr><td><input type="checkbox" class="shipselect" name="ship" value="' + val.delivery_id + '" ></td><td>' + val.order_id + '</td><td>' + val.fulfillment_code + '</td><td>' + val.consignee_olshop_city + '</td><td>' + val.number_of_package + '</td></tr>');
                        });

                    }else{

                    }
                }
                ,'json');

            console.log(date);

        });

        $('#do-device-reassign').on('click',function(){
            var ships = $('.shipselect:checked');

            var device = $('.devselect:checked');

            var reason = $('#re-device-reason').val();

            //var props = $('.selector:checked');
            var ids = [];
            $.each(ships, function(index){
                ids.push( $(this).val() );
            });

            console.log(ids);

            console.log(device.val());

            if(ids.length > 0){
                $.post('{{ URL::to('ajax/reassigndevice')}}',
                    {
                        device : device.val(),
                        ship_ids : ids,
                        reason : reason
                    },
                    function(data){
                        $('#device-reassign-modal').modal('hide');
                        oTable.draw();
                    }
                    ,'json');

            }else{
                alert('No product selected.');
                $('#device-reassign-modal').modal('hide');
            }

        });



        $('#reschedule_pickup').on('click',function(e){
            $('#reset-pickup-date-modal').modal();
            e.preventDefault();
        });

        $('#do-reschedule').on('click',function(){
            var props = $('.selector:checked');
            var ids = [];
            $.each(props, function(index){
                ids.push( $(this).val() );
            });

            var reason = $('#re-reschedule-reason').val();

            console.log(ids);

            if(ids.length > 0){
                $.post('{{ URL::to('ajax/reschedule')}}',
                    {
                        date : $('#reschedule-pickup-date').val(),
                        trip : $('#reschedule-trip').val(),
                        reason : reason,
                        ids : ids
                    },
                    function(data){
                        $('#reset-pickup-date-modal').modal('hide');
                        oTable.draw();
                    }
                    ,'json');

            }else{
                alert('No item selected.');
                $('#assign-modal').modal('hide');
            }

        });


        $('.auto_courier_reassign').autocomplete({
            appendTo:'#reassign-courier-modal',
            source: base + 'ajax/courier',
            select: function(event, ui){
                $('#re-courier-id').val(ui.item.id);
            }
        });

        function getSelected(){
            var props = $('.selector:checked');
            var ids = [];
            $.each(props, function(index){
                ids.push( $(this).val() );
            });

            return ids;
        }

    });
</script>