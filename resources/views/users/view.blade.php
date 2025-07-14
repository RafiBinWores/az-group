<x-layouts.app>
    {{-- Page title --}}
    <x-slot name="title">Users List | AZ Group</x-slot>
    {{-- Page title end --}}

    {{-- Page header --}}
    <x-slot name="header">User List</x-slot>
    {{-- Page header end --}}

    {{-- Models --}}
    <x-notification.notification-toast />
    <x-modals.delete-confirm />

    {{-- Page Content --}}
    <div class="bg-white shadow-sm font-ibm p-8 w-full rounded-lg mt-4 overflow-x-auto">

        <div class="p-6 bg-white rounded-lg overflow-x-auto">
            <table id="users-table" class="min-w-full divide-y text-sm">
                <thead>
                    <tr>
                        <th class="px-4 py-3 text-left font-semibold text-gray-700">ID</th>
                        <th class="px-4 py-3 text-left font-semibold text-gray-700">Avatar</th>
                        <th class="px-4 py-3 text-left font-semibold text-gray-700">Name</th>
                        <th class="px-4 py-3 text-left font-semibold text-gray-700">Role</th>
                        <th class="px-4 py-3 text-left font-semibold text-gray-700">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($users as $user)
                        <tr class="border-b-gray-400" data-user-id="{{ $user->id }}">
                            <td class="px-4 py-2">{{ $user->id }}</td>
                            <td class="px-4 py-2">
                                @if (!empty($user->avatar))
                                    <img class="size-12 rounded-md" src="{{ asset($user->avatar) }}" alt="{{ $user->name }}">
                                @else
                                    <svg viewBox="0 0 61.7998 61.7998" xmlns="http://www.w3.org/2000/svg" fill="#000000" class="size-12"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <title></title> <g data-name="Layer 2" id="Layer_2"> <g data-name="—ÎÓÈ 1" id="_ÎÓÈ_1"> <circle cx="30.8999" cy="30.8999" fill="#485a69" r="30.8999"></circle> <path d="M23.242 38.592l15.92.209v12.918l-15.907-.121-.013-13.006z" fill="#f9dca4" fill-rule="evenodd"></path> <path d="M53.478 51.993A30.814 30.814 0 0 1 30.9 61.8a31.225 31.225 0 0 1-3.837-.237A30.699 30.699 0 0 1 15.9 57.919a31.033 31.033 0 0 1-7.857-6.225l1.284-3.1 13.925-6.212c0 4.535 1.84 6.152 7.97 6.244 7.57.113 7.94-1.606 7.94-6.28l12.79 6.281z" fill="#d5e1ed" fill-rule="evenodd"></path> <path d="M39.165 38.778v3.404c-2.75 4.914-14 4.998-15.923-3.59z" fill-rule="evenodd" opacity="0.11"></path> <path d="M31.129 8.432c21.281 0 12.987 35.266 0 35.266-12.267 0-21.281-35.266 0-35.266z" fill="#ffe8be" fill-rule="evenodd"></path> <path d="M18.365 24.045c-3.07 1.34-.46 7.687 1.472 7.658a31.973 31.973 0 0 1-1.472-7.658z" fill="#f9dca4" fill-rule="evenodd"></path> <path d="M44.14 24.045c3.07 1.339.46 7.687-1.471 7.658a31.992 31.992 0 0 0 1.471-7.658z" fill="#f9dca4" fill-rule="evenodd"></path> <path d="M43.409 29.584s1.066-8.716-2.015-11.752c-1.34 3.528-7.502 4.733-7.502 4.733a16.62 16.62 0 0 0 3.215-2.947c-1.652.715-6.876 2.858-11.61 1.161a23.715 23.715 0 0 0 3.617-2.679s-4.287 2.322-8.44 1.742c-2.991 2.232-1.66 9.162-1.66 9.162C15 18.417 18.697 6.296 31.39 6.226c12.358-.069 16.17 11.847 12.018 23.358z" fill="#ecbe6a" fill-rule="evenodd"></path> <path d="M23.255 42.179a17.39 17.39 0 0 0 7.958 6.446l-5.182 5.349L19.44 43.87z" fill="#ffffff" fill-rule="evenodd"></path> <path d="M39.16 42.179a17.391 17.391 0 0 1-7.958 6.446l5.181 5.349 6.592-10.103z" fill="#ffffff" fill-rule="evenodd"></path> <path d="M33.366 61.7q-1.239.097-2.504.098-.954 0-1.895-.056l1.031-8.757h2.41z" fill="#3dbc93" fill-rule="evenodd"></path> <path d="M28.472 51.456l2.737-2.817 2.736 2.817-2.736 2.817-2.737-2.817z" fill="#3dbc93" fill-rule="evenodd"></path> </g> </g> </g></svg>
                                @endif
                            </td>
                        
                            <td class="px-4 py-2">{{ $user->name }}</td>
                            <td class="px-4 py-2">
                                @if ($user->getRoleNames()->count())
                                    @foreach ($user->getRoleNames() as $role)
                                        <span
                                            class="inline-block bg-blue-50 text-blue-600 px-2 py-1 rounded-full text-xs mr-1 mb-1">{{ $role }}</span>
                                    @endforeach
                                @else
                                    <span class="inline-block bg-red-50 text-red-600 px-2 py-1 rounded-full text-xs mr-1 mb-1">No role assigned</span>
                                @endif
                            </td>
                            <td class="px-4 py-2 flex gap-5 items-center">
                                <a class="text-green-600" href="{{ route('users.edit', $user->id) }}"><i
                                        class="fa-regular fa-pen"></i></a>
                                <button class="delete-user-btn text-red-500 hover:text-red-700 cursor-pointer"
                                    data-id="{{ $user->id }}" title="Delete"><i
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
                let table = $("#users-table").DataTable({
                    responsive: true,
                    scrollX: true,
                    language: {
                        search: "_INPUT_",
                        searchPlaceholder: "Search users...",
                    },
                });
                setTimeout(function() {
                    // Add New Role button beside search input
                    let searchWrapper = $("#users-table_filter");
                    searchWrapper.find(".add-new-role-btn").remove();
                    let addBtn = $(`
                        <a href="{{ route('users.create') }}"
                            class="add-new-role-btn">
                            <i class="fa fa-plus mr-2"></i> Add New User
                        </a>
                    `);
                    searchWrapper.append(addBtn);
                    // On mobile: vertical (col) and centered, on lg+ horizontal (row)
                    searchWrapper.removeClass("flex-col flex-row items-center justify-center");
                    searchWrapper.addClass("flex flex-col gap-2 justify-center items-center lg:flex-row lg:items-center lg:justify-start");
                }, 200);

                // Delete user with custom modal
                $(document).on("click", ".delete-user-btn", function(e) {
                    e.preventDefault();
                    let btn = $(this);
                    let userId = btn.data("id");
                    window.showDeleteModal({
                        id: userId,
                        message: "Do you really want to delete this user? This process cannot be undone.",
                        onConfirm: function(id) {
                            let btn = $(`button.delete-user-btn[data-id='${id}']`);
                            $.ajax({
                                url: "/users/" + id,
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
                                            "User deleted successfully"
                                        );
                                        // Remove row from table
                                        let row = btn.closest("tr");
                                        table.row(row).remove().draw();
                                    } else {
                                        showToast(
                                            "error",
                                            response.message ||
                                            "Could not delete user."
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
    @endpush
</x-layouts.app>
