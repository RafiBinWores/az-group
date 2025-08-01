<!-- Uiverse.io Delete Confirmation Modal -->
<div id="deleteModal" style="display:none;" class="fixed inset-0 z-50 flex items-center justify-center bg-black/25">
  <div class="group select-none w-sm flex flex-col p-4 relative items-center justify-center bg-gray-800 border border-gray-800 shadow-lg rounded-2xl">
    <div class="">
      <div class="text-center p-3 flex-auto justify-center">
        <svg
          fill="currentColor"
          viewBox="0 0 20 20"
          class="group-hover:animate-bounce w-12 h-12 flex items-center text-gray-600 fill-red-500 mx-auto"
          xmlns="http://www.w3.org/2000/svg"
        >
          <path
            clip-rule="evenodd"
            d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z"
            fill-rule="evenodd"
          ></path>
        </svg>
        <h2 class="text-xl font-bold py-4 text-gray-200">Are you sure?</h2>
        <p class="font-bold text-sm text-gray-500 px-2">
          Do you really want to continue ? This process cannot be undone.
        </p>
      </div>
      <div class="p-2 mt-2 text-center space-x-1 md:block">
        <button id="delete-modal-cancel"
          class="mb-2 md:mb-0 bg-gray-700 px-5 py-2 text-sm cursor-pointer shadow-sm font-medium tracking-wider border-2 border-gray-600 hover:border-gray-700 text-gray-300 rounded-full hover:shadow-lg hover:bg-gray-800 transition ease-in duration-300"
        >
          Cancel
        </button>
        <button id="delete-modal-confirm"
          class="bg-red-500 hover:bg-transparent cursor-pointer px-5 ml-4 py-2 text-sm shadow-sm hover:shadow-lg font-medium tracking-wider border-2 border-red-500 hover:border-red-500 text-white hover:text-red-500 rounded-full transition ease-in duration-300"
        >
          Confirm
        </button>
      </div>
    </div>
  </div>
</div>
<script>
(function() {
    let modal = document.getElementById('deleteModal');
    let cancelBtn = document.getElementById('delete-modal-cancel');
    let confirmBtn = document.getElementById('delete-modal-confirm');
    let currentId = null;
    let onConfirmCallback = null;

    window.showDeleteModal = function({id, onConfirm}) {
        currentId = id;
        onConfirmCallback = typeof onConfirm === 'function' ? onConfirm : null;
        modal.style.display = 'flex';
    };
    window.hideDeleteModal = function() {
        modal.style.display = 'none';
        currentId = null;
        onConfirmCallback = null;
    };
    cancelBtn.addEventListener('click', function() {
        hideDeleteModal();
    });
    confirmBtn.addEventListener('click', function() {
        if (onConfirmCallback) {
            onConfirmCallback(currentId);
        } else {
            // fallback: dispatch event
            const event = new CustomEvent('confirm-delete', { detail: { id: currentId } });
            document.dispatchEvent(event);
        }
        hideDeleteModal();
    });
    // Hide modal on backdrop click
    modal.addEventListener('click', function(e) {
        if (e.target === modal) hideDeleteModal();
    });
})();
</script>
