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
            stop: function (e, ui) {
                $.get('/' + kl83FileStorageOptions.moduleId + '/default/move', {
                    id: ui.item.data('id'),
                    afterId: ui.item.index() ? ui.item.prev().data('id') : 0
                });
            }
        });
    };

    methods.upload = function () {
        let $widget = this;
        let $filesetInput = this.find('input[type="hidden"]');
        let $fileInput = this.find('input[type="file"]');
        this.addClass('progress-enabled');
        this.closest('form').ajaxSubmit({
            url: '/' + kl83FileStorageOptions.moduleId + '/default/upload' +
                '?filesetId=' + ($filesetInput.val() || 0) +
                '&attributes=' + $fileInput.attr('name').replace(/\[\]$/, ''),
            type: 'post',
            success: function(data){
                if ( $filesetInput === '0' || ! /\d+/.test($filesetInput.val()) ) {
                    $filesetInput.val(data.fileSetId);
                }
                // sortable.append(data.html[fileInputName]);
                $widget.picsetWidget('checkLimit');
                setTimeout(function(){
                    $widget.find('.item.animation').removeClass('animation');
                }, $widget.picsetWidget('isLimitReached') ? 400 : 50);
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
                    $widget.picsetWidget('checkLimit');
                }, 400);
            }
        );
    };

    methods.checkLimit = function () {
        if (this.picsetWidget('isLimitReached')) {
            this.find('.items .new-item').fadeOut();
        } else {
            this.find('.items .new-item').fadeIn();
        }
    };

    methods.isLimitReached = function () {
        let options = this.data('picset');
        return options.maxImages !== false &&
            this.find('.items div.item').length >= options.maxImages;
    };

    $(document).on('click', '.kl83-picset-widget .remove-item', function () {
        let $widget = $(this).closest('.kl83-picset-widget');
        let item = $(this).closest('.item');
        $widget.picsetWidget('deleteItem', item.data('id'));
    });

    $(document).on('change', '.kl83-picset-widget input[type="file"]', function () {
        let $widget = $(this).closest('.kl83-picset-widget');
        $widget.picsetWidget('upload');
    });

})(jQuery);
