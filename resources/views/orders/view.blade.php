<x-layouts.app>
    {{-- Page title --}}
    <x-slot name="title">Order List | AZ Group</x-slot>
    {{-- Page title end --}}

    {{-- Page header --}}
    <x-slot name="header">Order List</x-slot>
    {{-- Page header end --}}

    {{-- Models --}}
    <x-notification.notification-toast />
    <x-modals.delete-confirm />

    {{-- Page Content --}}
    <div class="bg-white shadow-sm font-ibm p-8 w-full rounded-lg mt-4 overflow-x-auto">

        <div class="p-6 bg-white rounded-lg overflow-x-auto">
            <table id="orders-table" class="min-w-full divide-y text-sm">
                <thead>
                    <tr>
                        <th class="px-4 py-3 text-left font-semibold text-gray-700">ID</th>
                        <th class="px-4 py-3 text-left font-semibold text-gray-700">Buyer Name</th>
                        <th class="px-4 py-3 text-left font-semibold text-gray-700">Style No</th>
                        <th class="px-4 py-3 text-left font-semibold text-gray-700">Order Quantity</th>
                        <th class="px-4 py-3 text-left font-semibold text-gray-700">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($orders as $order)
                        <tr class="border-b-gray-400" data-order-id="{{ $order->id }}">
                            <td class="px-4 py-2">{{ $order->id }}</td>
                            <td class="px-4 py-2">{{ $order->buyer_name }}</td>
                            <td class="px-4 py-2">{{ $order->style_no }}</td>
                            <td class="px-4 py-2">{{ $order->order_qty }}</td>
                            <td class="px-4 py-2 flex gap-5 items-center">
                                @can('update', $order)
                                    <a class="text-green-600" href="{{ route('orders.edit', $order->id) }}"><i
                                        class="fa-regular fa-pen"></i></a>
                                @endcan
                                @can('delete', $order)
                                    <button class="delete-order-btn text-red-500 hover:text-red-700 cursor-pointer"
                                    data-id="{{ $order->id }}" title="Delete"><i
                                        class="fa-regular fa-trash"></i></button>
                                @endcan
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
                let table = $("#orders-table").DataTable({
                    responsive: true,
                    scrollX: true,
                    language: {
                        search: "_INPUT_",
                        searchPlaceholder: "Search orders...",
                    },
                });
                setTimeout(function() {
                    // Add New Role button beside search input
                    let searchWrapper = $("#orders-table_filter");
                    searchWrapper.find(".add-new-role-btn").remove();
                    let addBtn = $(`
                        <a href="{{ route('orders.create') }}"
                            class="add-new-role-btn">
                            <i class="fa fa-plus mr-2"></i> Add New Order
                        </a>
                    `);
                    searchWrapper.append(addBtn);
                    // On mobile: vertical (col) and centered, on lg+ horizontal (row)
                    searchWrapper.removeClass("flex-col flex-row items-center justify-center");
                    searchWrapper.addClass("flex flex-col gap-2 justify-center items-center lg:flex-row lg:items-center lg:justify-start");
                }, 200);

                // Delete order with custom modal
                $(document).on("click", ".delete-order-btn", function(e) {
                    e.preventDefault();
                    let btn = $(this);
                    let userId = btn.data("id");
                    window.showDeleteModal({
                        id: userId,
                        message: "Do you really want to delete this user? This process cannot be undone.",
                        onConfirm: function(id) {
                            let btn = $(`button.delete-order-btn[data-id='${id}']`);
                            $.ajax({
                                url: "/orders/" + id,
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
                                            "Order deleted successfully"
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
