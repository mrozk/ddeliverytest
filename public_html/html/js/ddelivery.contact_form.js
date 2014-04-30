/**
 * Created by DnAp on 11.04.14.
 */
var ContactForm = (function () {
    var el;

    return {
        init: function () {
            $('.phone-mask').mask("+7(999)999-99-99");

            var form = $('#main_form').submit(function () {

                return false;
            });

            $('input', form).on('required', function() {
                var el = $(this), val;
                if(el.hasClass('tipped')){
                    val = '';
                }else{
                    val = el.val().trim();
                }
                if(el.attr('req') && val.length == 0) {
                    el.closest('.row__inp').addClass('error');
                }else{
                    el.closest('.row__inp').removeClass('error');
                }
            }).blur(function(){
                $(this).trigger('required');
                return true;
            }).on('keyup', function(){
                $(this).trigger('required');
            });

            $('.row-btns a.next').click(function () {
                $('input, textarea', form).trigger('required');
                if($('.error', form).length > 0){
                    return false;
                }
                DDeliveryIframe.ajaxPage({contact_form:form.serializeArray(), action: 'change'});
            });

            $('.row-btns a.prev').click(function () {
                DDeliveryIframe.ajaxPage({type:$(this).data('type')});
            });

            $('input[title], textarea[title]', form).formtips();
        }
    }
})();
