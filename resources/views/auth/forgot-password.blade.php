<x-layouts.auth title="Forgot Password | A.Z Group">
    <div class="w-full sm:max-w-md mt-6 px-6 py-4 bg-white shadow-md overflow-hidden sm:rounded-lg">
        <form method="POST" action="" id="form">
            @csrf

            <div class="py-6">
                <center>
                    <p class="text-2xl font-bold">Forgot Password</p><br>
                    <span class="text-sm text-gray-600"> Please enter your email address below. If an account exists for that address, we will send you a password reset link shortly.</span>
                </center>
            </div>

            {{-- ALert --}}
            <div id="main-alert" class="hidden p-4 mt-4 mb-4 text-semibold text-red-600 rounded-lg bg-blue-50" role="alert">
                <div class="flex justify-between items-center">
                     <span id="main-alert-msg"></span> 
                <i class="fa-regular fa-xmark-large text-gray-600 cursor-pointer"></i>
                </div>
            </div>

            <div class="mb-3">
                <label class="block font-medium text-sm text-gray-700" for="email" value="Email" />
                <input id="email" type='email' name='email' placeholder='Email'
                    class="w-full rounded-md py-2.5 px-4 border text-sm outline-[#99c041]" />
                    <span class="error text-red-500 text-xs mt-1 block"></span>
            </div>


            <div class="flex items-center justify-end mt-4">
                <a class="hover:underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                    href="{{ route('login') }}">
                    Back to Login
                </a>
                <button type="submit" id="submit-btn"
                    class ="ms-4 inline-flex items-center px-4 py-2 bg-[#99c041] border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-[#2b544a] focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                    <span class="btn-text">Send</span>
                    <span class="btn-loading hidden"><i class="fa-solid fa-spinner fa-spin ps-2"></i> Loading...</span>
                </button>

            </div>

        </form>
    </div>

    @push('scripts')
        <script src="{{ asset('assets/js/jquery-3.7.1.min.js') }}"></script>
        <script src="{{ asset('assets/js/pages/forgotPassword.js') }}"></script>
    @endpush
</x-layouts.auth>
