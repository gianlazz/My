define([], function() {
    window.log = function(param)
    {
        if (typeof(console.log) !== 'undefined') {
            console.log(param);
        }
    }
    return window.log;
})
