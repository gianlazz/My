require.config({
    urlArgs: 'noCache=' + Math.round((new Date()).getTime() / (1000 * 120)),
    baseUrl: '/assets/js/'
});

define(['jquery', 'studentrnd/log'], function(jQuery){
    return new (function(){
        this.constructor = function()
        {
            jQuery('.avatar-picker').live('click', function(){
                var _image = $(this).children('img');
                var _field = $(this).children('input');
                filepicker.getFile("image/*", {'modal': true},
                    function(url, metadata){
                        url = url + "/convert?fit=crop&h=256&w=256"
                        _image.attr('src', url);
                        _field.val(url);
                    }
                );
            })


            jQuery('.image-picker').live('click', function(){
                var _field = $(this);
                filepicker.getFile("image/*", {'modal': true},
                    function(url, metadata){
                        url = url + "/convert?fit=crop&h=279&w=418"
                        _field.val(url);
                    }
                );
            })

            jQuery('.rfid-scan').each(function(){
                var _scan = jQuery(this);
                require(['studentrnd/drivers/rfid'], function(Rfid) {
                    if (Rfid.IsAvailable) {
                        _scan.css('display', 'block');

                        var rfidForm = _scan.children('form');
                        var rfidInput = rfidForm.children('input[name="rfID"]');

                        Rfid.OnSwipe = function(token)
                        {
                            rfidInput.val(token);
                            rfidForm.submit();
                        }
                    }
                })
            })
        }
        this.constructor();
    })();
});
