<x-layouts.app>
    {{-- Page title --}}
    <x-slot name="title">Garment Types List | AZ Group</x-slot>
    {{-- Page title end --}}

    {{-- Page header --}}
    <x-slot name="header">Garment Types List</x-slot>
    {{-- Page header end --}}

    {{-- Models --}}
    <x-notification.notification-toast />
    <x-modals.delete-confirm />

    {{-- Page Content --}}
    <div class="bg-white shadow-sm font-ibm p-8 w-full rounded-lg mt-4 overflow-x-auto">

        <div class="p-6 bg-white rounded-lg overflow-x-auto">
            <table id="types-table" class="min-w-full divide-y text-sm">
                <thead>
                    <tr>
                        <th class="px-4 py-3 text-left font-semibold text-gray-700">ID</th>
                        <th class="px-4 py-3 text-left font-semibold text-gray-700">Name</th>
                        <th class="px-4 py-3 text-left font-semibold text-gray-700">Status</th>
                        <th class="px-4 py-3 text-left font-semibold text-gray-700">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($garmentTypes as $type)
                        <tr class="border-b-gray-400" data-order-id="{{ $type->id }}">
                            <td class="px-4 py-2">{{ $type->id }}</td>
                            <td class="px-4 py-2">{{ $type->name }}</td>
                            <td class="px-4 py-2">
                                <label
                                    class="relative inline-block h-6 w-12 cursor-pointer rounded-full bg-gray-300 transition has-[:checked]:bg-[#99c041]">
                                    <input class="peer sr-only status-toggle" type="checkbox"
                                        data-type-id="{{ $type->id }}" {{ $type->status ? 'checked' : '' }} />
                                    <span
                                        class="absolute inset-y-0 start-0 m-1 size-4 rounded-full bg-gray-300 ring-[4px] ring-inset ring-white transition-all peer-checked:start-8 peer-checked:w-2 peer-checked:bg-white peer-checked:ring-transparent"></span>
                                </label>
                                <span
                                    class="ml-2 text-sm font-medium status-label {{ $type->status ? 'text-[#99c041]' : 'text-gray-400' }}">
                                </span>
                            </td>
                            <td class="px-4 py-2 flex gap-5 items-center">
                                <a class="text-green-600" href="{{ route('garment_types.edit', $type->id) }}">
                                    <i class="fa-regular fa-pen"></i>
                                </a>
                                <button class="delete-type-btn text-red-500 hover:text-red-700 cursor-pointer"
                                    data-id="{{ $type->id }}" title="Delete">
                                    <i class="fa-regular fa-trash"></i>
                                </button>
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
                let table = $("#types-table").DataTable({
                    responsive: true,
                    scrollX: true,
                    language: {
                        search: "_INPUT_",
                        searchPlaceholder: "Search garments...",
                    },
                });
                setTimeout(function() {
                    // Add New Role button beside search input
                    let searchWrapper = $("#types-table_filter");
                    searchWrapper.find(".add-new-role-btn").remove();
                    let addBtn = $(`
                        <a href="{{ route('garment_types.create') }}"
                            class="add-new-role-btn">
                            <i class="fa fa-plus mr-2"></i> Add New Type
                        </a>
                    `);
                    searchWrapper.append(addBtn);
                    // On mobile: vertical (col) and centered, on lg+ horizontal (row)
                    searchWrapper.removeClass("flex-col flex-row items-center justify-center");
                    searchWrapper.addClass(
                        "flex flex-col gap-2 justify-center items-center lg:flex-row lg:items-center lg:justify-start"
                    );
                }, 200);

                // status update
                $(document).on('change', '.status-toggle', function() {
                    let typeId = $(this).data('type-id');
                    let status = $(this).is(':checked') ? 1 : 0;
                    let _token = "{{ csrf_token() }}";

                    $.ajax({
                        url: "{{ route('garment_types.updateStatus') }}",
                        type: "POST",
                        data: {
                            id: typeId,
                            status: status,
                            _token: _token
                        },
                        success: function(response) {
                            if (response.success) {
                               showToast(
                                    "success",
                                    response.message || "Status updated successfully!"
                                );
                            } else {
                                alert('Failed to update status!');
                            }
                        },
                        error: function() {
                            alert('AJAX error!');
                        }
                    });
                });


                // Delete order with custom modal
                $(document).on("click", ".delete-type-btn", function(e) {
                    e.preventDefault();
                    let btn = $(this);
                    let userId = btn.data("id");
                    window.showDeleteModal({
                        id: userId,
                        message: "Do you really want to delete this user? This process cannot be undone.",
                        onConfirm: function(id) {
                            let btn = $(`button.delete-type-btn[data-id='${id}']`);
                            $.ajax({
                                url: "/garment-types/" + id,
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
                                            "Garment types deleted successfully"
                                        );
                                        // Remove row from table
                                        let row = btn.closest("tr");
                                        table.row(row).remove().draw();
                                    } else {
                                        showToast(
                                            "error",
                                            response.message ||
                                            "Could not delete order."
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
