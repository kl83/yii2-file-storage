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

    function reloadThumbnail() {
        var img = this.find('label.picture');
        var thumb = img.css('backgroundImage')
            .replace(/(\?\d+|)(['"])\)/, '?' + (new Date()).getTime() + '$2)');
        img.css('backgroundImage', thumb)
    }

    function rotate(direction) {
        var $this = this;
        var id = this.find('input[type="hidden"]').val();
        var url = '/' + kl83FileStorageOptions.moduleId + '/rotate/' + direction;
        if (id > 0) {
            $.get(url, {id: id}, function () {
                reloadThumbnail.apply($this);
            });
        }
    }

    var methods = {};

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
            var $this = this;
            this.removeClass('show-picture');
            setTimeout(function(){
                $this.find('.picture').css('backgroundImage', 'none');
            }, 200);
            this.trigger('pic-widget:change');
        }
    };

    methods.rotateLeft = function () {
        rotate.apply(this, ['left']);
    };

    methods.rotateRight = function () {
        rotate.apply(this, ['right']);
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
                            "url('" + data.files[fileInputName][0].thumbUrl + "')"
                        );
                        $widget.removeClass('change-picture');
                    }, 200);
                } else {
                    $img.css(
                        'backgroundImage',
                        "url('" + data.files[fileInputName][0].thumbUrl + "')"
                    );
                    $widget.addClass('show-picture');
                }
                $widget.trigger('pic-widget:change');
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

    $(document).on('click', '.kl83-pic-widget .rotate-left', function () {
        $(this).closest('.kl83-pic-widget').picWidget('rotateLeft');
    });

    $(document).on('click', '.kl83-pic-widget .rotate-right', function () {
        $(this).closest('.kl83-pic-widget').picWidget('rotateRight');
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
