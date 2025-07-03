window.toastTypes = {
    success: {
        bg: "bg-green-500 text-white",
        bar: "bg-green-700 bg-opacity-25",
        icon: `<svg fill="none" stroke="currentColor" viewBox="0 0 24 24" class="w-6 h-6"><circle cx="12" cy="12" r="10" stroke-width="2"/><path stroke-width="2" d="M9 12l2 2l4-4"/></svg>`,
    },
    warning: {
        bg: "bg-yellow-500 text-white",
        bar: "bg-yellow-700 bg-opacity-30",
        icon: `<svg fill="none" viewBox="0 0 24 24" stroke="currentColor" class="w-6 h-6">
          <path stroke="currentColor" stroke-width="2" d="M12 9v3m0 4h.01"/>
          <path stroke="currentColor" stroke-width="2" d="M10.29 3.86l-7.42 12.84A1.7 1.7 0 004.18 19h15.64a1.7 1.7 0 001.31-2.3L13.82 3.86a1.7 1.7 0 00-2.94 0z"/>
        </svg>`,
    },
    danger: {
        bg: "bg-red-600 text-white",
        bar: "bg-red-800 bg-opacity-30",
        icon: `<svg fill="none" stroke="currentColor" viewBox="0 0 24 24" class="w-6 h-6"><circle cx="12" cy="12" r="10" stroke-width="2"/><path stroke-width="2" d="M15 9l-6 6m0-6l6 6"/></svg>`,
    },
};

window.showToast = function (
    type = "success",
    message = "Success! Your action was completed."
) {
    const toast = document.getElementById("toast");
    const toastContent = document.getElementById("toast-content");
    const toastMessage = document.getElementById("toast-message");
    const toastIcon = document.getElementById("toast-icon");
    const progressBar = document.getElementById("toast-progress-bar");
    const toastBar = toast.querySelector(".relative");

    // Set styles and icon
    toastContent.className = `flex items-center p-4 gap-3 w-full ${toastTypes[type].bg}`;
    toastBar.className = `relative w-full h-1 ${toastTypes[type].bar}`;
    toastIcon.innerHTML = toastTypes[type].icon;
    toastMessage.textContent = message;

    // Show and animate
    toast.classList.remove("hidden");
    toast.classList.add("animate-fade-in");
    progressBar.classList.remove("progress-bar-animate");
    void progressBar.offsetWidth;
    progressBar.classList.add("progress-bar-animate");

    // Auto-hide after 3 seconds
    clearTimeout(window.toastTimeout);
    window.toastTimeout = setTimeout(() => {
        closeToast();
    }, 3000);
};

window.closeToast = function () {
    const toast = document.getElementById("toast");
    const progressBar = document.getElementById("toast-progress-bar");
    toast.classList.add("hidden");
    toast.classList.remove("animate-fade-in");
    progressBar.classList.remove("progress-bar-animate");
    clearTimeout(window.toastTimeout);
};
