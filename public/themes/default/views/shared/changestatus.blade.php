<a class="btn btn-transparent btn-info btn-sm" id="change_status"><i class="fa fa-arrows"></i> Change Shipment Status</a>

<div id="change-status-modal" class="modal fade large" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
        <h3 id="myModalLabel">Change Status Selected</span></h3>
    </div>
    <div class="modal-body" >
        <div class="row">
            <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                {{ Former::textarea('reason','Reason')->id('re-status-reason') }}
                {{ Former::select('Delivery Status','delivery_status')->options(Config::get('jayon.dialog_delivery_status'))->id('delivery_status') }}
                {{ Former::select('Courier Status','courier_status')->options(Config::get('jayon.dialog_courier_status'))->id('courier_status') }}
                {{ Former::select('Hub Status','warehouse_status')->options(Config::get('jayon.dialog_warehouse_status'))->id('warehouse_status') }}
                {{ Former::select('Position','position')->options(Prefs::getPosition()->PositionToSelection('node_code','name',true,'Tidak Ada Perubahan'))->id('chg_position') }}
            </div>
            <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                <table id="order_shipment_list">
                    <thead>
                        <tr>
                            <th><input type="checkbox" id="box_select_all" /></th>
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
        </div>
    </div>
    <div class="modal-footer">
        <button class="btn" data-dismiss="modal" aria-hidden="true">Cancel</button>
        <button class="btn btn-primary" id="do-change-status">Set Status</button>
    </div>
</div>

<script type="text/javascript">

    $(document).ready(function(){
                /* box status */
        $('#change_status').on('click',function(e){
            var ids = getSelectedChg();

            if(ids.length == 0){
                alert('Please select item first');
            }else{
                $('#change-status-modal').modal();
            }

            e.preventDefault();
        });

        $('#change-status-modal').on('shown',function(){
            var ids = getSelectedChg();

            $.post('{{ URL::to('ajax/boxlist')}}',
                {
                    ids : ids
                },
                function(data){
                    if(data.result == 'OK'){

                        var box_list = data.boxes;
                        var shipment_list = data.shipment;

                        $('table#order_shipment_list tbody').html('');
                        $('table#order_box_list tbody').html('');

                        if(shipment_list.length > 0){
                            $('table#order_shipment_list tbody').html('');
                        }
                        /*
                        if(box_list.length > 0){
                            $('table#order_box_list tbody').html('');
                        }
                        $.each(box_list, function(index, val) {
                            $('table#order_box_list tbody').prepend('<tr><td><input type="checkbox" name="box_' + val.key + '_' + val.box_id + '" class="boxselect" value="' + val.key + '" ></td><td><b>' + val.box_id + '</b></td><td>' + val.deliveryStatus + '</td><td></td></tr>' + '<tr><td>&nbsp;</td><td>' + val.deliveryStatus + '</td><td>' + val.courierStatus + '</td><td>' + val.warehouseStatus + '</td></tr>');
                        });
                        */
                        var shipment_tab = $('table#order_shipment_list tbody');

                        $.each(shipment_list, function(index, val) {


                        //' + box_tab_list.html() + '</td></tr>
                            var blist = $('<table><thead><tr>'
                                            + '<th></th>'
                                            + '<th>Box Id</th>'
                                            + '<th>Delivery Status</th>'
                                            + '<th>Courier Status</th>'
                                            + '<th>Warehouse Status</th>'
                                            + '</tr>'
                                            + '</thead>'
                                            + '<tbody></tbody></table>');

                            var box_tab_list = val.box_list;

                            shipment_tab.append('<tr><td><input type="checkbox" class="box_select_order" name="ship" value="'+ val.fulfillment_code +'" ></td><td>' + val.order_id + '</td><td>' + val.fulfillment_code + '</td><td>' + val.consignee_olshop_city + '</td><td>' + val.number_of_package + '</td></tr>');

                            shipment_tab.append('<tr><td>&nbsp;</td><td colspan="6">'+ val.consignee_olshop_name +'</td></tr>');

                            shipment_tab.append(
                                '<tr><td>&nbsp;</td><td>Box ID</td><td>Delivery Status</td><td>Courier Status</td><td>Warehouse Status</td></tr>'
                                );

                            $.each(box_tab_list, function(i, v) {
                                shipment_tab.append(
                                    '<tr><td>&nbsp;&nbsp;</td><td><input type="checkbox" name="box_' + v._id + '_' + v.fulfillment_code + '" class="boxselect '+ v.fulfillment_code +'" value="' + v._id + '" >&nbsp;&nbsp;<b>' + v.box_id + '</b></td><td>' + v.deliveryStatus + '</td><td>' + v.courierStatus + '</td><td>' + v.warehouseStatus + '</td></tr>'
                                    );
                            });


                        });

                    }else{

                    }
                }
                ,'json');

            //console.log(date);

        });

        $('#do-change-status').on('click',function(){

            var boxes = $('.boxselect:checked');

            var reason = $('#re-status-reason').val();

            var delivery_status = $('#delivery_status').val();

            var courier_status = $('#courier_status').val();

            var warehouse_status = $('#warehouse_status').val();

            var position = $('#chg_position').val();

            //var props = $('.selector:checked');
            var ids = [];
            $.each(boxes, function(index){
                ids.push( $(this).val() );
            });

            if(ids.length > 0){
                $.post('{{ URL::to('ajax/changestatus')}}',
                    {
                        box_ids : ids,
                        delivery_status : delivery_status,
                        courier_status : courier_status,
                        warehouse_status : warehouse_status,
                        position : position,
                        reason : reason
                    },
                    function(data){
                        $('#change-status-modal').modal('hide');
                        oTable.draw();
                    }
                    ,'json');

            }else{
                alert('No product selected.');
                $('#change-status-modal').modal('hide');
            }

        });

        $('#box_select_all').on('click',function(){
            if($('#box_select_all').is(':checked')){
                $('.boxselect').prop('checked', true);
            }else{
                $('.boxselect').prop('checked',false);
            }
        });

        $('#box_select_all').on('ifChecked',function(){
            $('.boxselect').prop('checked', true);
        });

        $('#box_select_all').on('ifUnchecked',function(){
            $('.boxselect').prop('checked', false);
        });


        $('#order_shipment_list').on('click',function(e){
            if($(e.target).is('.box_select_order')) {
                var ff = e.target.value;
                console.log(ff);
                if($(e.target).is(':checked')){
                    $('.'+ff).prop('checked', true);
                }else{
                    $('.'+ff).prop('checked',false);
                }
            }
        });

        $('#box_select_order').on('click',function(){
        });

        function getSelectedChg(){
            var props = $('.selector:checked');
            var ids = [];
            $.each(props, function(index){
                ids.push( $(this).val() );
            });

            return ids;
        }

        function getSelectedBox(){
            var props = $('.boxselect:checked');
            var ids = [];
            $.each(props, function(index){
                ids.push( $(this).val() );
            });

            return ids;
        }

    });


</script>
