// require jQuery normally
const $ = require('jquery');
window.jQuery = $;
window.$ = $;

(function($) {
    /**
     * Toggle view password.
     */
    $(document).on('click', '#toggle-password-icon', function() {
        $('#password_reset_password').attr('type', function(index, attr){return attr === 'password' ? 'text' : 'password'; });
    });

    /**
     * Password strength indicator.
     */
    $('#password_reset_password').keyup(function() {
        const pw_reset_button = $('#password_reset_submit');
        const pw_toggle_button = $('#toggle-password-icon');
        const pw_strength_text = $('#password-strength-text');
        const pw_info_text = $('#password-info-text');

        let pw_length = this.value.length;

        pw_reset_button.attr('disabled', 'disabled');

        if (pw_length >= 0 && pw_length <= 7) {
            pw_reset_button.attr('disabled', 'disabled');
            $(this).addClass('is-danger');
            pw_toggle_button.addClass('is-danger');
            pw_strength_text.removeClass('is-hidden').addClass('is-danger');
            pw_toggle_button.children().removeClass('has-text-grey-light').addClass('has-text-dark');
            changeText(pw_strength_text, 'Weak');
            pw_info_text.removeClass('is-hidden');
        } else if (pw_length > 7 && pw_length <= 9) {
            pw_info_text.addClass('is-hidden');
            pw_reset_button.removeAttr('disabled');
            $(this).removeClass('is-danger').addClass('is-warning');
            pw_toggle_button.removeClass('is-danger').addClass('is-warning');
            pw_strength_text.removeClass('is-danger').addClass('is-warning');
            changeText(pw_strength_text, 'Average');
        } else {
            pw_reset_button.removeAttr('disabled');
            $(this).removeClass('is-warning').addClass('is-success');
            pw_toggle_button.removeClass('is-warning').addClass('is-success');
            pw_strength_text.removeClass('is-warning').addClass('is-success');
            changeText(pw_strength_text, 'Strong');
        }
    });

    /**
     * Change text of DOM element.
     *
     * @param el
     * @param text
     * @param color
     */
    const changeText = function (el, text, color) {
        el.text(text).css('color', color);
    };

})(jQuery);
