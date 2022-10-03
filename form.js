$("#form").submit(function (e) {

    e.preventDefault(); // avoid to execute the actual submit of the form.
    $(".form-group").removeClass("has-error");
    $(".help-block").remove();

    var response = grecaptcha.getResponse();
    var recaptcha_response = null;
    if(response.length !== 0) {
        recaptcha_response = response;
    }

    var form = $(this);
    var actionUrl = form.attr('action');
    var formData = {
        name: $("#name").val(),
        email: $("#email").val(),
        phone: $("#phone").val(),
        postcode: $("#postcode").val(),
        comments: $("#msg").val(),
        recaptcha: recaptcha_response,
    };

    $.ajax({
        type: "POST",
        url: actionUrl,
        data: formData,
        dataType: "json",
        encode: true,
    }).done(function (data) {
        console.log(data.success);
        if (!data.success) {
            if (data.errors.name) {
                $(".name-group").addClass("has-error");
                $(".name-group").append(
                    '<div class="help-block">' + data.errors.name + "</div>"
                );
            }

            if (data.errors.email) {
                $(".email-group").addClass("has-error");
                $(".email-group").append(
                    '<div class="help-block">' + data.errors.email + "</div>"
                );
            }

            if (data.errors.phone) {
                $(".phone-group").addClass("has-error");
                $(".phone-group").append(
                    '<div class="help-block">' + data.errors.phone + "</div>"
                );
            }

            if (data.errors.postcode) {
                $(".postcode-group").addClass("has-error");
                $(".postcode-group").append(
                    '<div class="help-block">' + data.errors.postcode + "</div>"
                );
            }

            if (data.errors.recaptcha) {
                $(".recaptcha-group").addClass("has-error");
                $(".recaptcha-group").append(
                    '<div class="help-block">' + data.errors.recaptcha + "</div>"
                );
            }
        } else {
            $("form").html(
                '<div class="alert alert-success">' + data.message + "</div>"
            );
        }
    });

});