<div class="modal-overlay" id="confirmModal" style="display:none;">
    <div class="modal" style="max-width:400px;">
        <div class="modal-header">
            <h3>Confirm Action</h3>
            <button type="button" class="modal-close" onclick="closeModal('confirmModal')">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>
        <div class="modal-body" style="padding:1rem;">
            <p id="confirmMessage" style="color:var(--gray-dark);margin-bottom:1.5rem;"></p>
            <div class="flex-between">
                <button type="button" class="btn btn-outline" onclick="closeModal('confirmModal')">Cancel</button>
                <button type="button" class="btn btn-danger" id="confirmDeleteBtn" onclick="confirmAction()">
                    <i class="fa-solid fa-trash"></i> Delete
                </button>
            </div>
        </div>
    </div>
</div>
