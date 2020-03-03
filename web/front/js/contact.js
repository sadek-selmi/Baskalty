// form validation
var contactform = $("form[name='registration']");
if(contactform.length) {
    $(function() {
        $("form[name='registration']").validate({
        rules: {
            firstname: "required",
            message: "required",
            subject: "required",
            email: {
            required: true,
            email: true
            },
            password: {
            required: true,
            minlength: 5
            }
        },
        messages: {
            firstname: "Please enter your name",
            message: "Please enter your message",
            subject: "Please enter subject",
            password: {
            required: "Please provide a password",
            minlength: "Your password must be at least 5 characters long"
            },
            email: "Please enter a valid email address"
        }
        });
    });
}

    // Forms Validation
    var filterEmail  = /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,6})+$/;
    $('.contact-form').submit(function () {
        var errors = 0;
        $(this).find('[data-required="text"]').each(function () {
            if ($(this).attr('data-required-email') === 'email'){
                if (!filterEmail.test($(this).val())) {
                    $(this).addClass("redborder");
                    errors++;
                }
                else {
                    $(this).removeClass("redborder");
                }
                return;
            }
            if ($(this).val() === '') {
                $(this).addClass('redborder');
                errors++;
            } else {
                $(this).removeClass('redborder');
            }
        });
        if (errors === 0) {
            var form1 = $(this);
            $.ajax({
                type: "POST",
                url: 'contact-form.php',
                data: $(this).serialize(),
                success: function(data) {
                    form1.append('<p class="form-result">message has been sent successfully!</p>');
                    $("form").trigger('reset');
                }
            });
        }
        return false;
    });
    $('.contact-form').find('[data-required="text"]').blur(function () {
        if ($(this).attr('data-required-email') === 'email' && ($(this).hasClass("redborder"))) {
            if (filterEmail.test($(this).val()))
                $(this).removeClass("redborder");
            return;
        }
        if ($(this).val() != "" && ($(this).hasClass("redborder")))
            $(this).removeClass("redborder");
    });
