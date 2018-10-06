(function ($) {
    'use strict';

    $.fn.picsetWidget = function (method) {
        if (typeof method === 'string') {
            return methods[method]
                .apply(this, Array.prototype.slice.apply(arguments, [1]));
        } else {
            return methods.init.apply(this, arguments);
        }
    };

    let methods = {};

    methods.init = function (options) {
        this.data('picset', options);
        this.find('.sortable').sortable({
            stop: sortableStop
        });
        checkLimit.apply(this);
    };

    methods.upload = function () {
        let $widget = this;
        let $filesetInput = this.find('input[type="hidden"]');
        let $fileInput = this.find('input[type="file"]');
        let $items = this.find('.sortable');
        let fileInputName = $fileInput.attr('name').replace(/\[\]$/, '');
        this.addClass('progress-enabled');
        this.closest('form').ajaxSubmit({
            url: '/' + kl83FileStorageOptions.moduleId + '/default/upload' +
                '?filesetId=' + ($filesetInput.val() || -1) +
                '&attributes=' + fileInputName,
            type: 'post',
            success: function (data) {
                $filesetInput.val(data.fileset);
                for (let i in data.files[fileInputName]) {
                    let item = $(Mustache.render(
                        picsetItemTemplate,
                        data.files[fileInputName][i]
                    ));
                    item.addClass('animation');
                    $items.append(item);
                }
                checkLimit.apply($widget);
                setTimeout(function () {
                    $widget.find('.item.animation').removeClass('animation');
                }, isLimitReached.apply($widget) ? 400 : 50);
                $fileInput.val('');
                $widget.removeClass('progress-enabled');
                $widget.find('.progress-bar').width(0);
            },
            uploadProgress: function (e, position, total, percent) {
                $widget.find('.progress-bar').width(percent + '%');
            }
        });
    };

    methods.deleteItem = function (id) {
        let $widget = this;
        let item = this.find('.item[data-id="' + id + '"]');
        $.get(
            '/' + kl83FileStorageOptions.moduleId + '/default/delete',
            {id: id},
            function () {
                item.addClass('animation');
                setTimeout(function () {
                    item.remove();
                    checkLimit.apply($widget);
                }, 400);
            }
        );
    };

    function checkLimit() {
        if (isLimitReached.apply(this)) {
            this.find('.items .new-item').fadeOut();
        } else {
            this.find('.items .new-item')
                .css('display', 'flex')
                .hide()
                .fadeIn();
        }
    }

    function isLimitReached() {
        let options = this.data('picset');
        return options.maxImages !== false &&
            this.find('.items div.item').length >= options.maxImages;
    }

    function sortableStop(e, ui) {
        $.get('/' + kl83FileStorageOptions.moduleId + '/default/move', {
            id: ui.item.data('id'),
            afterId: ui.item.index() ? ui.item.prev().data('id') : 0
        });
    }

    $(document).on('click', '.kl83-picset-widget .remove-item', function () {
        let $widget = $(this).closest('.kl83-picset-widget');
        let item = $(this).closest('.item');
        $widget.picsetWidget('deleteItem', item.data('id'));
    });

    $(document).on('change', '.kl83-picset-widget input[type="file"]', function () {
        let $widget = $(this).closest('.kl83-picset-widget');
        $widget.picsetWidget('upload');
    });

    $(document).on('dragenter dragover', '.kl83-picset-widget', function (e) {
        e.preventDefault();
        e.stopPropagation();
        $(this).addClass('dd-action');
    });

    $(document).on('dragleave', '.kl83-picset-widget', function (e) {
        if (!$(e.relatedTarget).closest('.kl83-picset-widget').length) {
            $(this).removeClass('dd-action');
        }
    });

    $(document).on('drop', '.kl83-picset-widget', function (e) {
        e.preventDefault();
        e.stopPropagation();
        $(this).find('input[type="file"]').get(0).files =
            e.originalEvent.dataTransfer.files;
    });

})(jQuery);
