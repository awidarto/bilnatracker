            if ($(e.target).is('.upload')) {
                var _id = e.target.id;
                var _rel = $(e.target).attr('rel');
                var _status = $(e.target).data('status');

                $('#loading-pictures').show();

                $.post('{{ URL::to('ajax/documentinfo') }}', { doc_id: _id },
                    function(data){

                        $('#loading-pictures').hide();

                        if(data.result == 'OK:FOUND'){
                            var defaultpic = data.data.defaultpic;

                            var brchead = data.data.brchead;
                            var brc1 = data.data.brc1;
                            var brc2 = data.data.brc2;
                            var brc3 = data.data.brc3;

                            console.log(brchead);

                            if(data.data.files){

                                $.each(data.data.files, function (index, file) {
                                    console.log(file);

                                    var isdefault = (defaultpic == file.file_id)?'checked':'';
                                    var isbrchead = (brchead == file.file_id)?'checked':'';
                                    var isbrc1 = (brc1 == file.file_id)?'checked':'';
                                    var isbrc2 = (brc2 == file.file_id)?'checked':'';
                                    var isbrc3 = (brc3 == file.file_id)?'checked':'';

                                    {{ View::make('fupload.jsajdetail') }}

                                    $(thumb).appendTo('#pictureupload_files ul');

                                    var upl = '<li id="fdel_' + file.file_id +'" ><input type="hidden" name="delete_type[]" value="' + file.delete_type + '">';
                                    upl += '<input type="hidden" name="delete_url[]" value="' + file.delete_url + '">';
                                    upl += '<input type="hidden" name="filename[]" value="' + file.filename  + '">';
                                    upl += '<input type="hidden" name="filesize[]" value="' + file.filesize  + '">';
                                    upl += '<input type="hidden" name="temp_dir[]" value="' + file.temp_dir  + '">';
                                    upl += '<input type="hidden" name="thumbnail_url[]" value="' + file.thumbnail_url + '">';
                                    upl += '<input type="hidden" name="large_url[]" value="' + file.large_url + '">';
                                    upl += '<input type="hidden" name="medium_url[]" value="' + file.medium_url + '">';
                                    upl += '<input type="hidden" name="full_url[]" value="' + file.full_url + '">';
                                    upl += '<input type="hidden" name="filetype[]" value="' + file.filetype + '">';
                                    upl += '<input type="hidden" name="fileurl[]" value="' + file.fileurl + '">';
                                    upl += '<input type="hidden" name="file_id[]" value="' + file.file_id + '"></li>';

                                    $(upl).appendTo('#pictureupload_uploadedform ul');

                                });



                            }

                        }

                    },'json');

                $('#upload-modal').modal();

                $('#upload-id').val(_id);

                $('#upload-title-id').html('Doc ID : ' + _rel);

            }
