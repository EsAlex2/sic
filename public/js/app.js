/* SIC — App JavaScript (Tailwind v4 compatible) */
document.addEventListener('DOMContentLoaded', function() {
    // Sidebar toggle (mobile/desktop)
    const toggle = document.getElementById('sidebar-toggle');
    const sidebar = document.getElementById('sidebar');
    const main = document.getElementById('main-content');
    if (toggle && sidebar) {
        toggle.addEventListener('click', () => {
            sidebar.classList.toggle('-translate-x-full');
            if (main) {
                // If the sidebar is closed (has -translate-x-full), we remove margin
                if (sidebar.classList.contains('-translate-x-full')) {
                    main.classList.remove('ml-64');
                    main.classList.add('ml-0');
                } else {
                    main.classList.add('ml-64');
                    main.classList.remove('ml-0');
                }
            }
        });
    }

    // Auto-dismiss flash alerts after 5s
    const flashAlert = document.getElementById('flash-alert');
    if (flashAlert) {
        setTimeout(() => {
            flashAlert.style.opacity = '0';
            flashAlert.style.transform = 'translateY(-10px)';
            flashAlert.style.transition = 'all 0.3s ease';
            setTimeout(() => flashAlert.remove(), 300);
        }, 5000);
    }

    // Intersection Observer for card animations
    const observer = new IntersectionObserver((entries) => {
        entries.forEach((entry, i) => {
            if (entry.isIntersecting) {
                entry.target.style.transitionDelay = `${i * 50}ms`;
                entry.target.style.opacity = '1';
                entry.target.style.transform = 'translateY(0)';
            }
        });
    }, { threshold: 0.1 });

    document.querySelectorAll('[data-animate]').forEach(el => {
        el.style.opacity = '0';
        el.style.transform = 'translateY(16px)';
        el.style.transition = 'all 0.5s cubic-bezier(0.4, 0, 0.2, 1)';
        observer.observe(el);
    });

    // Dark Mode Toggle Logic
    const themeToggleBtn = document.getElementById('theme-toggle');
    const themeToggleDarkIcon = document.getElementById('theme-toggle-dark-icon');
    const themeToggleLightIcon = document.getElementById('theme-toggle-light-icon');

    if (themeToggleBtn && themeToggleDarkIcon && themeToggleLightIcon) {
        // Change the icons inside the button based on previous settings
        if (document.documentElement.classList.contains('dark')) {
            themeToggleLightIcon.classList.remove('hidden');
        } else {
            themeToggleDarkIcon.classList.remove('hidden');
        }

        themeToggleBtn.addEventListener('click', function() {
            // toggle icons inside button
            themeToggleDarkIcon.classList.toggle('hidden');
            themeToggleLightIcon.classList.toggle('hidden');

            // if is set in localStorage
            if (localStorage.getItem('color-theme')) {
                if (localStorage.getItem('color-theme') === 'light') {
                    document.documentElement.classList.add('dark');
                    localStorage.setItem('color-theme', 'dark');
                } else {
                    document.documentElement.classList.remove('dark');
                    localStorage.setItem('color-theme', 'light');
                }
            } else {
                if (document.documentElement.classList.contains('dark')) {
                    document.documentElement.classList.remove('dark');
                    localStorage.setItem('color-theme', 'light');
                } else {
                    document.documentElement.classList.add('dark');
                    localStorage.setItem('color-theme', 'dark');
                }
            }
        });
    }
});

// ─── Modal Helpers ──────────────────────────────────────────────
function openModal(id) {
    const modal = document.getElementById(id);
    if (modal) {
        modal.classList.remove('hidden');
        modal.classList.add('flex');
        // Animate in
        requestAnimationFrame(() => {
            const card = modal.querySelector('[data-modal-card]');
            if (card) {
                card.style.opacity = '1';
                card.style.transform = 'scale(1)';
            }
        });
    }
}

function closeModal(id) {
    const modal = document.getElementById(id);
    if (modal) {
        const card = modal.querySelector('[data-modal-card]');
        if (card) {
            card.style.opacity = '0';
            card.style.transform = 'scale(0.95)';
        }
        setTimeout(() => {
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }, 150);
    }
}

// Close modal on overlay click
document.addEventListener('click', function(e) {
    if (e.target.hasAttribute('data-modal-overlay')) {
        const modal = e.target.closest('[id]');
        if (modal) closeModal(modal.id);
    }
});

// ─── Confirm Delete Global (SweetAlert2) ─────────────────────────
document.addEventListener('submit', function(e) {
    if (e.target.hasAttribute('data-confirm')) {
        e.preventDefault();
        const form = e.target;
        const message = form.getAttribute('data-confirm');
        const isDark = document.documentElement.classList.contains('dark');
        
        Swal.fire({
            title: '¿Confirmar acción?',
            text: message,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3b82f6',
            cancelButtonColor: '#64748b',
            confirmButtonText: 'Sí, continuar',
            cancelButtonText: 'Cancelar',
            background: isDark ? '#1e293b' : '#ffffff',
            color: isDark ? '#f8fafc' : '#1e293b'
        }).then((result) => {
            if (result.isConfirmed) {
                form.removeAttribute('data-confirm');
                form.submit();
            }
        });
    }
});
