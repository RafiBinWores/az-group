$(document).ready(function () {
    let table = $("#roles-table").DataTable({
        responsive: true,
        language: {
            search: "_INPUT_",
            searchPlaceholder: "Search roles...",
        },
    });
    setTimeout(function () {
        // Add New Role button beside search input
        let searchWrapper = $("#roles-table_filter");
        searchWrapper.find(".add-new-role-btn").remove();
        let addBtn = $(`
                <a href="{{ route('roles.create') }}"
                    class="add-new-role-btn">
                    <i class="fa fa-plus mr-2"></i> Add New Role
                </a>
            `);
        searchWrapper.append(addBtn);
        searchWrapper.addClass("flex items-center gap-2");
    }, 200);
    // Delete role with custom modal
    $(document).on("click", ".delete-role-btn", function (e) {
        e.preventDefault();
        let btn = $(this);
        let roleId = btn.data("id");
        window.showDeleteModal({
            id: roleId,
            message:
                "Do you really want to delete this role? This process cannot be undone.",
            onConfirm: function (id) {
                let btn = $(`button.delete-role-btn[data-id='${id}']`);
                $.ajax({
                    url: "/roles/" + id,
                    type: "POST",
                    data: {
                        _method: "DELETE",
                        _token: $('meta[name="csrf-token"]').attr("content"),
                    },
                    success: function (response) {
                        if (response.status) {
                            showToast(
                                "success",
                                response.message || "Role deleted successfully"
                            );
                            // Remove row from table
                            let row = btn.closest("tr");
                            table.row(row).remove().draw();
                        } else {
                            showToast(
                                "error",
                                response.message || "Could not delete role."
                            );
                        }
                    },
                    error: function () {
                        showToast(
                            "error",
                            "Something went wrong. Please try again."
                        );
                    },
                });
            },
        });
    });
});
