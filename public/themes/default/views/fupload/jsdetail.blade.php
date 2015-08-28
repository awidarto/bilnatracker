var thumb = '<table class="table" id="par_'+ file.file_id +'" >' +
    '    <tr>' +
    '        <td rowspan="6"><img style="width:50px;" src="' + file.thumbnail_url + '"><br />' +
    '            <span class="file_copy" data-clipboard-text="'+ file.url +'"><i class="icon-copy"></i> copy URL</span>' +
    '        </td>' +
    '        <td><span class="img-title">' + file.name + '</span></td>' +
    '        <td><span class="file_del icon-trash" id="' + file.file_id +'"></td>' +
    '    </tr>' +
'</table>';