        $(function() {
            $('form').on('submit', function(e) {
                e.preventDefault();
                var form = $(this);
                var url = form.attr('action');
                var data = form.serialize();
                $.ajax({
                    url: url,
                    type: 'POST',
                    data: data + '&_method=PUT',
                    headers: {
                        'X-CSRF-TOKEN': $('input[name="_token"]').val()
                    },
                    success: function(response) {
                        if (response && response.status && response.message) {
                            localStorage.setItem('role_update_toast', response.message);
                        } else {
                            localStorage.setItem('role_update_toast', 'Role updated successfully');
                        }
                        window.location.href = "{{ route('roles.index') }}";
                    },
                    error: function(xhr) {
                        if(xhr.status === 422) {
                            var errors = xhr.responseJSON.errors;
                            var msg = Object.values(errors).map(function(e){ return e.join('<br>'); }).join('<br>');
                            alert(msg);
                        } else {
                            alert('Update failed.');
                        }
                    }
                });
            });
        });