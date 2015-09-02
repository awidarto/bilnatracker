var thumb = '<table class="table" id="par_'+ file.file_id +'" >' +
    '    <tr>' +
    '        <td rowspan="6"><img style="width:125px;" src="' + file.thumbnail_url + '"><br />' +
    '            <span class="file_copy" data-clipboard-text="'+ file.url +'"><i class="icon-copy"></i> copy URL</span>' +
    '    </td>' +
    '        <td><span class="img-title">' + file.filename + '</span></td>' +
    '        <td><span class="file_del icon-trash" id="' + file.file_id +'"></td>' +
    '    </tr>' +
    '    <tr>' +
    '        <td colspan="2">' +
    '            <label for="defaultpic"><input type="radio" name="defaultpic" ' + isdefault + ' value="' + file.file_id + '" > Cover</label>' +
    '        </td>' +
    '    </tr>' +
    '    <tr>' +
    '        <td colspan="2">' +
    '            <input type="radio" name="brchead" ' + isbrchead + ' value="' + file.file_id + '" > Main' +
    '        </td>' +
    '    </tr>' +
    '    <tr>' +
    '        <td style="text-align:right;">Caption</td>' +
    '        <td colspan="2">' +
    '            <input type="text" name="caption[]" />' +
    '        </td>' +
    '    </tr>' +
'</table>';