<x-layouts.auth title="Login | A.Z Group">
    <div class="w-full sm:max-w-md mt-6 px-6 py-4 bg-white shadow-md overflow-hidden sm:rounded-lg">
        <form method="POST" action="" id="form">
            @csrf

            <div class="py-6">
                <center>
                    <span class="text-2xl font-bold">Welcome Back</span>
                </center>
            </div>

            <div id="main-alert" class="hidden p-4 mt-4 mb-4 text-sm text-red-600 rounded-lg bg-blue-50" role="alert">
                <span id="main-alert-msg"></span> 
            </div>

            <div class="mb-3">
                <label class="block font-medium text-sm text-gray-700" for="email" value="Email" />
                <input id="email" type='email' name='email' placeholder='Email'
                    class="w-full rounded-md py-2.5 px-4 border text-sm outline-[#99c041]" />
                <span class="error text-red-500 text-xs mt-1 block"></span>
            </div>


            <div class="mb-3 mt-4">
                <label class="block font-medium text-sm text-gray-700" for="password" value="Password" />
                <div class="relative">
                    <input id="password" type="password" name="password" placeholder="Password"
                        autocomplete="current-password"
                        class = 'w-full rounded-md py-2.5 px-4 border text-sm outline-[#99c041]'>
                    <div class="absolute inset-y-0 right-0 pr-3 flex items-center text-sm leading-5">
                        <button type="button" id="togglePassword"
                            class="text-gray-500 focus:outline-none focus:text-gray-600 hover:text-gray-600">
                            <span id="togglePasswordIcon">
                                <i class="fa-regular fa-eye-slash text-xl"></i>
                        </button>
                    </div>
                </div>
                <span class="error text-red-500 text-xs mt-1 block"></span>
            </div>

            <div class="block mt-4">
                <label for="remember_me" class="flex items-center">
                    <input type="checkbox" id="remember_me" name="remember"
                        class = "rounded border-gray-300 text-[#99c041] shadow-sm focus:ring-[#99c041]">
                    <span class="ms-2 text-sm text-gray-600">Remember Me</span>
                </label>
            </div>

            <div class="flex items-center justify-end mt-4">
                <a class="hover:underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                    href="{{ route('password.request') }}">
                    Forgot your password?
                </a>

                <button type="submit"
                    class ="ms-4 inline-flex items-center px-4 py-2 bg-[#99c041] border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-[#2b544a] focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                    Sign In
                </button>

            </div>

        </form>
    </div>

    @push('scripts')
        <script src="{{ asset('assets/js/jquery-3.7.1.min.js') }}"></script>
        <script src="{{ asset('assets/js/pages/login.js') }}"></script>
    @endpush
</x-layouts.auth>
