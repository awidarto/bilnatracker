<!--
    available vars :
        filename
        thumbnail_url
        full_url
        file_id
-->
<table class="table" id="par_{{ $filedata['_id'] }}" >
    <tr>
        <td rowspan="6">
            <img style="width:100px;" src="{{ $filedata['thumbnail_url'] }}"><br />
            <span class="file_copy" data-clipboard-text="{{ $filedata['url'] }}><i class="icon-copy"></i> copy URL</span>
        </td>
        <td><span class="img-title">{{ $filedata['name'] }}</span></td>
        <td><span class="file_del" ><i class="file_del fa fa-trash-o" id="{{ $filedata['_id'] }}"></i></span></td>
    </tr>
</table>
