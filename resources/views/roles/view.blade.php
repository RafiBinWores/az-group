<x-layouts.app>
    {{-- Page title --}}
    <x-slot name="title">Roles List | AZ Group</x-slot>
    {{-- Page title end --}}

    {{-- Page header --}}
    <x-slot name="header">Role List</x-slot>
    {{-- Page header end --}}

    {{-- Models --}}
    <x-notification.notification-toast />
    <x-modals.delete-confirm />

    {{-- Page Content --}}
    <div class="bg-white shadow-sm font-ibm p-8 w-full rounded-lg mt-4 overflow-x-auto">

        <div class="p-6 bg-white rounded-lg overflow-x-auto">
            <table id="roles-table" class="min-w-full divide-y text-sm">
                <thead>
                    <tr>
                        <th class="px-4 py-3 text-left font-semibold text-gray-700">ID</th>
                        <th class="px-4 py-3 text-left font-semibold text-gray-700">Role Name</th>
                        <th class="px-4 py-3 text-left font-semibold text-gray-700">Permissions</th>
                        <th class="px-4 py-3 text-left font-semibold text-gray-700">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($roles as $role)
                        <tr class="border-b-gray-400" data-role-id="{{ $role->id }}">
                            <td class="px-4 py-2">{{ $role->id }}</td>
                            <td class="px-4 py-2">{{ $role->name }}</td>
                            <td class="px-4 py-2">
                                @if ($role->permissions->count())
                                    @foreach ($role->permissions as $permission)
                                        <span
                                            class="inline-block bg-blue-50 text-blue-600 px-2 py-1 rounded-full text-xs mr-1 mb-1">{{ $permission->name }}</span>
                                    @endforeach
                                @else
                                    <span class="text-gray-400 text-xs">No permissions</span>
                                @endif
                            </td>
                            <td class="px-4 py-2 flex gap-5 items-center">
                                <a class="text-green-600" href="{{ route('roles.edit', $role->id) }}"><i
                                        class="fa-regular fa-pen"></i></a>
                                <button class="delete-role-btn text-red-500 hover:text-red-700 cursor-pointer"
                                    data-id="{{ $role->id }}" title="Delete"><i
                                        class="fa-regular fa-trash"></i></button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    {{-- Page Scripts --}}
    @push('scripts')
        <script>
            $(document).ready(function() {
                let table = $("#roles-table").DataTable({
                    responsive: true,
                    scrollX: true,
                    language: {
                        search: "_INPUT_",
                        searchPlaceholder: "Search roles...",
                    },
                });
                setTimeout(function() {
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
                    // On mobile: vertical (col) and centered, on lg+ horizontal (row)
                    searchWrapper.removeClass("flex-col flex-row items-stretch items-center justify-center");
                    searchWrapper.addClass("flex flex-col gap-2 items-stretch justify-center items-center lg:flex-row lg:items-center lg:justify-start");
                }, 200);
                // Delete role with custom modal
                $(document).on("click", ".delete-role-btn", function(e) {
                    e.preventDefault();
                    let btn = $(this);
                    let roleId = btn.data("id");
                    window.showDeleteModal({
                        id: roleId,
                        message: "Do you really want to delete this role? This process cannot be undone.",
                        onConfirm: function(id) {
                            let btn = $(`button.delete-role-btn[data-id='${id}']`);
                            $.ajax({
                                url: "/roles/" + id,
                                type: "POST",
                                data: {
                                    _method: "DELETE",
                                    _token: $('meta[name="csrf-token"]').attr("content"),
                                },
                                success: function(response) {
                                    if (response.status) {
                                        showToast(
                                            "success",
                                            response.message ||
                                            "Role deleted successfully"
                                        );
                                        // Remove row from table
                                        let row = btn.closest("tr");
                                        table.row(row).remove().draw();
                                    } else {
                                        showToast(
                                            "error",
                                            response.message ||
                                            "Could not delete role."
                                        );
                                    }
                                },
                                error: function() {
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
        </script>
        {{-- <script src="{{ asset('assets/js/pages/roles/roleView.js') }}"></script> --}}
    @endpush
</x-layouts.app>
