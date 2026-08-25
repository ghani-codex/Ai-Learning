/* ===========================================================
   CaRiA — Dashboard interactions
   Scroll progress bar · Indeterminate CV loader hookup
   =========================================================== */
(function () {
    'use strict';

    /* ---------- Scroll progress bar ---------- */
    const bar = document.querySelector('.ca-scroll-progress');
    if (bar) {
        const update = () => {
            const max = document.documentElement.scrollHeight - window.innerHeight;
            const pct = max > 0 ? (window.scrollY / max) * 100 : 0;
            bar.style.width = pct + '%';
        };
        let raf = false;
        window.addEventListener('scroll', () => {
            if (raf) return;
            raf = true;
            requestAnimationFrame(() => { update(); raf = false; });
        });
        update();
    }

    /* ---------- CV processing rotating status messages ---------- */
    const statusEl = document.getElementById('statusText');
    if (statusEl) {
        const messages = [
            'Parsing your CV...',
            'Extracting skills...',
            'Identifying your target role...',
            'Searching real-time job listings...',
            'Scoring matches against your profile...',
            'Detecting skill gaps...',
            'Curating relevant courses...'
        ];
        let idx = 0;
        setInterval(() => {
            idx = (idx + 1) % messages.length;
            statusEl.style.opacity = '0';
            setTimeout(() => {
                statusEl.innerHTML = '<i class="ti ti-loader-2 me-1"></i> ' + messages[idx];
                statusEl.style.opacity = '1';
            }, 200);
        }, 2200);
    }
})();
