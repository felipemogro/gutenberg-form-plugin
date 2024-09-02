(function ($) {
    $(document).ready(function () {
        $('#gfp-form').on('submit', function (e) {
            e.preventDefault();

            let formData = {
                action: 'gfp_form_submit',
                name: $('#gfp-name').val(),
                lastname: $('#gfp-lastname').val(),
                email: $('#gfp-email').val(),
                country: $('#gfp-country').val(),
                suggestions: $('#gfp-suggestions').val(),
                nonce: gfp_ajax_obj.nonce
            };

            $.post(gfp_ajax_obj.ajax_url, formData, function (response) {
                if (response.success) {
                    Swal.fire('¡Gracias!', response.data, 'success');
                    $('#gfp-form')[0].reset();
                } else {
                    Swal.fire('¡Error!', response.data, 'error');
                }
            });
        });

        if (gfp_ajax_obj.is_user_logged_in) {
            $('#gfp-name').val(gfp_ajax_obj.user_name);
            $('#gfp-lastname').val(gfp_ajax_obj.user_lastname);
            $('#gfp-email').val(gfp_ajax_obj.user_email);
        }

        $.ajax({
            url: 'https://restcountries.com/v3.1/all',
            method: 'GET',
            success: function (countries) {
                countries.sort((a, b) => a.name.common.localeCompare(b.name.common));
                countries.forEach((country) => {
                    $('#gfp-country').append(
                        `<option value="${country.name.common}">${country.name.common}</option>`
                    );
                });
            },
        });
    });
})(jQuery);
