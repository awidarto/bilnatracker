            if ($(e.target).is('.order-detail')) {
                var target = $(e.target);
                var order_id = target.data('order');
                var ff_id = target.data('ff');

                $('#order-id').val(order_id);
                $('#order-ff').val(ff_id);

                console.log(order_id + ' ' + ff_id);

                $('#last-order-status').html('Loading data...');

                $.post('{{ URL::to('cs/last')}}',
                {
                    orderId : order_id,
                    orderFf : ff_id
                },
                function(data){
                    $('#last-order-status').html(data);
                    oTableTwo.draw();
                }
                ,'html');



                //oTableTwo.draw();
                return false;
            }
