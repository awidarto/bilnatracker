@extends('layout.front')


@section('content')

<h3>{{$title}}</h3>

{{Former::open_for_files($submit,'POST',array('class'=>'custom'))}}

{{ Former::hidden('id')->value($formdata['_id']) }}
<div class="row-fluid">
    <div class="col-md-6">

        {{ Former::text('SKU','SKU') }}
        {{ Former::select('category','Category')->options(Prefs::ExtractProductCategory()) }}
        {{ Former::text('series','Series') }}
        {{ Former::text('itemDescription','Description') }}
        {{ Former::text('itemGroup','Item Group')->help('for compound product only') }}
        {{ Former::text('priceRegular','Regular Price')->class('col-md-4') }}
        {{ Former::text('material','Material') }}
        {{ Former::text('colour','Colour')->class('col-md-4') }}
        {{--

        <div class="row-fluid form-vertical">
            <div class="col-md-4">
                {{ Former::text('colour','Colour')->class('col-md-12') }}
            </div>
            <div class="col-md-4">
                {{ Former::text('colourHex','')->class('pick-a-color') }}
            </div>
        </div>
        --}}

        <div class="row-fluid form-vertical">
            <div class="col-md-4">
                {{ Former::text('W','Width')->class('col-md-12')}}
            </div>
            <div class="col-md-4">
                {{ Former::text('H','Height')->class('col-md-12') }}
            </div>
            <div class="col-md-4">
                {{ Former::text('L','Length')->class('col-md-12') }}
            </div>
        </div>
        <div class="row-fluid form-vertical">
            <div class="col-md-4">
                {{ Former::text('D','Diameter')->class('col-md-12')}}
            </div>
            <div class="col-md-4">
                {{ Former::text('sizeDescription','Dimension Description') }}
            </div>
        </div>

        {{ Former::text('tags','Tags')->class('tag_keyword') }}

    </div>
    <div class="col-md-6">
        <div class="row-fluid form-vertical">
            <div class="col-md-2" style="text-align:right;width:120px;">
                Inventory
            </div>
            <div class="col-md-9" style="padding-left:10px;">
                <table class="table " >
                    <tr>
                        <th>
                            Outlet
                        </th>
                        <th>
                            Sold
                        </th>
                        <th>
                            Reserved
                        </th>
                        <th>
                            Avail.
                        </th>
                        <th>
                            Add Qty.
                        </th>
                        <th>
                            <span style="color:red;">(Adjust Qty.)</span>
                        </th>
                    </tr>
                    @foreach( Prefs::getOutlet()->OutletToArray() as $o)
                        <tr>
                            <td>
                                {{ $o->name }}
                            </td>
                            <td>
                                {{ $formdata['stocks'][$o->_id]['sold'] }}
                            </td>
                            <td>
                                {{ $formdata['stocks'][$o->_id]['reserved'] }}
                            </td>
                            <td>
                                {{ $formdata['stocks'][$o->_id]['available'] }}
                            </td>
                            <td>
                                <input type="hidden" name="outlets[]"  value="{{ $o->_id }}">
                                <input type="hidden" name="outletNames[]"  value="{{ $o->name }}">
                                <input type="text" class="col-md-6" id="{{ $o->_id }}" name="addQty[]" value="" />
                            </td>
                            <td>
                                <input type="text" class="col-md-6" id="{{ $o->_id }}" name="adjustQty[]" value="" />
                            </td>
                        </tr>
                    @endforeach
                </table>
            </div>

        </div>

        <?php
            $fupload = new Fupload();
        ?>

        {{ $fupload->id('imageupload')->title('Select Images')->label('Upload Images')->make($formdata) }}

    </div>
</div>

<div class="row-fluid right">
    <div class="col-md-12">
        {{ Form::submit('Save',array('class'=>'btn btn-primary'))}}&nbsp;&nbsp;
        {{ HTML::link($back,'Cancel',array('class'=>'btn'))}}
    </div>
</div>
{{Former::close()}}

{{ HTML::script('js/wysihtml5-0.3.0.min.js') }}
{{ HTML::script('js/parser_rules/advanced.js') }}

<script type="text/javascript">


$(document).ready(function() {

    $('.pick-a-color').pickAColor({
        showHexInput:false
    });

    $('#name').keyup(function(){
        var title = $('#name').val();
        var slug = string_to_slug(title);
        $('#permalink').val(slug);
    });


});

</script>

@stop