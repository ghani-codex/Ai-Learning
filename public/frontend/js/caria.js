/* ===========================================================
   CaRiA — Landing-page interactions
   Theme toggle (light/dark/auto) · Scroll progress · Navbar state
   Hero parallax cards · Cursor glow · Smooth scroll
   =========================================================== */
(function () {
    'use strict';

    const THEME_KEY = 'caria-theme';
    const root = document.documentElement;

    /* ---------- Theme system ---------- */
    function getStored() { return localStorage.getItem(THEME_KEY) || 'auto'; }
    function setStored(t) { localStorage.setItem(THEME_KEY, t); }

    function resolveTheme(t) {
        if (t === 'auto') {
            return window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light';
        }
        return t;
    }

    function applyTheme(pref) {
        const resolved = resolveTheme(pref);
        root.setAttribute('data-bs-theme', resolved);
        // Update active state on all theme buttons
        document.querySelectorAll('[data-ca-theme]').forEach(btn => {
            btn.classList.toggle('active', btn.dataset.caTheme === pref);
        });
        // Update visible toggle icon
        const icon = document.querySelector('.ca-theme-icon');
        if (icon) {
            icon.className = 'ca-theme-icon bi ' + (
                pref === 'auto' ? 'bi-circle-half'
                    : resolved === 'dark' ? 'bi-moon-stars-fill'
                    : 'bi-sun-fill'
            );
        }
    }

    // Initial paint already handled by inline head script — re-run to set active states
    applyTheme(getStored());

    document.addEventListener('click', (e) => {
        const btn = e.target.closest('[data-ca-theme]');
        if (!btn) return;
        const pref = btn.dataset.caTheme;
        setStored(pref);
        applyTheme(pref);
    });

    // Follow OS changes when on Auto
    window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', () => {
        if (getStored() === 'auto') applyTheme('auto');
    });

    /* ---------- Scroll progress bar ---------- */
    const progressBar = document.querySelector('.ca-scroll-progress');
    function updateProgress() {
        if (!progressBar) return;
        const max = document.documentElement.scrollHeight - window.innerHeight;
        const pct = max > 0 ? (window.scrollY / max) * 100 : 0;
        progressBar.style.width = pct + '%';
    }

    /* ---------- Navbar scroll state ---------- */
    const navbar = document.querySelector('.ca-navbar');
    function updateNavbar() {
        if (!navbar) return;
        navbar.classList.toggle('scrolled', window.scrollY > 40);
    }

    let scrollRafPending = false;
    window.addEventListener('scroll', () => {
        if (scrollRafPending) return;
        scrollRafPending = true;
        requestAnimationFrame(() => {
            updateProgress();
            updateNavbar();
            scrollRafPending = false;
        });
    });
    updateProgress();
    updateNavbar();

    /* ---------- Hero cursor glow ---------- */
    const hero = document.querySelector('.ca-hero');
    if (hero) {
        hero.addEventListener('mousemove', (e) => {
            const rect = hero.getBoundingClientRect();
            const x = ((e.clientX - rect.left) / rect.width) * 100;
            const y = ((e.clientY - rect.top) / rect.height) * 100;
            hero.style.setProperty('--mx', x + '%');
            hero.style.setProperty('--my', y + '%');
        });
    }

    /* ---------- Hero parallax cards ---------- */
    const heroVisual = document.querySelector('.ca-hero-visual');
    if (heroVisual) {
        const cards = heroVisual.querySelectorAll('.ca-float-card');
        heroVisual.addEventListener('mousemove', (e) => {
            const rect = heroVisual.getBoundingClientRect();
            const x = ((e.clientX - rect.left) / rect.width - 0.5) * 2; // -1..1
            const y = ((e.clientY - rect.top) / rect.height - 0.5) * 2;
            cards.forEach((card, i) => {
                const depth = (i + 1) * 6;
                card.style.transform = `translate(${x * depth}px, ${y * depth}px)`;
            });
        });
        heroVisual.addEventListener('mouseleave', () => {
            cards.forEach(card => { card.style.transform = ''; });
        });
    }

    /* ---------- Mobile menu ---------- */
    const mobileToggle = document.querySelector('.ca-mobile-toggle');
    const mobileMenu = document.querySelector('.ca-mobile-menu');
    if (mobileToggle && mobileMenu) {
        mobileToggle.addEventListener('click', () => {
            mobileMenu.classList.toggle('open');
            const isOpen = mobileMenu.classList.contains('open');
            mobileToggle.innerHTML = `<i class="bi bi-${isOpen ? 'x-lg' : 'list'}"></i>`;
            document.body.style.overflow = isOpen ? 'hidden' : '';
        });
        mobileMenu.querySelectorAll('a').forEach(a => {
            a.addEventListener('click', () => {
                mobileMenu.classList.remove('open');
                mobileToggle.innerHTML = '<i class="bi bi-list"></i>';
                document.body.style.overflow = '';
            });
        });
    }

    /* ---------- Smooth-scroll for hash links ---------- */
    document.querySelectorAll('a[href^="#"]').forEach(a => {
        a.addEventListener('click', (e) => {
            const id = a.getAttribute('href');
            if (id.length <= 1) return;
            const target = document.querySelector(id);
            if (!target) return;
            e.preventDefault();
            target.scrollIntoView({ behavior: 'smooth', block: 'start' });
        });
    });
})();
