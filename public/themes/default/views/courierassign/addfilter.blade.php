<a class="btn btn-transparent btn-info btn-sm" id="print_barcodes"><i class="fa fa-print"></i> Print QR Label</a>
{{--
<a class="btn btn-transparent btn-info btn-sm" id="move_orders"><i class="fa fa-arrows"></i> Move Selected to</a>
--}}
<a class="btn btn-transparent btn-info btn-sm" id="reschedule_pickup"><i class="fa fa-calendar"></i> Reschedule</a>

<a class="btn btn-transparent btn-info btn-sm" id="reassign_to_device"><i class="fa fa-phone-square"></i> Reassign Device</a>

<a class="btn btn-transparent btn-info btn-sm" id="set-courier"><i class="fa fa-user"></i> Assign Courier</a>

<div id="assign-courier-modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
        <h3 id="myModalLabel">Assign Courier</span></h3>
    </div>
    <div class="modal-body" id="push-modal-body" >

        {{ Former::text('courier_name','Courier')->id('courier-name')->class('auto_courier form-control') }}
        {{ Former::text('courier_id','Courier ID')->id('courier-id') }}

        {{ Former::text('pickup_date','Pick Up Date')->id('pickup-date')->class('form-control')->readonly(true) }}
        {{ Former::text('device_name','Device')->id('device-name')->class('form-control')->readonly(true) }}
        {{ Former::text('device_key','Device')->id('device-key')->class('form-control')->readonly(true) }}

    </div>
    <div class="modal-footer">
        <button class="btn" data-dismiss="modal" aria-hidden="true">Cancel</button>
        <button class="btn btn-primary" id="do-assign-courier">Assign</button>
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
        {{ Former::select('trip', 'Trip' )->id('trip')->options( $trips ) }}

        {{ Former::textarea('reason','Reason')->id('re-reschedule-reason') }}

    </div>
    <div class="modal-footer">
        <button class="btn" data-dismiss="modal" aria-hidden="true">Cancel</button>
        <button class="btn btn-primary" id="do-reschedule">Reschedule</button>
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


<div id="move-order-modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
        <h3 id="myModalLabel">Move Selected</span></h3>
    </div>
    <div class="modal-body" >
        {{ Former::select('status', 'To' )->id('move-to')->options(Config::get('jex.buckets')) }}
    </div>
    <div class="modal-footer">
        <button class="btn" data-dismiss="modal" aria-hidden="true">Cancel</button>
        <button class="btn btn-primary" id="do-move">Move</button>
    </div>
</div>


<div id="print-modal" class="modal fade large" tabindex="-1" role="dialog" aria-labelledby="myPrintModalLabel" aria-hidden="true">
    <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
    <h3 id="myPrintModalLabel">Print Selected Codes</span></h3>
    </div>
    <div class="modal-body" style="overflow:auto;" >
        <h6>Print options</h6>
        <div style="border-bottom:thin solid #ccc;" class="row clearfix">
            <div class="col-md-2">
                {{ Former::text('label_columns','Number of columns')->value('4')->id('label_columns')->class('form-control input-sm') }}
                {{ Former::text('label_res','Resolution')->value('150')->id('label_res')->class('form-control input-sm') }}
            </div>
            <div class="col-md-2">
                {{ Former::text('label_cell_height','Label height')->value('230')->id('label_cell_height')->class('form-control input-sm') }}
                {{ Former::text('label_cell_width','Label width')->value('200')->id('label_cell_width')->class('form-control input-sm') }}
            </div>
            <div class="col-md-2">
                {{ Former::text('label_margin_right','Label margin right')->value('8')->id('label_margin_right')->class('form-control input-sm') }}
                {{ Former::text('label_margin_bottom','Label margin bottom')->value('10')->id('label_margin_bottom')->class('form-control input-sm') }}
            </div>
            <div class="col-md-2">
                {{ Former::text('label_offset_right','Page left offset')->value('40')->id('label_offset_right')->class('form-control input-sm') }}
                {{ Former::text('label_offset_bottom','Page top offset')->value('20')->id('label_offset_bottom')->class('form-control input-sm') }}
            </div>
            <div class="col-md-2">
                {{ Former::text('font_size','Font size')->value('12')->id('font_size')->class('form-control input-sm') }}
                {{ Former::select('code_type','Code type')->id('code_type')->options(array('qr'=>'QR','barcode'=>'Barcode') )}}
            </div>
            <div class="col-md-2">
                <button id="label_default" class="btn btn-primary btn-sm" ><i class="fa fa-save"></i> make default</button>
                <button id="label_refresh" class="btn btn-primary btn-sm" ><i class="fa fa-refresh"></i> refresh</button>
            </div>
        </div>
        <input type="hidden" value="" id="session_name" />
        <input type="hidden" value="" id="label_id" />

        <div style="height:100%;width:100%;">
            <iframe id="label_frame" name="label_frame" width="100%" height="90%"
            marginWidth="0" marginHeight="0" frameBorder="0" scrolling="auto"
            title="Dialog Title">Your browser does not suppr</iframe>

        </div>
    </div>
    <div class="modal-footer" style="z-index:20000;">
        <button class="btn" data-dismiss="modal" aria-hidden="true">Cancel</button>
        <button class="btn btn-primary" id="do-print">Print</button>
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

        $('#dev_select_all').on('click',function(){
            if($('#dev_select_all').is(':checked')){
                $('.shipselect').prop('checked', true);
            }else{
                $('.shipselect').prop('checked',false);
            }
        });

        $('#dev_select_all').on('ifChecked',function(){
            $('.shipselect').prop('checked', true);
        });

        $('#dev_select_all').on('ifUnchecked',function(){
            $('.shipselect').prop('checked', false);
        });

        $('#refresh_filter').on('click',function(){
            oTable.draw();
        });

        $('#outlet_filter').on('change',function(){
            oTable.draw();
        });

        $('#set-courier').on('click',function(e){
            var date = $('input[name=date_select]:checked').val();
            var device = $('input[name=device_select]:checked').val();
            var device_name = $('input[name=device_select]:checked').data('name');

            $('#device-key').val(device);
            $('#device-name').val(device_name);
            $('#pickup-date').val(date);

            $('#assign-courier-modal').modal();
            e.preventDefault();
        });

        $('#label_refresh').on('click',function(){

            var sessionname = $('#session_name').val();

            var col = $('#label_columns').val();
            var res = $('#label_res').val();
            var cell_width = $('#label_cell_width').val();
            var cell_height = $('#label_cell_height').val();
            var margin_right = $('#label_margin_right').val();
            var margin_bottom = $('#label_margin_bottom').val();
            var font_size = $('#font_size').val();
            var code_type = $('#code_type').val();
            var offset_left = $('#label_offset_left').val();
            var offset_top = $('#label_offset_top').val();
            var src = '{{ URL::to('incoming/printlabel')}}/' + sessionname + '/' + col + ':' + res + ':' + cell_width + ':' + cell_height + ':' + margin_right + ':' + margin_bottom + ':' + font_size + ':' + code_type + ':' + offset_left + ':' + offset_top;

            $('#label_frame').attr('src',src);

            e.preventDefault();
        });

        $('#print_barcodes').on('click',function(){

            var props = $('.selector:checked');
            var ids = [];
            $.each(props, function(index){
                ids.push( $(this).val() );
            });

            console.log(ids);

            if(ids.length > 0){
                $.post('{{ URL::to('ajax/sessionsave')}}',
                    {
                        data_array : ids
                    },
                    function(data){
                        if(data.result == 'OK'){
                            $('#session_name').val(data.sessionname);

                            var col = $('#label_columns').val();
                            var res = $('#label_res').val();
                            var cell_width = $('#label_cell_width').val();
                            var cell_height = $('#label_cell_height').val();
                            var margin_right = $('#label_margin_right').val();
                            var margin_bottom = $('#label_margin_bottom').val();
                            var font_size = $('#font_size').val();
                            var code_type = $('#code_type').val();
                            var offset_left = $('#label_offset_left').val();
                            var offset_top = $('#label_offset_top').val();
                            var src = '{{ URL::to('incoming/printlabel')}}/' + data.sessionname + '/' + col + ':' + res + ':' + cell_width + ':' + cell_height + ':' + margin_right + ':' + margin_bottom + ':' + font_size + ':' + code_type + ':' + offset_left + ':' + offset_top;
                            $('#label_frame').attr('src',src);
                            $('#print-modal').modal('show');
                        }else{
                            $('#print-modal').modal('hide');
                        }
                    }
                    ,'json');

            }else{
                alert('No product selected.');
                $('#print-modal').modal('hide');
            }

        });

        $('#do-print').click(function(){

            var pframe = document.getElementById('label_frame');
            var pframeWindow = pframe.contentWindow;
            pframeWindow.print();

        });

        $('#label_default').on('click',function(){
            var col = $('#label_columns').val();
            var res = $('#label_res').val();
            var cell_width = $('#label_cell_width').val();
            var cell_height = $('#label_cell_height').val();
            var margin_right = $('#label_margin_right').val();
            var margin_bottom = $('#label_margin_bottom').val();
            var font_size = $('#font_size').val();
            var code_type = $('#code_type').val();
            var offset_left = $('#label_offset_left').val();
            var offset_top = $('#label_offset_top').val();

            $.post('{{ URL::to('ajax/printdefault')}}',
                {
                    col : col,
                    res : res,
                    cell_width : cell_width,
                    cell_height : cell_height,
                    margin_right : margin_right,
                    margin_bottom : margin_bottom,
                    font_size : font_size,
                    code_type : code_type,
                    offset_left : offset_left,
                    offset_top : offset_top
                },
                function(data){
                    if(data.result == 'OK'){
                        alert('Print parameters set as default');
                    }else{
                        alert('Print parameters failed to set default');
                    }
                }
                ,'json');
                e.preventDefault();

        });

        $('#do-move').on('click',function(){
            var props = $('.selector:checked');
            var ids = [];
            $.each(props, function(index){
                ids.push( $(this).val() );
            });

            console.log(ids);

            if(ids.length > 0){
                $.post('{{ URL::to('ajax/moveorder')}}',
                    {
                        bucket : $('#move-to').val(),
                        ids : ids
                    },
                    function(data){
                        $('#move-order-modal').modal('hide');
                        oTable.draw();
                    }
                    ,'json');

            }else{
                alert('No shipment selected.');
                $('#move-order-modal').modal('hide');
            }

        });


        $('#do-assign-courier').on('click',function(){
            var courier_name = $('#courier-name').val();
            var courier_id = $('#courier-id').val();
            var device_key = $('#device-key').val();
            var device_name = $('#device-name').val();
            var pickup_date = $('#pickup-date').val();

            if(courier_id != ''){
                $.post('{{ URL::to('courierassign/assigncourier')}}',
                    {

                        courier_name : courier_name,
                        courier_id : courier_id,
                        device_key : device_key,
                        device_name : device_name,
                        pickup_date : pickup_date

                    },
                    function(data){
                        $('#assign-courier-modal').modal('hide');
                        oTable.draw();
                    }
                    ,'json');

            }else{
                alert('Empty courier information.');
                $('#assign-courier-modal').modal('hide');
            }

        });


        $('#do-assign').on('click',function(){
            var props = $('.selector:checked');
            var ids = [];
            $.each(props, function(index){
                ids.push( $(this).val() );
            });

            console.log(ids);

            if(ids.length > 0){
                $.post('{{ URL::to('ajax/assignoutlet')}}',
                    {
                        outlet : $('#assigned-category').val(),
                        product_ids : ids
                    },
                    function(data){
                        $('#assign-modal').modal('hide');
                        oTable.draw();
                    }
                    ,'json');

            }else{
                alert('No product selected.');
                $('#assign-modal').modal('hide');
            }

        });

        $('#unassign-prop').on('click',function(){
            var props = $('.selector:checked');
            var ids = [];
            $.each(props, function(index){
                ids.push( $(this).val() );
            });

            console.log(ids);

            var answer = confirm('Are you sure you want to un-assign these Properties ?');

            console.log(answer);

            if (answer == true){

                $.post('{{ URL::to('ajax/unassign')}}',
                {
                    user_id : $('#assigned-agent-filter').val(),
                    prop_ids : ids
                },
                function(data){
                    oTable.draw();
                }
                ,'json');

            }else{
                alert("Unassignment cancelled");
            }

        });

        $('.auto_courier').autocomplete({
            appendTo:'#assign-courier-modal',
            source: base + 'ajax/courier',
            select: function(event, ui){
                $('#courier-id').val(ui.item.id);
            }
        });

        $('#reschedule_pickup').on('click',function(e){
            $('#reset-pickup-date-modal').modal();
            e.preventDefault();
        });

        $('#do-reschedule').on('click',function(){
            var currentdate = $('input[name=date_select]:checked').val();
            var device = $('input[name=device_select]:checked').val();
            var device_name = $('input[name=device_select]:checked').data('name');

            var reason = $('#re-reschedule-reason').val();

            if(currentdate != '' && device != '' && device_name != ''){
                $.post('{{ URL::to('courierassign/reschedule')}}',
                    {
                        currentdate : currentdate,
                        date : $('#reschedule-pickup-date').val(),
                        trip : $('#trip').val(),
                        reason : reason,
                        device : device,
                        device_name : device_name
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

        $('#reassign_to_device').on('click',function(e){
            var currentdate = $('input[name=date_select]:checked').val();
            var device = $('input[name=device_select]:checked').val();

            if(currentdate == '' || device == ''){
                alert('Please select item first');
            }else{
                $('#device-reassign-modal').modal();
            }

            e.preventDefault();
        });

        $('#device-reassign-modal').on('shown',function(){
            var currentdate = $('input[name=date_select]:checked').val();
            var device = $('input[name=device_select]:checked').val();
            var device_name = $('input[name=device_select]:checked').data('name');

            var reason = $('#re-reschedule-reason').val();

            $.post('{{ URL::to('courierassign/shipmentlist')}}',
                {
                    currentdate : currentdate,
                    device : device
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

            console.log(currentdate);

        });

        $('#do-device-reassign').on('click',function(){

            var ships = $('.shipselect:checked');

            var ids = [];
            $.each(ships, function(index){
                ids.push( $(this).val() );
            });

            var currentdevice = $('input[name=device_select]:checked').val();
            var device = $('.devselect:checked');
            var device_name = $('input[name=device_select]:checked').data('name');

            var reason = $('#re-device-reason').val();

            if(currentdevice != '' && device != '' && device_name != ''){
                $.post('{{ URL::to('courierassign/reassigndevice')}}',
                    {
                        ship_ids : ids,
                        device : device.val(),
                        currentdevice : currentdevice,
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



    });
</script>