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
                <input type="text" class="col-md-10" id="{{ $o->_id }}" name="addQty[]" value="" />
            </td>
            <td>
                <input type="text" class="col-md-10" id="{{ $o->_id }}" name="adjustQty[]" value="" />
            </td>
        </tr>
    @endforeach
</table>
