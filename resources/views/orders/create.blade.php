<x-layouts.app>
    {{-- Page title --}}
    <x-slot name="title">Create order | AZ Group</x-slot>
    {{-- Page title end --}}

    {{-- Page header --}}
    <x-slot name="header">Create order</x-slot>
    {{-- Page header end --}}

    {{-- Notifications --}}
    <x-notification.notification-toast />

    {{-- Page Content --}}
    <div class="bg-white shadow-sm w-full rounded-lg mt-4">
        <div class="px-6 py-4 border-b border-gray-200 text-gray-700 font-semibold text-lg">
            Order Information
        </div>

        <div class="p-6 font-ibm">
            <form action="{{ route('orders.store') }}" id="form" method="POST" enctype="multipart/form-data">
                @csrf

                <!-- Name -->
                <div class="mb-4">
                    <label for="buyer_name" class="font-semibold">Buyer Name</label>
                    <input type="text" name="buyer_name" id="buyer_name" placeholder="Buyer Name"
                        class="w-full border mt-3 outline-[#99c041] border-gray-300 px-3 py-2 rounded-xl">
                    <span class="error text-red-500 text-xs mt-1 block"></span>
                </div>
                <div class="mb-4">
                    <label for="style_no" class="font-semibold">Style No</label>
                    <input type="text" name="style_no" id="style_no" placeholder="e.g. 1-KA-5123"
                        class="w-full border mt-3 outline-[#99c041] border-gray-300 px-3 py-2 rounded-xl">
                    <span class="error text-red-500 text-xs mt-1 block"></span>
                </div>

                <div class="mb-4">
                    <label for="state" class="font-semibold">Garment Types</label>
                    <select id="style-select"  class="w-full border mt-3 outline-[#99c041] border-gray-300 px-3 py-2 rounded-xl focus:ring-[#99c041] focus:border-[#99c041] transition" name="garment_types[]" multiple placeholder="Select a state..." 
                        autocomplete="off">
                        <option value="">Select a state...</option>
                        @foreach ($types as $type)
                            <option value="{{ $type->id }}">{{ $type->name }}</option>
                        @endforeach                  
                    </select>
                    <span class="error text-red-500 text-xs mt-1 block"></span>
                </div>


                <div class="mb-4">
                    <label for="order_quantity" class="font-semibold">Total Quantity</label>
                    <input type="order_quantity" name="order_quantity" id="order_quantity" placeholder="Total Quantity"
                        class="w-full border mt-3 outline-[#99c041] border-gray-300 px-3 py-2 rounded-xl">
                    <span class="error text-red-500 text-xs mt-1 block"></span>
                </div>
                <div id="add-fields" class="space-y-2 mb-4">
                    <label class="font-semibold">Color Wise Quantity</label>
                    <div class="flex gap-2 items-center">
                        <input type="text" name="color_qty[0][color]" placeholder="Color"
                            class="border border-gray-300 outline-[#99c041] rounded-xl px-3 py-2 w-2/3" />
                        <input type="number" min="0" name="color_qty[0][qty]" placeholder="Quantity"
                            class="border border-gray-300 outline-[#99c041] rounded-xl px-3 py-2 w-1/3" />
                        <button type="button"
                            class="remove-row bg-red-500 text-white rounded-xl size-10 px-2 py-1 ml-2 hidden">
                            &times;
                        </button>
                    </div>
                    <span class="error text-red-500 text-xs mt-1 block"></span>
                </div>
                <button type="button" id="add-row"
                    class="mt-2 bg-blue-600 text-white px-4 py-2 rounded-xl shadow cursor-pointer">
                    + Add
                </button>
                <input type="number" name="user_id" value="{{ auth()->user()->id }}" hidden>

                <!-- Buttons -->
                <div class="flex items-center gap-4 mt-5">
                    <a href="{{ route('orders.index') }}"
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
           document.addEventListener('DOMContentLoaded', function () {
            new TomSelect("#style-select", {
                plugins: ['remove_button'],
                maxItems: null,
                placeholder: 'Select garment types...',
                render: {
                    option: function(data, escape) {
                        return `<div class="px-3 py-2 hover:bg-[#99c041] hover:text-white rounded transition">${escape(data.text)}</div>`;
                    },
                    item: function(data, escape) {
                        return `<div class="inline-flex items-center bg-[#99c041] text-white rounded px-2 py-1 mr-2 mb-1">${escape(data.text)}</div>`;
                    }
                }
            });
        });
        </script>
        <script>
            let Index = 1; // Because initial is 0

            document.getElementById('add-row').addEventListener('click', function() {
                const fields = document.getElementById('add-fields');
                const div = document.createElement('div');
                div.className = 'flex gap-2 items-center';

                div.innerHTML = `
        <input type="text" name="color_qty[${Index}][color]" placeholder="Color"
            class="border border-gray-300 outline-[#99c041] rounded-xl px-3 py-2 w-2/3" />
        <input type="number" min="0" name="color_qty[${Index}][qty]" placeholder="Quantity"
            class="border border-gray-300 outline-[#99c041] rounded-xl px-3 py-2 w-1/3" />
        <button type="button" class="remove-row bg-red-500 text-white rounded-xl px-2 py-1 ml-2 size-10 cursor-pointer">
            &times;
        </button>
    `;
                fields.appendChild(div);
                Index++;
                updateRemoveButtons();
            });

            document.getElementById('add-fields').addEventListener('click', function(e) {
                if (e.target.classList.contains('remove-row')) {
                    e.target.parentNode.remove();
                    updateRemoveButtons();
                }
            });

            function updateRemoveButtons() {
                const rows = document.querySelectorAll('#add-fields .flex');
                rows.forEach((row, idx) => {
                    const btn = row.querySelector('.remove-row');
                    btn.classList.toggle('hidden', rows.length === 1);
                });
            }

            // Initialize on page load
            updateRemoveButtons();

            // For submitting form
            $(function() {
                $("#form").on("submit", function(event) {
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
                                    response.message || "Order created successfully"
                                );
                                // Optionally redirect
                                // setTimeout(function() { window.location.href = "{{ route('orders.index') }}"; }, 1200);
                            } else {
                                let errors = response.errors || {};

                                // Clear previous errors
                                $(".error").html("");
                                $("input, select").removeClass("border-red-500");

                                $.each(errors, function(key, value) {
                                    value = Array.isArray(value) ? value[0] : value;

                                    // handle array fields like garment_types and garment_types.0
                                    let baseKey = key.split('.')[0];
                                    let inputField = $(
                                        `[name='${baseKey}'], [name='${baseKey}[]']`);
                                    let errorField = inputField.closest(".mb-4").find(
                                        ".error").first();

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
