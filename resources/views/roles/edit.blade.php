<x-layouts.app>
    {{-- Page title --}}
    <x-slot name="title">Edit role | AZ Group</x-slot>
    {{-- Page title end --}}

    {{-- Page header --}}
    <x-slot name="header">Edit Role</x-slot>
    {{-- Page header end --}}

    {{-- Notifications --}}
    <x-notification.notification-toast />

    {{-- Page Content --}}
    <div class="bg-white shadow-sm w-full rounded-lg mt-6">
        <div class="px-6 py-4 border-b border-gray-200 text-gray-700 font-semibold text-lg">
            Role Information
        </div>

        <div class="p-6 font-ibm">
            <form action="{{ route('roles.update', $role->id) }}" method="POST" id="form">
                @csrf
                @method('PUT')
                <div class="mb-4">
                    <label for="name" class="font-semibold">Role Name</label>
                    <input type="text" name="name" id="name" value="{{ $role->name }}"
                        placeholder="Enter role name"
                        class="w-full border mt-3 outline-[#99c041] border-gray-300 px-3 py-2  rounded-xl">
                    <span class="error text-red-500 text-xs mt-1 block"></span>
                </div>

                <div class="mb-4">
                    <label class="font-semibold mb-2 block">Assign Permissions</label>
                    <div class="flex flex-row items-center flex-wrap gap-6">
                        @foreach ($permissions as $permission)
                            <div class="flex flex-row flex-wrap items-center select-none gap-2">
                                <label class="text-slate-400">
                                    <input type="checkbox" value="{{ $permission->name }}" name="permissions[]"
                                        class="h-[1px] opacity-0 overflow-hidden absolute whitespace-nowrap w-[1px] peer"
                                        {{ $role->permissions->pluck('name')->contains($permission->name) ? 'checked' : '' }}>
                                    <span
                                        class="peer-checked:border-[#99c041] peer-checked:shadow-[#99c041]/10 peer-checked:text-[#99c041] peer-checked:before:border-[#99c041] peer-checked:before:bg-[#99c041] peer-checked:before:opacity-100 peer-checked:before:scale-100 peer-checked:before:content-['✓'] flex flex-col items-center justify-center w-28 min-h-[80px] rounded-lg shadow-lg transition-all duration-200 cursor-pointer relative border-slate-300 border-[3px] bg-white before:absolute before:w-5 before:h-5 before:border-[3px]  before:rounded-full before:top-1 before:left-1 before:opacity-0 before:transition-transform before:scale-0 before:text-white before:text-xs before:flex before:items-center before:justify-center hover:border-[#99c041] hover:before:scale-100 hover:before:opacity-100">
                                        <span
                                            class="transition-all duration-300 text-center text-sm">{{ $permission->name }}</span>
                                    </span>
                                </label>
                            </div>
                        @endforeach
                    </div>
                    <span class="error-permissions error text-red-500 text-xs mt-1 block"></span>
                </div>

                <div class="flex items-center gap-4 mt-5">
                    <a href="{{ route('roles.index') }}"
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
        <script src="{{ asset('assets/js/jquery-3.7.1.min.js') }}"></script>
        <script>
            $(function() {
                $("form").on("submit", function(event) {
                    event.preventDefault();
                    let form = $(this);
                    let formData = new FormData(this);
                    $('button[type="submit"]').prop("disabled", true);

                    $.ajax({
                        url: form.attr('action'),
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
                                    response.message || "Role updated successfully"
                                );
                                // setTimeout(function() { window.location.href = "{{ route('roles.index') }}"; }, 1200);
                            } else {
                                // Show backend error message if present
                                if (response.message) {
                                    showToast("warning", response.message);
                                }
                                let errors = response.errors;

                                // Remove all previous error highlights and messages
                                $(".error").html("");
                                $("input, select").removeClass(
                                    "border-red-500 ring-2 ring-red-400"
                                );

                                let permissionsErrorShown = false;
                                $.each(errors, function(key, value) {
                                    value = Array.isArray(value) ? value[0] : value;
                                    if (
                                        key === "permissions" ||
                                        key.startsWith("permissions.")
                                    ) {
                                        if (!permissionsErrorShown) {
                                            $(".error-permissions").html(value);
                                            $("input[name='permissions[]']").addClass(
                                                "ring-2 ring-red-400 border-red-500"
                                            );
                                            permissionsErrorShown = true;
                                        }
                                    } else {
                                        let inputField = $(`[name='${key}']`);
                                        let errorField = inputField
                                            .closest(".mb-4")
                                            .find(".error")
                                            .first();
                                        inputField.addClass(
                                            "border-red-500 ring-2 ring-red-400"
                                        );
                                        errorField.html(value);
                                    }
                                });

                                // Remove error classes/messages when user types/selects again
                                $("input, select").on("input change", function() {
                                    $(this)
                                        .removeClass("border-red-500 ring-2 ring-red-400")
                                        .closest(".mb-4")
                                        .find(".error")
                                        .html("");
                                    $(".error-permissions").html("");
                                    $("input[name='permissions[]']").removeClass(
                                        "ring-2 ring-red-400 border-red-500"
                                    );
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
        {{-- <script src="{{ asset('assets/js/pages/roles/roleEdit.js') }}"></script> --}}
    @endpush

</x-layouts.app>
