$(function () {
    $("form").on("submit", function (event) {
        event.preventDefault();
        let form = $(this);
        let formData = new FormData(this);
        $('button[type="submit"]').prop("disabled", true);

        $.ajax({
            url: form.attr("action"),
            type: "POST",
            data: formData,
            dataType: "json",
            processData: false,
            contentType: false,
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
            },
            success: function (response) {
                $('button[type="submit"]').prop("disabled", false);
                if (response.status) {
                    showToast(
                        "success",
                        response.message || "Role created successfully"
                    );
                    // setTimeout(function() { window.location.href = "{{ route('roles.index') }}"; }, 1200);
                } else {
                    let errors = response.errors;

                    // Remove all previous error highlights and messages
                    $(".error").html("");
                    $("input, select").removeClass(
                        "border-red-500 ring-2 ring-red-400"
                    );

                    let permissionsErrorShown = false;
                    $.each(errors, function (key, value) {
                        value = Array.isArray(value) ? value[0] : value;
                        if (
                            key === "permissions" ||
                            key.startsWith("permissions.")
                        ) {
                            if (!permissionsErrorShown) {
                                $(".error-permissions").html(value);
                                $("input[name='permissions[]']").addClass(
                                    "ring-2 ring-red-400 border-red-500"
                                );
                                permissionsErrorShown = true;
                            }
                        } else {
                            let inputField = $(`[name='${key}']`);
                            let errorField = inputField
                                .closest(".mb-4")
                                .find(".error")
                                .first();
                            inputField.addClass(
                                "border-red-500 ring-2 ring-red-400"
                            );
                            errorField.html(value);
                        }
                    });

                    // Remove error classes/messages when user types/selects again
                    $("input, select").on("input change", function () {
                        $(this)
                            .removeClass("border-red-500 ring-2 ring-red-400")
                            .closest(".mb-4")
                            .find(".error")
                            .html("");
                        $(".error-permissions").html("");
                        $("input[name='permissions[]']").removeClass(
                            "ring-2 ring-red-400 border-red-500"
                        );
                    });
                }
            },
            error: function () {
                $('button[type="submit"]').prop("disabled", false);
                showToast("error", "Something went wrong. Please try again.");
            },
        });
    });
});
