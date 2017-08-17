var kl83RegisterPicSetWidget = function(elId, params){
    
    var rootEl = $('#'+elId);
    var form = rootEl.closest('form');
    var fileInput = rootEl.find('input[type="file"]');
    var fileSetInput = rootEl.find('input[type="hidden"]');
    var fileInputName = fileInput.attr('name');
    var sortable = rootEl.find('.sortable');
    
    jQuery(function($){
        
        /**
         * Check limit loaded images.
         * Returns false if limit is reached, true if not
         * @returns {Boolean}
         */
        var limitReached = function(){
            return params.maxImages !== false && rootEl.find('.items div.item').length >= params.maxImages;
        };
        
        var checkLimit = function(){
            if ( limitReached() ) {
                rootEl.find('.items .new-item').fadeOut();
            } else {
                rootEl.find('.items .new-item').fadeIn();
            }
        };
        
        // Enable sorting
        rootEl.find('.sortable').sortable({
            stop: function ( e, ui ) {
                var afterId = ui.item.index() ? ui.item.prev().data('id') : 0;
                $.get(params.moveUrl, { id: ui.item.data('id'), afterId: afterId });
            }
        });
        
        // Upload selected file
        fileInput.change(function(){
            form.ajaxSubmit({
                url: params.uploadUrl+'?fileSetId='+fileSetInput.val()+'&attributes='+fileInputName,
                type: 'post',
                success: function(data){
                    if ( fileSetInput.val() === '0' ) {
                        fileSetInput.val(data.fileSetId);
                    }
                    sortable.append(data.html[fileInputName]);
                    checkLimit();
                    setTimeout(function(){
                        rootEl.find('.item.animation').removeClass('animation');
                    }, limitReached() ? 400 : 50);
                    fileInput.val('');
                }
            });
        });

        // Remove file
        $(document).on('click', '#'+elId+' .remove-item', function(){
            var item = $(this).closest('.item');
            $.get(params.removeUrl, { id: item.data('id') }, function(){
                item.addClass('animation');
                setTimeout(function(){
                    item.remove();
                    checkLimit();
                }, 400);
            });
        });
    });
};
