    <!-- Global Message Modal (Floating Modal) -->
    <div class="modal-overlay" id="messageModal">
        <div class="modal-content max-w-sm text-center floating-modal">
            <div id="messageIcon" class="w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
                <i class="fa-solid fa-info-circle text-2xl"></i>
            </div>
            <h3 class="text-xl font-bold mb-2 dark:text-white" id="messageTitle">Notice</h3>
            <p class="text-gray-500 dark:text-gray-400 mb-6" id="messageBody"></p>
            <button type="button" onclick="closeModal('messageModal')" class="btn btn-primary w-full">OK</button>
        </div>
    </div>

    <!-- Global Confirmation Modal -->
    <div class="modal-overlay" id="confirmModal">
        <div class="modal-content max-w-sm text-center">
            <div class="w-16 h-16 bg-red-100 dark:bg-red-900/30 text-red-600 rounded-full flex items-center justify-center mx-auto mb-4">
                <i class="fa-solid fa-triangle-exclamation text-2xl"></i>
            </div>
            <h3 class="text-xl font-bold mb-2 dark:text-white" id="confirmTitle">Confirm Action</h3>
            <p class="text-gray-500 dark:text-gray-400 mb-6" id="confirmMessage">Are you sure you want to proceed? This action may be irreversible.</p>
            <div class="flex gap-3">
                <button type="button" id="confirmCancel" class="btn btn-outline flex-1">Cancel</button>
                <button type="button" id="confirmProceed" class="btn btn-danger flex-1">Proceed</button>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script src="<?php echo BASE_URL; ?>assets/js/main.js"></script>

    <script>
        // Initialize AOS
        AOS.init({
            duration: 800,
            once: true,
            offset: 50,
            easing: 'ease-out-cubic'
        });

        // Add slide out animation for toasts
        const style = document.createElement('style');
        style.textContent = `
            @keyframes slideOut {
                from { opacity: 1; transform: translateX(0); }
                to { opacity: 0; transform: translateX(100%); }
            }
        `;
        document.head.appendChild(style);
    </script>
    </body>

    </html>