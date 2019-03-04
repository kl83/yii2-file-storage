var cropImage;

(function ($) {
    'use strict';

    var $cropPopup;
    var $cropImage;
    var cropper;
    var _cb;
    var _data;
    var _allowHide;

    function _cropImage(img, options) {
        $cropImage.attr('src', img);
        $cropPopup.modal('show');
        setTimeout(function () {
            cropper = new Cropper($cropImage.get(0), options);
        }, 200);
    }

    cropImage = function (img, options, cb) {
        _data = undefined;
        _cb = cb;
        _allowHide = false;
        if (!$cropPopup) {
            $.get('/' + kl83FileStorageOptions.moduleId + '/crop/popup', function (data) {
                $('body').append(data);
                $cropPopup = $('#cropper-cmp');
                $cropImage = $cropPopup.find('img');
                _cropImage(img, options);
            });
        } else {
            _cropImage(img, options);
        }
    };

    $(document).on('hidden.bs.modal', '#cropper-cmp', function () {
        cropper.destroy();
        _cb(_data);
    });

    $(document).on('click', '#cropper-cmp .apply', function () {
        _allowHide = true;
        _data = cropper.getData(true);
        $cropPopup.modal('hide');
    });

    $(document).on('click', '#cropper-cmp .cancel', function () {
        _allowHide = true;
        $cropPopup.modal('hide');
    });

    $(document).on('hide.bs.modal', '#cropper-cmp', function (e) {
        if (!_allowHide) {
            return false;
        }
    });

})(jQuery);
