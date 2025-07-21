<x-layouts.app>
    {{-- Page title --}}
    <x-slot name="title">Edit Wash Report | AZ Group</x-slot>
    <x-slot name="header">Edit Wash Report</x-slot>

    {{-- Notification message --}}
    <x-notification.notification-toast />

    <div class="bg-white shadow-sm w-full rounded-lg mt-4">
        <div class="px-6 py-4 border-b border-gray-200 text-gray-700 font-semibold text-lg">
            Basic Information
        </div>
        <div class="p-6 font-ibm">
            <form id="form" action="{{ route('washes.update', $wash->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="mb-4">
                    <label class="font-semibold">Style No</label>
                    <select name="order_id" id="style-select"
                        class="w-full border mt-3 outline-[#99c041] border-gray-300 px-3 py-2 rounded-xl focus:ring-[#99c041] focus:border-[#99c041] transition">
                        <option value="" class="text-gray-300">Select a style</option>
                        @foreach ($orders as $order)
                            <option value="{{ $order->id }}" data-colors='@json($order->color_qty)'
                                data-garments='@json($order->garmentTypes->map->only(['id', 'name']))'
                                {{ $order->id == $wash->order_id ? 'selected' : '' }}>
                                {{ $order->style_no }}
                            </option>
                        @endforeach
                    </select>
                    <span class="error text-red-500 text-xs mt-1 block"></span>
                </div>

                <div class="mb-4">
                    <label class="font-semibold">Garment Type</label>
                    <select name="garment_type" id="garment_type"
                        class="w-full border mt-3 outline-[#99c041] border-gray-300 px-3 py-2 rounded-xl focus:ring-[#99c041] focus:border-[#99c041] transition">
                        <option value="">Select...</option>
                        {{-- Filled by JS, but also on load (see script) --}}
                    </select>
                    <span class="error text-red-500 text-xs mt-1 block"></span>
                </div>

                <div id="add-fields" class="space-y-2 mb-4">
                    <label class="font-semibold">Washes</label>
                    <span class="error text-red-500 text-xs mt-1 block"></span>
                    {{-- JS will fill fields --}}
                </div>

                <div class="mb-4">
                    <label class="font-semibold">Date</label>
                    <input type="date" name="date" id="date" value="{{ old('date', $wash->date) }}"
                        class="w-full border mt-3 outline-[#99c041] border-gray-300 px-3 py-2 rounded-xl focus:ring-[#99c041] focus:border-[#99c041] transition">
                    <span class="error text-red-500 text-xs mt-1 block"></span>
                </div>
                <!-- Buttons -->
                <div class="flex items-center gap-4 mt-5">
                    <a href="{{ route('washes.index') }}"
                        class="bg-red-400 cursor-pointer hover:bg-red-500 text-white px-4 py-2 rounded-xl">
                        <i class="fa-regular fa-xmark pe-1"></i> Cancel
                    </a>
                    <button type="submit"
                        class="bg-[#99c041] cursor-pointer hover:bg-[#89bb13] text-white px-4 py-2 rounded-xl">
                        <i class="fa-regular fa-file-spreadsheet pe-1"></i> Update
                    </button>
                </div>
            </form>
        </div>
    </div>

    @push('scripts')
        <script>
            new TomSelect("#style-select", {
                create: true,
                sortField: {
                    field: "text",
                    direction: "desc"
                }
            });

            // Helper to fill garment types on page load or style change
            function populateGarmentTypes(orderGarments, selectedGarment) {
                let garmentSelect = document.getElementById('garment_type');
                garmentSelect.innerHTML = '<option value="">Select...</option>';
                (orderGarments || []).forEach(type => {
                    garmentSelect.innerHTML +=
                        `<option value="${type.name}" ${type.name === selectedGarment ? 'selected' : ''}>${type.name}</option>`;
                });
            }
            // Helper to fill cutting fields for edit
            function populateWashFields(colorQty, washRows) {
                const fieldsDiv = document.getElementById('add-fields');
                fieldsDiv.innerHTML = `<label class="font-semibold">Washes</label>
                <span class="error text-red-500 text-xs mt-1 block"></span>`;
                (colorQty || []).forEach((row, idx) => {
                    // Try to find matching entry in washRows
                    let matching = (washRows || []).find(
                        cr => cr.color === row.color
                    );
                    fieldsDiv.insertBefore(
                        (() => {
                            const div = document.createElement('div');
                            div.className = 'flex gap-2 items-center mt-2';
                            div.innerHTML = `
                            <input type="text" readonly value="${row.color}" class="border border-gray-300 rounded-xl px-3 py-2 w-2/6 bg-gray-100" name="wash[${idx}][color]">
                            <input type="number" readonly min="0" placeholder="Order Qty" value="${row.order_qty ?? (matching ? matching.order_qty : '')}" class="border border-gray-300 bg-gray-100 rounded-xl px-3 py-2 w-1/6" name="wash[${idx}][order_qty]">
                            <input type="text" min="0" placeholder="Factory" class="border border-gray-300 rounded-xl px-3 py-2 w-2/6" name="wash[${idx}][factory]" value="${matching ? matching.factory : ''}">
                            <input type="number" min="0" placeholder="Send" class="border border-gray-300 rounded-xl px-3 py-2 w-1/6" name="wash[${idx}][send]" value="${matching ? matching.send : ''}">
                            <input type="number" min="0" placeholder="Received" class="border border-gray-300 rounded-xl px-3 py-2 w-1/6" name="wash[${idx}][received]" value="${matching ? matching.received : ''}">
                `;
                            return div;
                        })(),
                        fieldsDiv.querySelector('.error')
                    );
                });
            }

            // On page load, set initial fields
            document.addEventListener('DOMContentLoaded', function() {
                let styleSelect = document.getElementById('style-select');
                let selectedOption = styleSelect.options[styleSelect.selectedIndex];
                let orderGarments = [];
                let colorQty = [];
                try {
                    orderGarments = JSON.parse(selectedOption.getAttribute('data-garments') || '[]');
                    colorQty = JSON.parse(selectedOption.getAttribute('data-colors') || '[]');
                } catch (e) {}
                let selectedGarment = @json($wash->garment_type ?? '');
                let washRows = @json($wash->wash ?? []);

                // Fill garment type options and select
                populateGarmentTypes(orderGarments, selectedGarment);

                // Fill color/cutting fields (using current cutting data)
                populateWashFields(colorQty, washRows);
            });

            // On style no change, update fields
            document.getElementById('style-select').addEventListener('change', function() {
                let selected = this.options[this.selectedIndex];
                let garments = [];
                let colors = [];
                try {
                    garments = JSON.parse(selected.getAttribute('data-garments'));
                } catch (e) {}
                try {
                    colors = JSON.parse(selected.getAttribute('data-colors'));
                } catch (e) {}
                populateGarmentTypes(garments, '');
                populateCuttingFields(colors, []);
            });

            // Form submit via AJAX (same as before)
             $(function() {
                $("form").on("submit", function(event) {
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
                        success: function(response) {
                            $('button[type="submit"]').prop("disabled", false);
                            if (response.status) {
                                showToast(
                                    "success",
                                    response.message || "Wash report updated successfully"
                                );
                            } else {
                                if (response.message) {
                                    showToast("warning", response.message);
                                }
                                let errors = response.errors || {};

                                // Clear previous errors
                                $(".error").html("");
                                $("input, select").removeClass("border-red-500");

                                $.each(errors, function(key, value) {
                                    value = Array.isArray(value) ? value[0] : value;
                                    let inputField = $(`[name='${key}']`);
                                    let errorField = inputField
                                        .closest(".mb-4")
                                        .find(".error")
                                        .first();
                                    inputField.addClass("border-red-500");
                                    errorField.html(value);
                                });

                                // Remove error classes/messages on change
                                $("input, select").on("input change", function() {
                                    $(this)
                                        .removeClass("border-red-500")
                                        .closest(".mb-4")
                                        .find(".error")
                                        .html("");
                                });
                            }
                        },
                        error: function() {
                            $('button[type="submit"]').prop("disabled", false);
                            showToast("error", "Something went wrong. Please try again.");
                        },
                    });
                });
            });
        </script>
    @endpush
</x-layouts.app>
