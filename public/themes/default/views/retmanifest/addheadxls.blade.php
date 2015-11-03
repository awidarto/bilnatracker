<table class="table table-bordered">
    <tr>
        <td colspan="2" style="font-weight:bold;font-size:18px;">RET-{{ str_pad($doc_number, 5, '0', STR_PAD_LEFT) }}</td>
    </tr>
    <tr>
        <td>MANIFEST DATE / TGL TERBIT</td>
        <td style="min-width:100px;">
            @if(is_null(Input::get('manifest-date')) || Input::get('manifest-date') == '')
                {{ date('d-m-Y', time() ) }}
            @else
                {{ date('d-m-Y', strtotime(Input::get('manifest-date')) ) }}
            @endif

        </td>
    </tr>
    <tr>
        <td>DELIVERY DATE</td>
        <td>
            @if(is_null(Input::get('date-from')) || Input::get('date-from') == '')

            @else
                {{ date('d-m-Y', strtotime(Input::get('date-from')) ) }}
            @endif
        </td>
    </tr>
    <tr>
        <td>DEVICE</td>
        <td>
            @if(is_null(Input::get('device')) || Input::get('device') == '')
                All
            @else
                {{  Prefs::getDevice('key',Input::get('device'))->identifier
                }}
            @endif
        </td>
    </tr>
    <tr>
        <td>COURIER</td>
        <td>
            @if(is_null(Input::get('courier')) || Input::get('courier') == '')
                All
            @else
                {{  Prefs::getCourier('_id',Input::get('courier'))->name
                }}
            @endif
        </td>
    </tr>
    <tr>
        <td>LOGISTIC</td>
        <td>
            @if(is_null(Input::get('logistic')) || Input::get('logistic') == '')
                All
            @else
                {{  Prefs::getLogistic('logistic_code',Input::get('logistic'))->name
                }}
            @endif
        </td>
    </tr>
</table>
<table class="table table-bordered" style="height:100%;min-height:75px;">
    <thead>
        <tr>
            <th>Dibuat Oleh</th>
            <th>Laporan</th>
            <th>Keuangan</th>
            <th>Staff Dispatch</th>
            <th>Staff Delivery</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td class="box" >&nbsp;</td>
            <td class="box" >&nbsp;</td>
            <td class="box" >&nbsp;</td>
            <td class="box" >&nbsp;</td>
            <td class="box" >&nbsp;</td>
        </tr>
        <tr>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
        </tr>
    </tbody>
</table>
<table class="table table-bordered">
    <thead>
        <tr>
            <th>REG</th>
            <th>PR</th>
            <th>PS</th>
            <th>Pending</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td class="box" ></td>
            <td class="box" ></td>
            <td class="box" ></td>
            <td class="box" ></td>
        </tr>
    </tbody>
</table>

