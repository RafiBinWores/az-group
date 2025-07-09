document.addEventListener("DOMContentLoaded", function () {
    const photoPreview = document.getElementById("photo-preview");
    const fileInput = document.getElementById("avatar-input");
    const uploadBtn = document.getElementById("upload-btn");
    const deleteBtn = document.getElementById("delete-photo-btn");
    const errorMsg = document.querySelector(".error");

    // Open file picker when Upload Photo is clicked
    uploadBtn.onclick = () => fileInput.click();

    // When file selected, show preview (replace SVG/img)
    fileInput.onchange = function () {
        if (!this.files.length) return;
        const file = this.files[0];
        errorMsg.textContent = "";

        if (!file.type.startsWith("image/")) {
            errorMsg.textContent = "Please select a valid image file.";
            this.value = "";
            return;
        }
        if (file.size > 5 * 1024 * 1024) {
            errorMsg.textContent = "Image size must be less than 5MB.";
            this.value = "";
            return;
        }

        const url = URL.createObjectURL(file);
        let img = photoPreview.querySelector("img");
        let svg = photoPreview.querySelector("svg");
        if (!img) {
            img = document.createElement("img");
            img.className = "w-16 h-16 object-cover rounded-full";
            photoPreview.appendChild(img);
        }
        img.src = url;
        img.alt = "Preview";
        if (svg) svg.style.display = "none";
    };

    // Delete action (remove preview and clear file input)
    deleteBtn.onclick = function () {
        const img = photoPreview.querySelector("img");
        const svg = photoPreview.querySelector("svg");
        if (img) img.remove();
        if (svg) svg.style.display = "";
        fileInput.value = "";
        // Optional: set a hidden input if you want to remove avatar from DB on backend
        let removeInput = document.getElementById("remove_avatar_flag");
        if (!removeInput) {
            removeInput = document.createElement("input");
            removeInput.type = "hidden";
            removeInput.name = "remove_avatar";
            removeInput.id = "remove_avatar_flag";
            removeInput.value = "1";
            document.querySelector("form").appendChild(removeInput);
        }
    };
});
