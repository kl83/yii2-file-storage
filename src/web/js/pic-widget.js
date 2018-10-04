var kl83RegisterPicWidget = function(elId, params){

    var rootEl = $('#'+elId);
    var form = rootEl.closest('form');
    var valueInput = rootEl.find('input[type="hidden"]');
    var fileInput = rootEl.find('input[type="file"]');
    var pictureEl = rootEl.find('.picture');
    var fileInputName = fileInput.attr('name');
    var removeEl = rootEl.find('.remove');

    jQuery(function($){

        var deletePicture = function(id, cb){
            $.get(params.removeUrl, { id: id }, cb);
        };

        // Upload selected file
        fileInput.change(function(){
            if ( rootEl.hasClass('show-picture') ) {
                deletePicture(valueInput.val());
            }
            form.ajaxSubmit({
                url: params.uploadUrl+(/\?/.test(params.uploadUrl)?'&':'?')+'attributes='+fileInputName,
                type: 'post',
                success: function(data){
                    valueInput.val(data[fileInputName].id).change();
                    pictureEl.css('backgroundImage', "url('"+data[fileInputName].url)+"')";
                    rootEl.addClass('show-picture');
                    fileInput.val('');
                }
            });
        });

        // Remove file
        removeEl.click(function(){
            deletePicture(valueInput.val(), function(){
                valueInput.val('0');
                rootEl.removeClass('show-picture');
                setTimeout(function(){
                    pictureEl.css('backgroundImage', "none");
                }, 400);
            });
        });
    });
};
