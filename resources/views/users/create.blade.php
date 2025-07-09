<x-layouts.app>
    {{-- Page title --}}
    <x-slot name="title">Create user | AZ Group</x-slot>
    {{-- Page title end --}}

    {{-- Page header --}}
    <x-slot name="header">Create user</x-slot>
    {{-- Page header end --}}

    {{-- Notifications --}}
    <x-notification.notification-toast />

    {{-- Page Content --}}
    <div class="bg-white shadow-sm w-full rounded-lg mt-4">
        <div class="px-6 py-4 border-b border-gray-200 text-gray-700 font-semibold text-lg">
            User Information
        </div>

        <div class="p-6 font-ibm">
            <form action="{{ route('users.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <!-- Avatar Upload (Drop Zone) -->
                <div class="mb-4">
                    <label class="font-semibold block mb-2">Profile Picture</label>
                    <div class="w-full flex items-center gap-6 p-6 bg-white rounded-2xl border border-gray-200">
                        <!-- Profile picture (preview) -->
                        <div id="photo-preview"
                            class="flex items-center justify-center rounded-full border-2 border-dashed border-gray-300 w-20 h-20 relative">
                            <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <circle cx="12" cy="8" r="4" stroke-width="1" />
                                <path d="M4 20c0-4 4-7 8-7s8 3 8 7" stroke-width="1" />
                            </svg>
                            <input id="avatar-input" type="file" name="avatar"
                                class="absolute inset-0 opacity-0 cursor-pointer z-10" />
                        </div>

                        <!-- Upload + Delete buttons -->
                        <div class="flex gap-4">
                            <button type="button" id="upload-btn"
                                class="flex items-center px-6 py-2 rounded-lg bg-[#99c041] text-white font-medium shadow hover:bg-[#99c041] focus:outline-none">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" stroke-width="2"
                                    viewBox="0 0 24 24">
                                    <path d="M12 16V4M8 8l4-4 4 4" stroke-linecap="round" stroke-linejoin="round" />
                                    <path d="M20 16v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2" stroke-linecap="round"
                                        stroke-linejoin="round" />
                                </svg>
                                Upload photo
                            </button>
                            <button type="button" id="delete-photo-btn"
                                class="px-6 py-2 rounded-lg border border-gray-300 text-gray-500 hover:bg-gray-100 focus:outline-none">
                                Delete
                            </button>
                        </div>
                    </div>
                    <span class="error text-red-500 text-xs mt-1 block"></span>
                </div>

                <!-- Name -->
                <div class="mb-4">
                    <label for="name" class="font-semibold">User Name</label>
                    <input type="text" name="name" id="name" placeholder="User Name"
                        class="w-full border mt-3 outline-[#99c041] border-gray-300 px-3 py-2 rounded-xl">
                    <span class="error text-red-500 text-xs mt-1 block"></span>
                </div>
                <!-- Email -->
                <div class="mb-4">
                    <label for="email" class="font-semibold">Email Address</label>
                    <input type="email" name="email" id="email" placeholder="Email Address"
                        class="w-full border mt-3 outline-[#99c041] border-gray-300 px-3 py-2 rounded-xl">
                    <span class="error text-red-500 text-xs mt-1 block"></span>
                </div>

                <!-- Role -->
                <div class="mb-4">
                    <label class="font-semibold">Assign Role</label>
                    <select name="role" id="role-select"
                        class="w-full border mt-3 outline-[#99c041] border-gray-300 px-3 py-2 rounded-xl focus:ring-2 focus:ring-[#99c041] focus:border-[#99c041] transition">
                        <option value="" class="text-gray-300">Select a role</option>
                        @foreach ($roles as $role)
                            <option value="{{ $role->name }}">{{ $role->name }}</option>
                        @endforeach
                    </select>
                    <span class="error text-red-500 text-xs mt-1 block"></span>
                </div>

                <!-- Password -->
                <div class="mb-4">
                    <label class="font-semibold">Password</label>
                    <div class="relative">
                        <input id="password" type="password" name="password" placeholder="Password"
                            class="w-full border mt-3 outline-[#99c041] border-gray-300 px-3 py-2 rounded-xl">
                        <div class="absolute inset-y-0 right-0 top-3 pr-3 flex items-center text-sm leading-5">
                            <button type="button" id="togglePassword"
                                class="text-gray-500 focus:outline-none focus:text-gray-600 cursor-pointer hover:text-gray-600">
                                <span id="togglePasswordIcon">
                                    <i class="fa-regular fa-eye-slash text-xl"></i>
                            </button>
                        </div>
                    </div>
                    <span class="error text-red-500 text-xs mt-1 block"></span>
                </div>

                <!-- Buttons -->
                <div class="flex items-center gap-4 mt-5">
                    <a href="{{ route('users.index') }}"
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
