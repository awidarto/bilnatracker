            if ($(e.target).is('.senddevice')) {
                var _id = e.target.id;

                $('#parse-device-id').val(_id);

                $('#device-name').val("");
                $('#device-key').val("");

                $('#push-device-modal').modal();

            }
