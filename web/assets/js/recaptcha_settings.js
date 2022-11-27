

var verifyCallback = function(response) {

        $('input[name="g-recaptcha-response"]').val(response);
      };

var widgetId1;
var widgetId2;
var widgetId3;

var onloadCallback = function() {
// Renders the HTML element with id 'example1' as a reCAPTCHA widget.
// The id of the reCAPTCHA widget is assigned to 'widgetId1'.
    widgetId1 = grecaptcha.render('recap1', {
        'sitekey' : '6LcGCzUUAAAAADpeaCQhkXWZqdhnB6_ZEGRm7Z2m',
        'theme' : 'light',
        'callback' : verifyCallback

    });
    widgetId2 = grecaptcha.render('recap2', {
        'sitekey' : '6LcGCzUUAAAAADpeaCQhkXWZqdhnB6_ZEGRm7Z2m',
'callback' : verifyCallback
    });
    widgetId3 = grecaptcha.render('recap3', {
        'sitekey' : '6LcGCzUUAAAAADpeaCQhkXWZqdhnB6_ZEGRm7Z2m',
        'callback' : verifyCallback
    });
};