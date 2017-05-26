// Change php.ini settings to support large uploads
// upload_max_filesize = 1000M
// post_max_size = 1000M

Dropzone = require('./lbd/dropzone.min');

Dropzone.options.realDropzone = {
    // Set the max file upload size
    maxFilesize: 1000,

    // Handle upload errors
    error: function(file, response) {
        if($.type(response) === "string")
            var message = response; //dropzone sends it's own error messages in string
        else
            var message = response.message;
        file.previewElement.classList.add("dz-error");
        _ref = file.previewElement.querySelectorAll("[data-dz-errormessage]");
        _results = [];
        for (_i = 0, _len = _ref.length; _i < _len; _i++) {
            node = _ref[_i];
            _results.push(node.textContent = message);
        }
        $.notify({
            icon: "pe-7s-bell",
            message: "<b>Sorry, there was a problem uploading your file....</b>"

        },{
            type: 'danger',
            timer: 4000,
            placement: {
                from: 'top',
                align: 'right'
            }
        });
        return _results;
    },
    // Handle upload success
    success: function(file,done) {
        $.notify({
            icon: "pe-7s-bell",
            message: "<b>File Uploaded Succesfully!</b>"

        },{
            type: 'success',
            timer: 4000,
            placement: {
                from: 'top',
                align: 'right'
            }
        });
    }
}