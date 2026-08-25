<footer class="ca-footer" id="contact">
    <div class="container">
        <div class="row gy-5">
            <div class="col-lg-4 col-md-12">
                <a href="{{ route('home') }}" class="ca-brand mb-3 d-inline-flex" style="font-size: 26px;">
                    <img src="{{ asset('frontend/img/caria-logo.svg') }}" alt="IntelliCareer logo">
                    <span>IntelliCareer</span>
                </a>
                <p class="mt-3" style="max-width: 320px;">
                    AI-Based Adaptive Learning &amp; Career Planning Platform — your AI co-pilot for matching jobs, identifying skill gaps, and learning what's missing.
                </p>
                <div class="d-flex gap-2 mt-4">
                    <a href="#" class="ca-theme-toggle" aria-label="GitHub"><i class="bi bi-github"></i></a>
                    <a href="#" class="ca-theme-toggle" aria-label="LinkedIn"><i class="bi bi-linkedin"></i></a>
                    <a href="mailto:intellicareer@iub.edu.pk" class="ca-theme-toggle" aria-label="Email"><i class="bi bi-envelope-fill"></i></a>
                </div>
            </div>

            <div class="col-lg-2 col-md-4 col-6">
                <h5>Product</h5>
                <ul>
                    <li><a href="#hero">Home</a></li>
                    <li><a href="#features">Features</a></li>
                    <li><a href="#how-it-works">How It Works</a></li>
                    <li><a href="{{ route('login') }}">Sign In</a></li>
                    <li><a href="{{ route('register') }}">Get Started</a></li>
                </ul>
            </div>

            <div class="col-lg-2 col-md-4 col-6">
                <h5>Resources</h5>
                <ul>
                    <li><a href="#problem">About</a></li>
                    <li><a href="#team">Team</a></li>
                    <li><a href="#" target="_blank">GitHub</a></li>
                    <li><a href="mailto:intellicareer@iub.edu.pk">Contact</a></li>
                </ul>
            </div>

            <div class="col-lg-4 col-md-4 col-12">
                <h5>Institution</h5>
                <p style="font-size: 14px; color: var(--ca-text);">
                    <strong style="color: var(--ca-text);">The Islamia University of Bahawalpur</strong><br>
                    Rahim Yar Khan Campus<br>
                    Rahim Yar Khan, Pakistan
                </p>
                <p style="font-size: 13px; color: var(--ca-text-muted); margin-top: 12px;">
                    <span class="ca-mono" style="color: var(--ca-primary);">Final Year Project · 2026</span>
                </p>
            </div>
        </div>

        <div class="ca-footer-bottom">
            <div class="ca-line"></div>
            <p class="mb-1">© 2026 <strong style="color: var(--ca-text);">IntelliCareer</strong> — AI-Based Adaptive Learning &amp; Career Planning Platform.</p>
            <p class="mb-0" style="font-size: 12px;">
                Designed &amp; built by <strong style="color: var(--ca-text);">Muhammad Basit Aslam</strong> &amp; <strong style="color: var(--ca-text);">Hania Shahid</strong>
                · Supervised by <strong style="color: var(--ca-text);">Dr. Mirza Adnan Baig</strong>
            </p>
        </div>
    </div>
</footer>
