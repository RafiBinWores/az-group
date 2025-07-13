<x-layouts.app>
    {{-- Page title --}}
    <x-slot name="title">Create cutting | AZ Group</x-slot>
    {{-- Page title end --}}

    {{-- Page header --}}
    <x-slot name="header">Create cutting</x-slot>
    {{-- Page header end --}}

    {{-- Notifications --}}
    <x-notification.notification-toast />

    {{-- Page Content --}}
    <div class="bg-white shadow-sm w-full rounded-lg mt-4">
        <div class="px-6 py-4 border-b border-gray-200 text-gray-700 font-semibold text-lg">
            Cutting Information
        </div>

        <div class="p-6 font-ibm">
            <form action="{{ route('cutting.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="mb-4">
                    <label class="font-semibold">Style No</label>
                    <select name="order_id" id="style-select"
                        class="w-full border mt-3 outline-[#99c041] border-gray-300 px-3 py-2 rounded-xl focus:ring-[#99c041] focus:border-[#99c041] transition">
                        <option value="" class="text-gray-300">Select a style</option>
                        @foreach ($orders as $order)
                            <option value="{{ $order->id }}">{{ $order->style_no }}</option>
                        @endforeach
                    </select>
                    <span class="error text-red-500 text-xs mt-1 block"></span>
                </div>

                {{-- Cutting Report --}}
                <div id="cutting-fields" class="space-y-2 mb-4">
                    <label for="style_no" class="font-semibold">Cutting</label>
                    <div class="flex gap-2 items-center">
                        <input type="text" name="cutting[0][color]" placeholder="Color"
                            class="border border-gray-300 outline-[#99c041] rounded-xl px-3 py-2 w-2/3" />
                        <input type="number" min="0" name="cutting[0][qty]" placeholder="Quantity"
                            class="border border-gray-300 outline-[#99c041] rounded-xl px-3 py-2 w-1/3" />
                        <button type="button"
                            class="remove-row bg-red-500 text-white rounded-xl size-10 px-2 py-1 ml-2 hidden">
                            &times;
                        </button>
                    </div>
                    <span class="error text-red-500 text-xs mt-1 block"></span>
                </div>
                <button type="button" id="add-cutting-row"
                    class="mt-2 bg-blue-600 text-white px-4 py-2 rounded-xl shadow cursor-pointer">
                    + Add
                </button>

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
            // for add multiple color based quantity under one order
            let cuttingIndex = 1;
            document.getElementById('add-cutting-row').addEventListener('click', function() {
                const fields = document.getElementById('cutting-fields');
                const div = document.createElement('div');
                div.className = 'flex gap-2 items-center';

                div.innerHTML = `
        <input type="text" name="cutting[${cuttingIndex}][color]" placeholder="Color"
            class="border border-gray-300 rounded-xl px-3 py-2 w-2/3 focus:outline-none focus:ring-2 focus:ring-blue-300" />
        <input type="number" min="0" name="cutting[${cuttingIndex}][qty]" placeholder="Quantity"
            class="border border-gray-300 rounded-xl px-3 py-2 w-1/3 focus:outline-none focus:ring-2 focus:ring-blue-300" />
        <button type="button" class="remove-row bg-red-500 text-white rounded-xl px-2 py-1 ml-2 size-10 cursor-pointer">
            &times;
        </button>
    `;
                fields.appendChild(div);
                cuttingIndex++;
                updateRemoveButtons();
            });

            // Remove row functionality
            document.getElementById('cutting-fields').addEventListener('click', function(e) {
                if (e.target.classList.contains('remove-row')) {
                    e.target.parentNode.remove();
                    updateRemoveButtons();
                }
            });

            // Hide remove button if only 1 row left
            function updateRemoveButtons() {
                const rows = document.querySelectorAll('#cutting-fields .flex');
                rows.forEach((row, idx) => {
                    const btn = row.querySelector('.remove-row');
                    btn.classList.toggle('hidden', rows.length === 1);
                });
            }

            // Initialize remove button visibility on page load
            updateRemoveButtons();

            // FOr submit the form
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
                                    response.message || "User created successfully"
                                );
                                // Optionally redirect after success:
                                // setTimeout(function() { window.location.href = "{{ route('users.index') }}"; }, 1200);
                            } else {
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
