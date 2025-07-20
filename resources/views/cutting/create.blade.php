<x-layouts.app>
    {{-- Page title --}}
    <x-slot name="title">Create cutting | AZ Group</x-slot>
    {{-- Page header --}}
    <x-slot name="header">Create cutting</x-slot>

    {{-- Notifications --}}
    <x-notification.notification-toast />

    {{-- Page Content --}}
    <div class="bg-white shadow-sm w-full rounded-lg mt-4">
        <div class="px-6 py-4 border-b border-gray-200 text-gray-700 font-semibold text-lg">
            Cutting Information
        </div>

        <div class="p-6 font-ibm">
            <form id="cutting-form" action="{{ route('cutting.store') }}" method="POST">
                @csrf
                <div class="mb-4">
                    <label class="font-semibold">Style No</label>
                    <select name="order_id" id="style-select"
                        class="w-full border mt-3 outline-[#99c041] border-gray-300 px-3 py-2 rounded-xl focus:ring-[#99c041] focus:border-[#99c041] transition">
                        <option value="" class="text-gray-300">Select a style</option>
                        @foreach ($orders as $order)
                            <option value="{{ $order->id }}"
                                data-colors='@json($order->color_qty)'
                                data-garments='@json($order->garmentTypes->map->only("id","name"))'>
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
                    </select>
                    <span class="error text-red-500 text-xs mt-1 block"></span>
                </div>

                <div id="cutting-fields" class="space-y-2 mb-4">
                    <label class="font-semibold">Cutting</label>
                    <span class="error text-red-500 text-xs mt-1 block"></span>
                </div>

                <div class="mb-4">
                    <label class="font-semibold">Date</label>
                    <input type="date" name="date" id="date"
                        class="w-full border mt-3 outline-[#99c041] border-gray-300 px-3 py-2 rounded-xl focus:ring-[#99c041] focus:border-[#99c041] transition">
                    <span class="error text-red-500 text-xs mt-1 block"></span>
                </div>
                <!-- Buttons -->
                <div class="flex items-center gap-4 mt-5">
                    <a href="{{ route('cutting.index') }}"
                        class="bg-red-400 cursor-pointer hover:bg-red-500 text-white px-4 py-2 rounded-xl">
                        <i class="fa-regular fa-xmark pe-1"></i> Cancel
                    </a>
                    <button type="submit"
                        class="bg-[#99c041] cursor-pointer hover:bg-[#89bb13] text-white px-4 py-2 rounded-xl">
                        <i class="fa-regular fa-file-spreadsheet pe-1"></i> Create
                    </button>
                </div>
            </form>
        </div>
    </div>

    @push('scripts')
        <script>
            // Dynamically update garment types and color fields
            document.getElementById('style-select').addEventListener('change', function() {
                // Garment Types
                let garmentSelect = document.getElementById('garment_type');
                garmentSelect.innerHTML = '<option value="">Select...</option>';
                let selected = this.options[this.selectedIndex];
                let garments = selected.getAttribute('data-garments');
                if (garments) {
                    try { garments = JSON.parse(garments); } catch (e) { garments = []; }
                    garments.forEach(type => {
                        garmentSelect.innerHTML += `<option value="${type.name}">${type.name}</option>`;
                    });
                }

                // Cutting fields
                const fieldsDiv = document.getElementById('cutting-fields');
                fieldsDiv.innerHTML = `<label class="font-semibold">Cutting</label>
                    <span class="error text-red-500 text-xs mt-1 block"></span>`;
                let colors = selected.getAttribute('data-colors');
                if (colors) {
                    try { colors = JSON.parse(colors); } catch (e) { colors = []; }
                    colors.forEach((row, idx) => {
                        const div = document.createElement('div');
                        div.className = 'flex gap-2 items-center mt-2';
                        div.innerHTML = `
                            <input type="text" readonly value="${row.color}" class="border border-gray-300 rounded-xl px-3 py-2 w-3/6 bg-gray-100" name="cutting[${idx}][color]">
                            <input type="number" readonly min="0" placeholder="Order Qty" value="${row.qty}" class="border border-gray-300 bg-gray-100 rounded-xl px-3 py-2 w-1/6" name="cutting[${idx}][order_qty]">
                            <input type="number" min="0" placeholder="Cutting Qty" class="border border-gray-300 rounded-xl px-3 py-2 w-2/6" name="cutting[${idx}][cutting_qty]">
                        `;
                        fieldsDiv.insertBefore(div, fieldsDiv.querySelector('.error'));
                    });
                }
            });

            // Form submit via AJAX (handles errors for both flat and nested fields)
            $(function() {
                $("#cutting-form").on("submit", function(event) {
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
                                    response.message || "Cutting report created successfully"
                                );
                                // Optionally reload or redirect
                            } else {
                                displayFieldErrors(response.errors || {});
                            }
                        },
                        error: function(xhr) {
                            $('button[type="submit"]').prop("disabled", false);
                            if (xhr.status === 422 && xhr.responseJSON && xhr.responseJSON.errors) {
                                displayFieldErrors(xhr.responseJSON.errors);
                            } else {
                                showToast("error", "Something went wrong. Please try again.");
                            }
                        },
                    });
                });

                // Error rendering function
                function displayFieldErrors(errors) {
                    $(".error").html("");
                    $("input, select").removeClass("border-red-500");

                    $.each(errors, function(key, value) {
                        // Convert array field names (dot notation) to correct selector
                        let name = key.replace(/\./g, "][");
                        let fieldSelector = `[name='${name}']`;
                        let inputField = $(fieldSelector);

                        // If not found, try flat fields
                        if (!inputField.length) inputField = $(`[name='${key}']`);

                        // Try finding the error span (looks for .error in parent mb-4, or after input for arrays)
                        let errorField = inputField.closest(".mb-4").find(".error").first();
                        if (!errorField.length && inputField.next('.error').length) {
                            errorField = inputField.next('.error');
                        }

                        inputField.addClass("border-red-500");
                        errorField.html(Array.isArray(value) ? value[0] : value);
                    });

                    $("input, select").on("input change", function() {
                        $(this)
                            .removeClass("border-red-500")
                            .closest(".mb-4")
                            .find(".error")
                            .html("");
                    });
                }
            });
        </script>
    @endpush
</x-layouts.app>
