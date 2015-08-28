<div class="form-horizontal">
    {{ Former::select('outlet')->options(Prefs::getOutlet()->OutletToSelection('id','name') )->id('scanoutlet') }} &nbsp;<span>select one of existing outlet before scanning</span><br />
    {{ Former::checkbox('')->id('outlet-active')->text('Only search in selected outlet') }}<br />
    {{ Former::text('barcode','')->id('barcode')->class('col-md-6')->autocomplete('off')->placeholder('Always make sure to put cursor in this box when scanning') }}

    <div id="scanResult">
        Hello !
    </div>
</div>


<script type="text/javascript">


$(document).ready(function() {

    $('#barcode').focus();

    $('#barcode').on('keyup',function(ev){
        if(ev.keyCode == '13'){
            onScanResult();
        }
        //$('#barcode').val($('#barcode').val() + event.keyCode);
    });

    $('#barcode').on('focus',function(ev){
        $('#barcode').val('');
        //$('#barcode').val($('#barcode').val() + event.keyCode);
    });

    function onScanResult(){
        var txtin = $('#barcode').val();
        var outlet_id = $('#scanoutlet').val();

        if( (outlet_id == '' || txtin == '') && $('#outlet-active').is(':checked')){
            alert('Select outlet before scanning');
            $('#barcode').focus();
            $('#barcode').val('');
        }else{

            var search_outlet = ($('#outlet-active').is(':checked'))?1:0;

            $.post('{{ URL::to('ajax/scancheck') }}',
                {
                    'txtin':txtin,
                    'outlet_id': outlet_id,
                    'search_outlet': search_outlet
                },
                function(data){
                    if(data.result == 'OK'){
                        oTable.fnDraw();
                    }
                    $('#scanResult').html(data.msg);
                    $('#barcode').val('');
                    $('#barcode').focus();
                },'json'
            );
        }

    }

    function clearList(){
        $('#barcode').val('').focus();
    }

});

</script>
