var cropImage;

(function ($) {
    'use strict';

    var $cropPopup;
    var $cropImage;
    var cropper;
    var _cb;

    cropImage = function (img, options, cb) {
        if (!$cropPopup) {
            $cropPopup = $('<div id="cropper-cmp">' +
                '<div class="img-wrapper"><img src="" alt=""></div>' +
                '<span class="apply glyphicon glyphicon-ok"></span>' +
                '<span class="cancel glyphicon glyphicon-remove"></span>' +
                '</div>');
            $cropImage = $cropPopup.find('img');
            $('body').append($cropPopup);
        } else {
            $cropPopup.show();
        }
        $cropImage.attr('src', img);
        cropper = new Cropper($cropImage.get(0), options);
        _cb = cb;
    };

    $(document).on('click', '#cropper-cmp .apply', function () {
        var data = cropper.getData(true);
        $cropPopup.hide();
        cropper.destroy();
        _cb(data);
    });

    $(document).on('click', '#cropper-cmp .cancel', function () {
        $cropPopup.hide();
        cropper.destroy();
        _cb();
    });

})(jQuery);
