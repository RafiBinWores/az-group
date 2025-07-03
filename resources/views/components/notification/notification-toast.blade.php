<div id="toast"
    class="fixed top-5 right-5 z-50 hidden flex flex-col items-start p-0 rounded-2xl shadow-lg min-w-[240px] max-w-xs animate-fade-in overflow-hidden">

    <div id="toast-content" class="flex items-center p-4 gap-3 w-full bg-green-500 text-white">
        <span id="toast-icon" class="w-6 h-6 flex-shrink-0"></span>
        <span id="toast-message" class="flex-1 font-semibold">Message</span>
        <button onclick="closeToast()" class="ml-2 focus:outline-none cursor-pointer">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
            </svg>
        </button>
    </div>
    <div class="relative w-full h-1 bg-green-700 bg-opacity-25">
        <div id="toast-progress-bar" class="absolute left-0 top-0 h-full bg-white bg-opacity-90 progress-bar-animate"></div>
    </div>
</div>
