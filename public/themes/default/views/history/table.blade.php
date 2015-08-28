@extends('layout.fixedstatic')

@section('left')
<style type="text/css">
    ul.gallery{
        list-style:none;
        padding-left: 0px !important;
    }
    ul.gallery li{
        float: left;
        display: block;
        margin-right: 4px;
        cursor:pointer;
    }
</style>
<dl>
    <dt>Description</dt>
    <dd>{{ $a->itemDescription }}</dd>
    <dt>Merchant</dt>
    <dd>{{ $a->merchantName }}</dd>
    <dt>Status</dt>
    <dd>{{ $a->status }}</dd>
    <dt>Tags</dt>
    <dd>{{ $a->tags }}</dd>
</dl>
@if(isset($a->files))
<ul class="gallery">
    @foreach($a->files as $k=>$f)
        <li>
            <img  class="thumbnail" data-href="{{ $f['full_url'] }}" src="{{ $f['thumbnail_url'] }}" alt="">
        </li>
    @endforeach
</ul>
@endif
@stop

@section('right')

{{ $table }}

@stop

@section('modals')

<div id="status-modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalStatus" aria-hidden="true">
  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
    <h5 id="myModalStatus">Approval</span></h5>
  </div>
  <div class="modal-body" >
        {{ Former::hidden('approvalId','')->id('approvalIdForm')}}
        {{ Former::select('approvalStatus', 'Approval Status')->options(array('pending'=>'Pending','verified'=>'Verified'))->id('approval-status')}}
  </div>
  <div class="modal-footer">
    <button class="btn" data-dismiss="modal" aria-hidden="true">Cancel</button>
    <button class="btn btn-primary" id="do-approve">Assign</button>
  </div>
</div>

<div id="blueimp-gallery" class="blueimp-gallery blueimp-gallery-controls">
    <div class="slides"></div>
    <h3 class="title"></h3>
    <a class="prev">‹</a>
    <a class="next">›</a>
    <a class="close">×</a>
    <a class="play-pause"></a>
    <ol class="indicator"></ol>
</div>

@stop

@section('aux')
<script type="text/javascript">
    $(document).ready(function(){
        $('#refresh_filter').on('click',function(){
            oTable.draw();
        });

        $('.change-approval').on('click',function(e){
            console.log(this);

            $('#approvalIdForm').val( $(this).data('id') );

            $('#status-modal').modal();
            e.preventDefault();
        });

        $('#do-approve').on('click',function(){

            $.post('{{ URL::to('ajax/approval')}}',
                {
                    approval_status : $('#approval-status').val(),
                    ticket_id : $('#approvalIdForm').val()
                },
                function(data){
                    if(data.result == 'OK'){
                        var ticket_id = $('#approvalIdForm').val();
                        var approval_status = $('#approval-status').val();
                        if(approval_status == 'verified'){
                            var btn = $('.btn.' + ticket_id);
                            console.log(btn);

                            btn.removeClass('btn-info').addClass('btn-success').html(approval_status);
                        }
                        $('#status-modal').modal('hide');
                    }else{
                        alert('Ticket not found');
                        $('#status-modal').modal('hide');
                    }
                }
                ,'json');

        });

        $('#do-status').on('click',function(){
            var props = $('.selector:checked');
            var ids = [];
            $.each(props, function(index){
                ids.push( $(this).val() );
            });

            console.log(ids);

            if(ids.length > 0){
                $.post('{{ URL::to('ajax/assignstatus')}}',
                    {
                        status : $('#assigned-status').val(),
                        product_ids : ids
                    },
                    function(data){
                        $('#status-modal').modal('hide');
                        oTable.draw();
                    }
                    ,'json');

            }else{
                alert('No product selected.');
                $('#status-modal').modal('hide');
            }

        });

        $('.thumbnail').on('click',function(){
            var links = [];

            var g = $('.thumbnail');

            g.each(function(){
                links.push({
                    href:$(this).data('href'),
                    title:''
                });
            })
            var options = {
                carousel: false
            };

            blueimp.Gallery(links, options);
        });


    });
</script>
@stop