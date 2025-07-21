<x-layouts.app>
    {{-- Page title --}}
    <x-slot name="title">Embroidery/Print List | AZ Group</x-slot>
    {{-- Page title end --}}

    {{-- Page header --}}
    <x-slot name="header">Embroidery/Print List</x-slot>
    {{-- Page header end --}}

    {{-- Models --}}
    <x-notification.notification-toast />
    <x-modals.delete-confirm />

    {{-- Page Content --}}
    <div class="bg-white shadow-sm font-ibm p-8 w-full rounded-lg mt-4">

        <div class="p-6 bg-white rounded-lg">
            <table id="table" class="min-w-full divide-y text-sm overflow-x-scroll">
                <thead>
                    <tr>
                        <th class="px-4 py-3 text-left font-semibold text-gray-700">ID</th>
                        <th class="px-4 py-3 text-left font-semibold text-gray-700">Style No</th>
                        <th class="px-4 py-3 text-left font-semibold text-gray-700">Garment Type</th>
                        <th class="px-4 py-3 text-left font-semibold text-gray-700">Total Send</th>
                        <th class="px-4 py-3 text-left font-semibold text-gray-700">Total Receive</th>
                        <th class="px-4 py-3 text-left font-semibold text-gray-700">Date</th>
                        <th class="px-4 py-3 text-left font-semibold text-gray-700">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($embroideryPrints as $embroideryPrint)
                        @php
                            $totalSend = collect($embroideryPrint->emb_or_print ?? [])->sum('send');
                            $totalReceive = collect($embroideryPrint->emb_or_print ?? [])->sum('received');

                        @endphp
                        <tr class="border-b-gray-400" data-order-id="{{ $embroideryPrint->id }}">
                            <td class="px-4 py-2">{{ $embroideryPrint->id }}</td>
                            <td class="px-4 py-2">{{ $embroideryPrint->order->style_no ?? 'N/A' }}</td>
                            <td class="px-4 py-2">{{ $embroideryPrint->garment_type ?? 'N/A' }}</td>
                            <td class="px-4 py-2 font-bold text-green-600">{{ $totalSend }}</td>
                            <td class="px-4 py-2 font-bold text-green-600">{{ $totalReceive }}</td>
                            <td class="px-4 py-2">{{ \Carbon\Carbon::parse($embroideryPrint->date)->format('M d, Y') }}
                            <td class="px-4 py-2 flex gap-5 items-center">
                                <a class="text-blue-500 rounded-full"
                                    href="{{ route('embroidery_prints.export', $embroideryPrint->id) }}"><i
                                        class="fa-regular fa-file-xls"></i></a>
                                <a class="text-yellow-500 rounded-full"
                                    href="{{ route('embroidery_prints.show', $embroideryPrint->id) }}"><i
                                        class="fa-regular fa-eye"></i></a>
                                <a class="text-green-600"
                                    href="{{ route('embroidery_prints.edit', $embroideryPrint->id) }}"><i
                                        class="fa-regular fa-pen"></i></a>

                                <button
                                    class="delete-btn text-red-500 hover:text-red-700 cursor-pointer"
                                    data-id="{{ $embroideryPrint->id }}" title="Delete"><i
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
                let table = $("#table").DataTable({
                    responsive: true,
                    order: [
                        [0, "desc"]
                    ],
                    scrollY: 'calc(100vh - 350px)', 
                    scrollCollapse: true,
                    scrollX: true,
                    language: {
                        search: "_INPUT_",
                        searchPlaceholder: "Search report...",
                    },
                });
                setTimeout(function() {
                    // Add New Role button beside search input
                    let searchWrapper = $("#table_filter");
                    searchWrapper.find(".add-new-role-btn").remove();
                    let addBtn = $(`
                        <a href="{{ route('embroidery_prints.create') }}"
                            class="add-new-role-btn">
                            <i class="fa fa-plus mr-2"></i> Add New Report
                        </a>
                    `);
                    searchWrapper.append(addBtn);
                    // On mobile: vertical (col) and centered, on lg+ horizontal (row)
                    searchWrapper.removeClass("flex-col flex-row items-center justify-center");
                    searchWrapper.addClass(
                        "flex flex-col gap-2 items-center justify-center lg:flex-row lg:items-center lg:justify-start"
                    );
                }, 200);

                // Delete cutting with custom modal
                $(document).on("click", ".delete-btn", function(e) {
                    e.preventDefault();
                    let btn = $(this);
                    let userId = btn.data("id");
                    window.showDeleteModal({
                        id: userId,
                        message: "Do you really want to delete this user? This process cannot be undone.",
                        onConfirm: function(id) {
                            let btn = $(`button.delete-btn[data-id='${id}']`);
                            $.ajax({
                                url: "/embroidery-prints/" + id,
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
                                            "Embroidery/Print report deleted successfully"
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
