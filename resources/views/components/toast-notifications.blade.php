<!-- Toast Container - Top Center -->
<div class="toast-container position-fixed top-0 start-50 translate-middle-x" style="z-index: 99999; padding-top: 20px;">
    <div id="appToast" class="app-toast" role="alert" aria-live="assertive" aria-atomic="true">
        <div class="app-toast-inner">
            <div class="app-toast-icon" id="toastIconWrapper">
                <span id="toastIcon"></span>
            </div>
            <div class="app-toast-content">
                <div id="toastTitle" class="app-toast-title"></div>
                <div id="toastMessage" class="app-toast-message"></div>
            </div>
            <button type="button" class="app-toast-close" id="toastCloseBtn" aria-label="Close">
                <svg width="14" height="14" viewBox="0 0 14 14" fill="none"><path d="M1 1l12 12M13 1L1 13" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg>
            </button>
        </div>
        <div class="app-toast-progress">
            <div class="app-toast-progress-bar" id="toastProgress"></div>
        </div>
    </div>
</div>

<style>
    .app-toast {
        min-width: 400px;
        max-width: 480px;
        border-radius: 16px;
        overflow: hidden;
        pointer-events: auto;
        opacity: 0;
        transform: translateY(-50px) scale(0.85);
        visibility: hidden;
        transition: none;
        font-family: 'Public Sans', 'Poppins', 'Nunito', system-ui, -apple-system, sans-serif;
        box-shadow:
            0 25px 50px -12px rgba(0, 0, 0, 0.25),
            0 12px 24px -8px rgba(0, 0, 0, 0.15),
            0 0 0 1px rgba(255, 255, 255, 0.1) inset;
    }
    .app-toast.active {
        visibility: visible;
        animation: appToastIn 0.6s cubic-bezier(0.175, 0.885, 0.32, 1.275) forwards;
    }
    .app-toast.closing {
        animation: appToastOut 0.45s cubic-bezier(0.6, -0.28, 0.735, 0.045) forwards;
    }

    @keyframes appToastIn {
        0% { opacity: 0; transform: translateY(-50px) scale(0.85); }
        40% { opacity: 1; }
        70% { transform: translateY(6px) scale(1.02); }
        100% { opacity: 1; transform: translateY(0) scale(1); }
    }
    @keyframes appToastOut {
        0% { opacity: 1; transform: translateY(0) scale(1); }
        100% { opacity: 0; transform: translateY(-40px) scale(0.85); }
    }

    .app-toast-inner {
        display: flex;
        align-items: center;
        gap: 14px;
        padding: 18px 20px;
    }

    /* Icon */
    .app-toast-icon {
        width: 44px;
        height: 44px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        background: rgba(255,255,255,0.2);
        position: relative;
    }
    .app-toast-icon::after {
        content: '';
        position: absolute;
        inset: -4px;
        border-radius: 16px;
        background: rgba(255,255,255,0.15);
        opacity: 0;
    }
    .app-toast.active .app-toast-icon::after {
        animation: iconRing 0.8s ease 0.3s forwards;
    }
    @keyframes iconRing {
        0% { opacity: 0.4; transform: scale(0.8); }
        100% { opacity: 0; transform: scale(1.4); }
    }
    .app-toast-icon svg {
        opacity: 0;
        transform: scale(0);
    }
    .app-toast.active .app-toast-icon svg {
        animation: iconPop 0.5s cubic-bezier(0.175, 0.885, 0.32, 1.275) 0.25s forwards;
    }
    @keyframes iconPop {
        0% { opacity: 0; transform: scale(0) rotate(-15deg); }
        100% { opacity: 1; transform: scale(1) rotate(0deg); }
    }

    /* Content */
    .app-toast-content { flex: 1; min-width: 0; }
    .app-toast-title {
        font-weight: 700;
        font-size: 0.94rem;
        letter-spacing: -0.01em;
        line-height: 1.3;
    }
    .app-toast-message {
        font-size: 0.82rem;
        opacity: 0.88;
        line-height: 1.45;
        margin-top: 2px;
    }

    /* Close */
    .app-toast-close {
        background: rgba(255,255,255,0.12);
        border: none;
        color: rgba(255,255,255,0.8);
        width: 30px;
        height: 30px;
        border-radius: 9px;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        flex-shrink: 0;
        transition: all 0.2s ease;
    }
    .app-toast-close:hover {
        background: rgba(255,255,255,0.25);
        color: #fff;
        transform: rotate(90deg) scale(1.1);
    }

    /* Progress */
    .app-toast-progress {
        height: 3px;
        background: rgba(255,255,255,0.15);
    }
    .app-toast-progress-bar {
        height: 100%;
        width: 100%;
        transform-origin: left;
        background: rgba(255,255,255,0.55);
        border-radius: 0 3px 3px 0;
    }
    @keyframes progressCountdown {
        from { transform: scaleX(1); }
        to { transform: scaleX(0); }
    }

    /* ── Success ── */
    .app-toast.toast-success {
        background: linear-gradient(135deg, #059669 0%, #34d399 50%, #10b981 100%);
        color: #fff;
    }
    /* ── Error ── */
    .app-toast.toast-error {
        background: linear-gradient(135deg, #b91c1c 0%, #ef4444 50%, #f87171 100%);
        color: #fff;
    }
    /* ── Warning ── */
    .app-toast.toast-warning {
        background: linear-gradient(135deg, #b45309 0%, #f59e0b 50%, #fbbf24 100%);
        color: #fff;
    }
    /* ── Info ── */
    .app-toast.toast-info {
        background: linear-gradient(135deg, #1d4ed8 0%, #3b82f6 50%, #60a5fa 100%);
        color: #fff;
    }

    @media (max-width: 576px) {
        .app-toast { min-width: calc(100vw - 40px); max-width: calc(100vw - 40px); }
    }
</style>

<script>
    (function() {
        const SVG_ICONS = {
            success: '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>',
            error: '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/></svg>',
            warning: '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>',
            info: '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="16" x2="12" y2="12"/><line x1="12" y1="8" x2="12.01" y2="8"/></svg>'
        };

        let toastTimer = null;

        window.showToast = function(type, title, message) {
            const toast = document.getElementById('appToast');
            const toastIcon = document.getElementById('toastIcon');
            const toastTitle = document.getElementById('toastTitle');
            const toastMessage = document.getElementById('toastMessage');
            const toastProgress = document.getElementById('toastProgress');
            const duration = 5000;

            // Clear any existing timer
            if (toastTimer) clearTimeout(toastTimer);

            // Reset state
            toast.classList.remove('active', 'closing', 'toast-success', 'toast-error', 'toast-warning', 'toast-info');
            void toast.offsetHeight; // force reflow

            // Apply type
            toast.classList.add('toast-' + type);
            toastIcon.innerHTML = SVG_ICONS[type] || SVG_ICONS.info;
            toastTitle.textContent = title;
            toastMessage.textContent = message;

            // Progress bar animation
            toastProgress.style.animation = 'none';
            void toastProgress.offsetHeight;
            toastProgress.style.animation = 'progressCountdown ' + duration + 'ms linear forwards';

            // Show
            toast.classList.add('active');

            // Auto-hide
            toastTimer = setTimeout(function() {
                toast.classList.add('closing');
                setTimeout(function() {
                    toast.classList.remove('active', 'closing');
                }, 450);
            }, duration);
        };

        // Close button
        document.getElementById('toastCloseBtn').addEventListener('click', function() {
            const toast = document.getElementById('appToast');
            if (toastTimer) clearTimeout(toastTimer);
            toast.classList.add('closing');
            setTimeout(function() {
                toast.classList.remove('active', 'closing');
            }, 450);
        });

        // Fire on page load from Laravel session
        document.addEventListener('DOMContentLoaded', function() {
            @if(session('success'))
                showToast('success', 'Success', @json(session('success')));
            @endif

            @if(session('error'))
                showToast('error', 'Error', @json(session('error')));
            @endif

            @if(session('warning'))
                showToast('warning', 'Warning', @json(session('warning')));
            @endif

            @if(session('info'))
                showToast('info', 'Info', @json(session('info')));
            @endif

            @if(session('status') === 'profile-updated')
                showToast('success', 'Success', 'Profile information updated successfully.');
            @endif

            @if(session('status') === 'password-updated')
                showToast('success', 'Success', 'Password updated successfully.');
            @endif

            @if(session('status') && !in_array(session('status'), ['profile-updated', 'password-updated', 'verification-link-sent']))
                showToast('info', 'Info', @json(session('status')));
            @endif

            @if($errors->any())
                showToast('error', 'Validation Error', @json($errors->first()));
            @endif
        });
    })();
</script>
