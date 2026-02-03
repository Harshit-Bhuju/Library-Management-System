/* ======================================
   LIBRARY MANAGEMENT SYSTEM - MAIN JS
   Interactive Features & Utilities
   ====================================== */

// ======================================
// THEME MANAGEMENT
// ======================================
const ThemeManager = {
    init() {
        // Load saved theme or default to light
        const savedTheme = localStorage.getItem('theme') || 'light';
        this.setTheme(savedTheme);

        // Listen for theme toggle clicks
        document.addEventListener('click', (e) => {
            if (e.target.closest('.theme-toggle')) {
                this.toggle();
            }
        });
    },

    setTheme(theme) {
        document.documentElement.setAttribute('data-theme', theme);
        localStorage.setItem('theme', theme);

        // Update toggle icon if exists
        const icon = document.querySelector('.theme-toggle i');
        if (icon) {
            icon.className = theme === 'dark' ? 'fa-solid fa-sun' : 'fa-solid fa-moon';
        }
    },

    toggle() {
        const current = document.documentElement.getAttribute('data-theme');
        const newTheme = current === 'dark' ? 'light' : 'dark';
        this.setTheme(newTheme);
    },

    get() {
        return document.documentElement.getAttribute('data-theme') || 'light';
    }
};

// ======================================
// MODAL MANAGEMENT
// ======================================
const ModalManager = {
    open(modalId) {
        const modal = document.getElementById(modalId);
        if (modal) {
            modal.style.display = 'flex';
            modal.classList.add('active');
            document.body.style.overflow = 'hidden';
        }
    },

    close(modalId) {
        const modal = document.getElementById(modalId);
        if (modal) {
            modal.style.display = 'none';
            modal.classList.remove('active');
            document.body.style.overflow = '';
        }
    },

    init() {
        // Close modal on overlay click
        document.addEventListener('click', (e) => {
            if (e.target.classList.contains('modal-overlay')) {
                e.target.style.display = 'none';
                e.target.classList.remove('active');
                document.body.style.overflow = '';
            }
        });

        // Close modal on escape key
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') {
                document.querySelectorAll('.modal-overlay.active').forEach(modal => {
                    modal.style.display = 'none';
                    modal.classList.remove('active');
                });
                document.body.style.overflow = '';
            }
        });
    }
};

// Global functions for backwards compatibility
function openModal(id) { ModalManager.open(id); }
function closeModal(id) { ModalManager.close(id); }

// ======================================
// TOAST NOTIFICATIONS
// ======================================
const Toast = {
    show(message, type = 'info', title = null) {
        const modal = document.getElementById('messageModal');
        const iconContainer = document.getElementById('messageIcon');
        const titleEl = document.getElementById('messageTitle');
        const bodyEl = document.getElementById('messageBody');

        if (!modal) {
            console.warn('Message modal not found, falling back to alert');
            alert(message);
            return;
        }

        // Set Icon and Title based on type
        let icon = 'fa-info-circle';
        let defaultTitle = 'Notice';
        let themeClass = 'icon-info';

        switch (type) {
            case 'success':
                icon = 'fa-check-circle';
                defaultTitle = 'Success!';
                themeClass = 'icon-success';
                break;
            case 'error':
                icon = 'fa-circle-xmark';
                defaultTitle = 'Error';
                themeClass = 'icon-error';
                break;
            case 'warning':
                icon = 'fa-triangle-exclamation';
                defaultTitle = 'Warning';
                themeClass = 'icon-warning';
                break;
        }

        if (iconContainer) {
            iconContainer.className = `w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4 ${themeClass}`;
            iconContainer.innerHTML = `<i class="fa-solid ${icon} text-2xl"></i>`;
        }
        if (titleEl) titleEl.textContent = title || defaultTitle;
        if (bodyEl) bodyEl.textContent = message;

        openModal('messageModal');

        // Auto-close success messages after 3 seconds
        if (type === 'success') {
            setTimeout(() => {
                if (modal.classList.contains('active')) {
                    closeModal('messageModal');
                }
            }, 3000);
        }
    },
    success(message, title) { this.show(message, 'success', title); },
    error(message, title) { this.show(message, 'error', title); },
    warning(message, title) { this.show(message, 'warning', title); },
    info(message, title) { this.show(message, 'info', title); }
};

// ======================================
// COUNTDOWN TIMER
// ======================================
const CountdownTimer = {
    init() {
        document.querySelectorAll('[data-countdown]').forEach(element => {
            const targetDate = element.getAttribute('data-countdown');
            this.updateCountdown(element, targetDate);

            // Update every second
            setInterval(() => this.updateCountdown(element, targetDate), 1000);
        });
    },

    updateCountdown(element, targetDate) {
        const now = new Date().getTime();
        const target = new Date(targetDate).getTime();
        const diff = target - now;

        if (diff <= 0) {
            element.innerHTML = '<span class="text-danger font-semibold">Overdue!</span>';
            return;
        }

        const days = Math.floor(diff / (1000 * 60 * 60 * 24));
        const hours = Math.floor((diff % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
        const minutes = Math.floor((diff % (1000 * 60 * 60)) / (1000 * 60));

        let html = '<div class="countdown">';

        if (days > 0) {
            html += `<div class="countdown-item"><span class="countdown-value">${days}</span><span class="countdown-label">Days</span></div>`;
        }
        html += `<div class="countdown-item"><span class="countdown-value">${hours}</span><span class="countdown-label">Hrs</span></div>`;
        html += `<div class="countdown-item"><span class="countdown-value">${minutes}</span><span class="countdown-label">Min</span></div>`;
        html += '</div>';

        element.innerHTML = html;

        // Add warning colors
        if (days === 0 && hours < 24) {
            element.classList.add('text-danger');
        } else if (days <= 3) {
            element.classList.add('text-warning');
        }
    }
};

// ======================================
// ANIMATED COUNTERS
// ======================================
const AnimatedCounter = {
    init() {
        const observerOptions = {
            threshold: 0.5
        };

        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    this.animateValue(entry.target);
                    observer.unobserve(entry.target);
                }
            });
        }, observerOptions);

        document.querySelectorAll('[data-counter]').forEach(el => observer.observe(el));
    },

    animateValue(element) {
        const target = parseInt(element.getAttribute('data-counter'));
        const duration = 1000;
        const step = target / (duration / 16);
        let current = 0;

        const timer = setInterval(() => {
            current += step;
            if (current >= target) {
                element.textContent = target.toLocaleString();
                clearInterval(timer);
            } else {
                element.textContent = Math.floor(current).toLocaleString();
            }
        }, 16);
    }
};

// ======================================
// TABLE FILTERING
// ======================================
function filterTable(tableId, searchInputId) {
    const input = document.getElementById(searchInputId);
    const filter = input.value.toUpperCase();
    const table = document.getElementById(tableId);
    const rows = table.getElementsByTagName('tr');

    for (let i = 1; i < rows.length; i++) {
        const cells = rows[i].getElementsByTagName('td');
        let found = false;

        for (let j = 0; j < cells.length; j++) {
            const text = cells[j].textContent || cells[j].innerText;
            if (text.toUpperCase().indexOf(filter) > -1) {
                found = true;
                break;
            }
        }

        rows[i].style.display = found ? '' : 'none';
    }
}

// Simple filter for backwards compatibility
function filterTableSimple(tableId, colIndex) {
    const input = document.getElementById('bookSearch') || document.getElementById('searchInput');
    if (!input) return;

    const filter = input.value.toUpperCase();
    const table = document.getElementById(tableId);
    const rows = table.getElementsByTagName('tr');

    for (let i = 1; i < rows.length; i++) {
        const cell = rows[i].getElementsByTagName('td')[colIndex];
        if (cell) {
            const text = cell.textContent || cell.innerText;
            rows[i].style.display = text.toUpperCase().indexOf(filter) > -1 ? '' : 'none';
        }
    }
}

// ======================================
// SIDEBAR TOGGLE (Mobile)
// ======================================
const Sidebar = {
    init() {
        const toggle = document.getElementById('sidebarToggle');
        const sidebar = document.getElementById('sidebar');

        if (toggle && sidebar) {
            toggle.addEventListener('click', () => {
                sidebar.classList.toggle('open');
            });

            // Close sidebar on outside click (mobile)
            document.addEventListener('click', (e) => {
                if (window.innerWidth <= 1024 &&
                    !sidebar.contains(e.target) &&
                    !toggle.contains(e.target)) {
                    sidebar.classList.remove('open');
                }
            });
        }
    }
};

// ======================================
// PASSWORD TOGGLE
// ======================================
function togglePasswordVisibility(inputId, toggleId) {
    const input = document.getElementById(inputId);
    const toggle = document.getElementById(toggleId);

    if (input && toggle) {
        toggle.addEventListener('click', () => {
            const type = input.type === 'password' ? 'text' : 'password';
            input.type = type;
            toggle.classList.toggle('fa-eye');
            toggle.classList.toggle('fa-eye-slash');
        });
    }
}

// ======================================
// STAR RATING
// ======================================
const StarRating = {
    init() {
        document.querySelectorAll('.star-rating').forEach(container => {
            const input = container.querySelector('input[type="hidden"]');
            const stars = container.querySelectorAll('.star');

            stars.forEach((star, index) => {
                star.addEventListener('click', () => {
                    const value = index + 1;
                    input.value = value;
                    this.updateStars(stars, value);
                });

                star.addEventListener('mouseenter', () => {
                    this.updateStars(stars, index + 1);
                });
            });

            container.addEventListener('mouseleave', () => {
                this.updateStars(stars, parseInt(input.value) || 0);
            });
        });
    },

    updateStars(stars, value) {
        const val = Number(value);
        stars.forEach((star, index) => {
            if (index < val) {
                star.classList.add('filled');
                star.classList.remove('fa-regular');
                star.classList.add('fa-solid');
            } else {
                star.classList.remove('filled');
                star.classList.add('fa-regular');
                star.classList.remove('fa-solid');
            }
        });
    }

};

// ======================================
// CONFIRMATION MODAL
// ======================================
const ConfirmModal = {
    modalId: 'confirmModal',

    show(message = 'Are you sure?', title = 'Confirm Action', type = 'danger') {
        return new Promise((resolve) => {
            const modal = document.getElementById(this.modalId);
            const titleEl = document.getElementById('confirmTitle');
            const messageEl = document.getElementById('confirmMessage');
            const proceedBtn = document.getElementById('confirmProceed');
            const cancelBtn = document.getElementById('confirmCancel');

            if (!modal) return resolve(confirm(message)); // Fallback

            titleEl.textContent = title;
            messageEl.textContent = message;

            // Set button color based on type
            proceedBtn.className = `btn flex-1 ${type === 'danger' ? 'btn-danger' : 'btn-primary'}`;

            openModal(this.modalId);

            const handleConfirm = () => {
                closeModal(this.modalId);
                cleanup();
                resolve(true);
            };

            const handleCancel = () => {
                closeModal(this.modalId);
                cleanup();
                resolve(false);
            };

            const cleanup = () => {
                proceedBtn.removeEventListener('click', handleConfirm);
                cancelBtn.removeEventListener('click', handleCancel);
            };

            proceedBtn.addEventListener('click', handleConfirm);
            cancelBtn.addEventListener('click', handleCancel);
        });
    }
};

// Replaces the native confirm for forms
async function confirmAction(event, message, title = 'Confirm Action', type = 'danger') {
    event.preventDefault();
    const form = event.target.closest('form');
    const confirmed = await ConfirmModal.show(message, title, type);
    if (confirmed && form) {
        form.submit();
    }
    return false;
}

// Global functions for backwards compatibility
function confirmDelete(message = 'Are you sure you want to delete this?') {
    // This is now synchronous for legacy calls, 
    // but newer code should use await ConfirmModal.show()
    return confirm(message);
}

// ======================================
// FORM VALIDATION
// ======================================
function validateForm(formId) {
    const form = document.getElementById(formId);
    if (!form) return true;

    let valid = true;
    const inputs = form.querySelectorAll('[required]');

    inputs.forEach(input => {
        if (!input.value.trim()) {
            input.classList.add('border-red-500');
            valid = false;
        } else {
            input.classList.remove('border-red-500');
        }
    });

    return valid;
}

// ======================================
// INITIALIZE ALL
// ======================================
document.addEventListener('DOMContentLoaded', () => {
    ThemeManager.init();
    ModalManager.init();
    CountdownTimer.init();
    AnimatedCounter.init();
    Sidebar.init();
    StarRating.init();

    // Initialize AOS if available
    if (typeof AOS !== 'undefined') {
        AOS.init({
            duration: 600,
            once: true,
            offset: 50
        });
    }
});

// Export for module usage
if (typeof module !== 'undefined' && module.exports) {
    module.exports = { ThemeManager, ModalManager, Toast, CountdownTimer, AnimatedCounter };
}
