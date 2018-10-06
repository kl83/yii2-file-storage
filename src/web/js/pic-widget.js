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

    methods.delete = function () {
        let $input = this.find('input[type="hidden"]');
        let id = $input.val();
        if (id > 0) {
            $.get('/' + kl83FileStorageOptions.moduleId + '/default/delete', {id: id});
        }
        $input.val('');
        this.removeClass('show-picture');
        this.find('.picture').css('backgroundImage', 'none');
    };

    methods.upload = function () {
        let $widget = this;
        let $fileInput = this.find('input[type="file"]');
        let fileInputName = $fileInput.attr('name');
        let $hiddenInput = this.find('input[type="hidden"]');
        let $img = this.find('.picture');
        this.picWidget('delete');
        this.closest('form').ajaxSubmit({
            url: '/' + kl83FileStorageOptions.moduleId + '/default/upload?attributes=' + fileInputName,
            type: 'post',
            success: function (data) {
                $hiddenInput.val(data.files[fileInputName][0].id);
                $img.css('backgroundImage', "url('" + data.files[fileInputName][0].url) + "')";
                $widget.addClass('show-picture');
                $fileInput.val('');
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

})(jQuery);
