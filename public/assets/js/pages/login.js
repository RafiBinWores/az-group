const passwordInput = document.getElementById("password");
const togglePasswordButton = document.getElementById("togglePassword");
const togglePasswordIcon = document.getElementById("togglePasswordIcon");

const eyeIcon = `<i class="fa-regular fa-eye text-xl"></i>`;
const eyeOffIcon = `<i class="fa-regular fa-eye-slash text-xl"></i>`;

togglePasswordButton.addEventListener("click", () => {
    const isPassword = passwordInput.getAttribute("type") === "password";
    passwordInput.setAttribute("type", isPassword ? "text" : "password");
    togglePasswordIcon.innerHTML = isPassword ? eyeIcon : eyeOffIcon;
});


// jQuery AJAX login handler
$(document).ready(function() {
    $('#form').submit(function(event) {
        event.preventDefault();
        let formData = new FormData(this);
        $('button[type="submit"]').prop('disabled', true);

        // Clear previous errors
        $('.error').removeClass('text-red-500').html('');
        $("input").removeClass('border-red-500 focus:ring-red-500');

        $.ajax({
            url: '/login',
            type: 'POST',
            data: formData,
            dataType: 'json',
            processData: false,
            contentType: false,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
            },
            success: function(response) {
                $('button[type="submit"]').prop('disabled', false);
                if (response.status) {
                    if (response.redirect) {
                        window.location.href = response.redirect;
                    } else {
                        location.reload();
                    }
                } else {
                    let errors = response.errors || {};
                    $.each(errors, function(key, value) {
                        let inputField = $('#' + key);
                        let errorField = inputField.closest('.mb-3').find('.error');
                        inputField.addClass('border-red-500 focus:ring-red-500');
                        errorField.addClass('text-red-500').html(value);
                        inputField.off('input').on('input', function() {
                            $(this).removeClass('border-red-500 focus:ring-red-500')
                                .closest('.mb-3').find('.error')
                                .removeClass('text-red-500').html('');
                        });
                    });
                    if (response.message && Object.keys(errors).length === 0) {
                        $('#main-alert-msg').text(response.message);
                        $('#main-alert').removeClass('hidden');
                    }
                }
            },
            error: function(xhr) {
                $('button[type="submit"]').prop('disabled', false);
                if (xhr.status === 422 && xhr.responseJSON && xhr.responseJSON.errors) {
                    let errors = xhr.responseJSON.errors;
                    $.each(errors, function(key, value) {
                        let inputField = $('#' + key);
                        let errorField = inputField.closest('.mb-3').find('.error');
                        inputField.addClass('border-red-500 focus:ring-red-500');
                        errorField.addClass('text-red-500').html(value);
                        // Remove error message for this field on input
                        inputField.off('input').on('input', function() {
                            $(this).removeClass('border-red-500 focus:ring-red-500')
                                .closest('.mb-3').find('.error')
                                .removeClass('text-red-500').html('');
                        });
                    });
                } else {
                    let msg = xhr.responseJSON?.message || 'Something went wrong. Please try again.';
                    $('#password').closest('.mb-3').find('.error').addClass('text-red-500').html(msg);
                }
            }
        });
    });
});
