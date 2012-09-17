require.config({
    urlArgs: 'noCache=' + Math.round((new Date()).getTime() / (1000 * 120)),
    baseUrl: '/assets/js/'
});
define(['jquery', 'studentrnd/drivers/rfid', 'studentrnd/log'], function(jQuery, Rfid){
    return new (function(){
        this.constructor = function()
        {
            if (Rfid.IsAvailable) {
                var rfidForm = jQuery('form#rfid-login');
                var rfidInput = jQuery('form#rfid-login input#rfid-token');

                Rfid.OnSwipe = function(token)
                {
                    rfidInput.val(token);
                    rfidForm.submit();
                }

                jQuery('#rfid').css('display', 'block');
            } else {
                log('no');
            }
        }
        this.constructor();
    })();
});
