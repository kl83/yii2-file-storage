(function ($) {
    'use strict';

    $.fn.picWidget = function (method) {
        if (typeof method === 'string') {
            return methods[method]
                .apply(this, Array.prototype.slice.apply(arguments, [1]));
        } else {
            return methods.init.apply(this, arguments);
        }
    };

    let methods = {};

    methods.init = function () {
    };

    methods.delete = function (silence) {
        let $input = this.find('input[type="hidden"]');
        let id = $input.val();
        let url = '/' + kl83FileStorageOptions.moduleId + '/default/delete';
        if (id > 0) {
            $.get(url, {id: id});
        }
        $input.val('');
        if (!silence) {
            this.removeClass('show-picture');
            setTimeout(function(){
                this.find('.picture').css('backgroundImage', 'none');
            }, 200);
        }
    };

    methods.upload = function () {
        let $widget = this;
        let $fileInput = this.find('input[type="file"]');
        let $hiddenInput = this.find('input[type="hidden"]');
        let $img = this.find('.picture');
        let fileInputName = $fileInput.attr('name');
        let url = '/' + kl83FileStorageOptions.moduleId + '/default/upload' +
            '?attributes=' + fileInputName;
        this.picWidget('delete', true);
        this.closest('form').ajaxSubmit({
            url: url,
            type: 'post',
            success: function (data) {
                $hiddenInput.val(data.files[fileInputName][0].id);
                $fileInput.val('');
                $widget.removeClass('dd-action');
                if ($widget.hasClass('show-picture')) {
                    $widget.addClass('change-picture');
                    setTimeout(function () {
                        $img.css(
                            'backgroundImage',
                            "url('" + data.files[fileInputName][0].url + "')"
                        );
                        $widget.removeClass('change-picture');
                    }, 200);
                } else {
                    $img.css(
                        'backgroundImage',
                        "url('" + data.files[fileInputName][0].url + "')"
                    );
                    $widget.addClass('show-picture');
                }
            }
        });
    };

    $(document).on('change', '.kl83-pic-widget input[type="file"]', function () {
        let $widget = $(this).closest('.kl83-pic-widget');
        $widget.picWidget('upload');
    });

    $(document).on('click', '.kl83-pic-widget .remove', function () {
        let $widget = $(this).closest('.kl83-pic-widget');
        $widget.picWidget('delete');
    });

    $(document).on('dragenter dragover', '.kl83-pic-widget', function (e) {
        e.preventDefault();
        e.stopPropagation();
        $(this).addClass('dd-action');
    });

    $(document).on('dragleave', '.kl83-pic-widget', function (e) {
        if (!$(e.relatedTarget).closest('.kl83-pic-widget').length) {
            $(this).removeClass('dd-action');
        }
    });

    $(document).on('drop', '.kl83-pic-widget', function (e) {
        e.preventDefault();
        e.stopPropagation();
        $(this).find('input[type="file"]').get(0).files =
            e.originalEvent.dataTransfer.files;
    });

})(jQuery);
