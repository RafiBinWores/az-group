$(document).ready(function() {
    $('#form').on('submit', function(e) {
        e.preventDefault();
        $('button[type="submit"]').prop('disabled', true);
        
        $('#submit-btn .btn-text').addClass('hidden');
        $('#submit-btn .btn-loading').removeClass('hidden');

        $.ajax({
            url: '/forgot-password',
            type: 'POST',
            data: $(this).serialize(),
            success: function(response) {
                $('button[type="submit"]').prop('disabled', false);
                $('#submit-btn .btn-text').removeClass('hidden');
                $('#submit-btn .btn-loading').addClass('hidden');
                if (response.status) {
                    $('#main-alert-msg').text(response.message).removeClass('text-red-600').addClass('text-green-600');
                    $('#main-alert').removeClass('hidden bg-blue-50').addClass('bg-green-50');
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
                        $('#main-alert-msg').text(response.message).removeClass('text-green-600').addClass('text-red-600');
                        $('#main-alert').removeClass('hidden bg-green-50').addClass('bg-blue-50');
                    }
                }
            },
            error: function(xhr) {
                $('button[type="submit"]').prop('disabled', false);
                $('#submit-btn .btn-text').removeClass('hidden');
                $('#submit-btn .btn-loading').addClass('hidden');
                if (xhr.status === 422 && xhr.responseJSON && xhr.responseJSON.errors) {
                    let errors = xhr.responseJSON.errors;
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
                } else {
                    let msg = xhr.responseJSON?.message || 'Something went wrong. Please try again.';
                    $('#main-alert-msg').text(msg).removeClass('text-green-600').addClass('text-red-600');
                    $('#main-alert').removeClass('hidden bg-green-50').addClass('bg-blue-50');
                }
            }
        });
    });

    // Close alert on x icon click
    $('#main-alert').on('click', '.fa-xmark-large', function() {
        $('#main-alert').addClass('hidden');
    });
});