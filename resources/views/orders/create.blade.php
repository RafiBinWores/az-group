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
            <form action="{{ route('orders.store') }}" method="POST" enctype="multipart/form-data">
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
                    <label for="order_quantity" class="font-semibold">Order Quantity</label>
                    <input type="order_quantity" name="order_quantity" id="order_quantity" placeholder="Order Quantity"
                        class="w-full border mt-3 outline-[#99c041] border-gray-300 px-3 py-2 rounded-xl">
                    <span class="error text-red-500 text-xs mt-1 block"></span>
                </div>
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
