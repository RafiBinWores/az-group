const passwordInput = document.getElementById("password");
const togglePasswordButton = document.getElementById("togglePassword");
const togglePasswordIcon = document.getElementById("togglePasswordIcon");

const eyeIcon = `<i class="fa-regular fa-eye text-xl"></i>`;
const eyeOffIcon = `<i class="fa-regular fa-eye-slash text-xl"></i>`;

togglePasswordButton.addEventListener("click", () => {
    const isPassword = passwordInput.getAttribute("type") === "password";
    passwordInput.setAttribute("type", isPassword ? "text" : "password");
    togglePasswordIcon.innerHTML = isPassword ? eyeIcon : eyeOffIcon;
});
