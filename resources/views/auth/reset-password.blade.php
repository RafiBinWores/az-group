<x-layouts.auth title="Reset Password | A.Z Group">
    <div class="w-full sm:max-w-md mt-6 px-6 py-4 bg-white shadow-md overflow-hidden sm:rounded-lg">
        <form method="POST" action="" id="form">
            @csrf

            <div class="py-6">
                <center>
                    <span class="text-2xl font-bold">Reset Password</span>
                </center>
            </div>

            {{-- Alert --}}
            <div id="main-alert" class="hidden p-4 mt-4 mb-4 text-sm font-medium text-red-600 rounded-lg bg-blue-50" role="alert">
                <span id="main-alert-msg"></span>
            </div>

            <input type="hidden" name="token" value="{{ $token }}">
            <input type="hidden" name="email" value="{{ $email }}">

            <div class="mb-3 mt-4">
                <label class="block font-medium text-sm text-gray-700" for="password" value="Password" />
                <div class="relative ">
                    <input id="password" type="password" name="password" placeholder="Password"
                        autocomplete="current-password"
                        class = 'w-full rounded-md py-2.5 px-4 border text-sm outline-[#99c041]'>
                    <div class="absolute inset-y-0 right-0 pr-3 flex items-center text-sm leading-5">
                        <button type="button"
                            class="toggle-password text-gray-500 focus:outline-none focus:text-gray-600 hover:text-gray-600"
                            data-target="password">
                            <span class="toggle-password-icon">
                                <i class="fa-regular fa-eye-slash text-xl"></i>
                            </span>
                        </button>
                    </div>
                </div>
                <span class="error text-red-500 text-xs mt-1 block"></span>
            </div>
            <div class="mb-3 mt-4">
                <label class="block font-medium text-sm text-gray-700" for="confirm_password"
                    value="Confirm Password" />
                <div class="relative">
                    <input id="password_confirmation" type="password" name="password_confirmation"
                        placeholder="Confirm Password" autocomplete="current-password"
                        class = 'w-full rounded-md py-2.5 px-4 border text-sm outline-[#99c041]'>
                    <div class="absolute inset-y-0 right-0 pr-3 flex items-center text-sm leading-5">
                        <button type="button"
                            class="toggle-password text-gray-500 focus:outline-none focus:text-gray-600 hover:text-gray-600"
                            data-target="password_confirmation">
                            <span class="toggle-password-icon">
                                <i class="fa-regular fa-eye-slash text-xl"></i>
                            </span>
                        </button>
                    </div>
                </div>
                <span class="error text-red-500 text-xs mt-1 block"></span>
            </div>
            <div class="flex items-center justify-end mt-4">

                <button type="submit"
                    class ="ms-4 inline-flex items-center px-4 py-2 bg-[#99c041] border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-[#2b544a] focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                    Reset
                </button>

            </div>

        </form>
    </div>

    @push('scripts')
        <script src="{{ asset('assets/js/jquery-3.7.1.min.js') }}"></script>
        <script src="{{ asset('assets/js/pages/resetPassword.js') }}"></script>
    @endpush
</x-layouts.auth>
