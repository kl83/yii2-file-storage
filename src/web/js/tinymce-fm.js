var mceUpload;
(function ($) {
    /**
     * TinyMCE target input for url
     */
    var fld;

    /**
     * Create CSRF-token input
     */
    function getCsrfInputInput() {
        var param = $('meta[name="csrf-param"]').attr('content');
        var token = $('meta[name="csrf-token"]').attr('content');
        return $('<input type="text" name="' + param + '" value="' + token + '">');
    }

    /**
     * Create form
     */
    var form = $('<form action="/filestorage/default/upload?attributes=picture" method="post" enctype="multipart/form-data" style="display:none">');
    var input = $('<input type="file" name="picture">');
    form.append(getCsrfInputInput());
    form.append(input);

    /**
     * The file is selected
     */
    input.bind('change', function () {
        form.ajaxSubmit(function (data) {
            fld.val(data.files.picture[0].url);
        });
    });

    mceUpload = function (fieldId, url, type) {
        if (type === 'image') {
            input.attr('accept', 'image/*');
        } else {
            input.removeAttr('accept');
        }
        input.trigger('click');
        fld = $('#' + fieldId);
    };

    /**
     * Append form to DOM
     */
    $(function () {
        $('body').append(form);
    });
})(jQuery);
