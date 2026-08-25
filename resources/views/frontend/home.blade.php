@extends('layouts.frontend', ['bodyClass' => 'home-page'])

@section('title', 'Your AI-Powered Career Co-Pilot')

@section('content')

{{-- ============================================================
     SECTION 1 — HERO
     ============================================================ --}}
<section class="ca-hero" id="hero">
    <div class="ca-hero-cursor" aria-hidden="true"></div>
    <div class="container ca-hero-inner">
        <div class="row align-items-center g-5">
            <div class="col-lg-6">
                <span class="ca-section-eyebrow" data-aos="fade-up">IntelliCareer · AI-BASED ADAPTIVE LEARNING &amp; CAREER PLANNING PLATFORM</span>
                <h1 data-aos="fade-up" data-aos-delay="80">
                    Your <span class="ca-gradient-text">AI-Powered</span><br>Career Co-Pilot.
                </h1>
                <p class="ca-hero-sub" data-aos="fade-up" data-aos-delay="160">
                    IntelliCareer analyzes your CV, finds matching jobs in real time, identifies your skill gaps, and curates the exact YouTube courses you need to close them.
                </p>
                <div class="ca-hero-cta" data-aos="fade-up" data-aos-delay="240">
                    @auth
                        <a href="{{ auth()->user()->isAdmin() ? route('admin.dashboard') : route('user.dashboard') }}" class="ca-btn ca-btn-primary ca-btn-lg">
                            Go to Dashboard <i class="bi bi-arrow-right"></i>
                        </a>
                    @else
                        <a href="{{ route('register') }}" class="ca-btn ca-btn-primary ca-btn-lg">
                            Get Started Free <i class="bi bi-arrow-right"></i>
                        </a>
                    @endauth
                    <a href="#how-it-works" class="ca-btn ca-btn-ghost ca-btn-lg">
                        <i class="bi bi-play-circle"></i> See How It Works
                    </a>
                </div>
                <div class="ca-trust-strip" data-aos="fade-up" data-aos-delay="320">
                    <span><span class="ca-dot"></span> Built for students &amp; professionals</span>
                    <span><span class="ca-dot"></span> Real-time job market data</span>
                    <span><span class="ca-dot"></span> 100% Free Tier</span>
                </div>
            </div>

            <div class="col-lg-6 d-none d-lg-block">
                <div class="ca-hero-visual" data-aos="fade-left" data-aos-delay="200">
                    <div class="ca-float-card ca-float-card-1">
                        <div class="lbl">Step 01 · CV</div>
                        <div class="ttl">CV Parsed Successfully</div>
                        <div class="meta">12 skills detected · Full Stack Developer</div>
                        <div class="mt-3">
                            <span class="ca-pill ca-pill-info">PHP</span>
                            <span class="ca-pill ca-pill-info">Laravel</span>
                            <span class="ca-pill ca-pill-info">React</span>
                        </div>
                    </div>
                    <div class="ca-float-card ca-float-card-2">
                        <div class="lbl">Step 02 · Match</div>
                        <div class="ttl">Senior Full Stack Developer</div>
                        <div class="meta">Acme Inc · Remote</div>
                        <div class="mt-3 d-flex align-items-center justify-content-between">
                            <span class="ca-pill ca-pill-success">87% Match</span>
                            <span style="font-family: var(--ca-font-mono); font-size: 12px; color: var(--ca-text-muted);">$120k–$150k</span>
                        </div>
                    </div>
                    <div class="ca-float-card ca-float-card-3">
                        <div class="lbl">Step 03 · Gap</div>
                        <div class="ttl">Docker · High Priority</div>
                        <div class="meta">Required in 8 of 10 matched jobs</div>
                        <div class="mt-3">
                            <div style="height: 6px; background: var(--ca-glass-strong); border-radius: 100px; overflow: hidden;">
                                <div style="width: 85%; height: 100%; background: var(--ca-gradient);"></div>
                            </div>
                        </div>
                    </div>
                    <div class="ca-float-card ca-float-card-4">
                        <div class="lbl">Step 04 · Learn</div>
                        <div class="ttl">Docker for Beginners</div>
                        <div class="meta"><i class="bi bi-play-fill" style="color: #ef4444;"></i> YouTube · 4h 12m</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- ============================================================
     SECTION 3 — THE PROBLEM
     ============================================================ --}}
<section class="ca-section alt" id="problem">
    <div class="container">
        <div class="text-center" data-aos="fade-up">
            <span class="ca-section-eyebrow">The Problem</span>
            <h2 class="ca-section-title mx-auto">Generic career advice<br>doesn't get you hired.</h2>
            <p class="ca-section-sub">
                Students and professionals struggle to find personalized guidance that aligns with their unique skills, goals, and learning preferences. Existing platforms offer generic recommendations that lack adaptability and real-time insights into job market trends.
            </p>
        </div>

        <div class="row g-4 mt-5">
            <div class="col-lg-4" data-aos="fade-up" data-aos-delay="100">
                <div class="ca-card">
                    <div class="ca-card-icon"><i class="bi bi-person-x-fill"></i></div>
                    <h3>Lack of Personalization</h3>
                    <p>One-size-fits-all advice ignores your actual skills, goals, and the specific role you're aiming for.</p>
                </div>
            </div>
            <div class="col-lg-4" data-aos="fade-up" data-aos-delay="200">
                <div class="ca-card">
                    <div class="ca-card-icon"><i class="bi bi-clock-history"></i></div>
                    <h3>Outdated Recommendations</h3>
                    <p>Static datasets and old algorithms miss what employers are actually asking for in today's market.</p>
                </div>
            </div>
            <div class="col-lg-4" data-aos="fade-up" data-aos-delay="300">
                <div class="ca-card">
                    <div class="ca-card-icon"><i class="bi bi-grid-1x2-fill"></i></div>
                    <h3>Fragmented Tools</h3>
                    <p>Job boards, resume builders, learning sites — you bounce between five tabs to do one job search.</p>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- ============================================================
     SECTION 4 — HOW INTELLICAREER WORKS
     ============================================================ --}}
<section class="ca-section" id="how-it-works">
    <div class="container">
        <div class="text-center" data-aos="fade-up">
            <span class="ca-section-eyebrow">How It Works</span>
            <h2 class="ca-section-title mx-auto">From CV to job offer in <span class="ca-gradient-text">four steps.</span></h2>
            <p class="ca-section-sub">
                Upload once. IntelliCareer's AI pipeline runs in the background — parsing, matching, analyzing, and curating — so you can focus on what matters.
            </p>
        </div>

        <div class="ca-pipeline mt-5">
            <div class="ca-pipeline-step" data-aos="fade-up" data-aos-delay="100">
                <div class="ca-step-num">STEP 01</div>
                <div class="ca-step-icon"><i class="bi bi-cloud-upload-fill"></i></div>
                <div class="ca-step-title">Upload Your CV</div>
                <p class="ca-step-desc">Drop a PDF or fill the manual form. Our AI extracts your skills, experience, and target role.</p>
            </div>
            <div class="ca-pipeline-step" data-aos="fade-up" data-aos-delay="200">
                <div class="ca-step-num">STEP 02</div>
                <div class="ca-step-icon"><i class="bi bi-briefcase-fill"></i></div>
                <div class="ca-step-title">Match Real-Time Jobs</div>
                <p class="ca-step-desc">We hit live job APIs, score every result against your profile, and rank by fit percentage.</p>
            </div>
            <div class="ca-pipeline-step" data-aos="fade-up" data-aos-delay="300">
                <div class="ca-step-num">STEP 03</div>
                <div class="ca-step-icon"><i class="bi bi-bar-chart-line-fill"></i></div>
                <div class="ca-step-title">Pinpoint Skill Gaps</div>
                <p class="ca-step-desc">We compare your skills with what employers actually require for your target role.</p>
            </div>
            <div class="ca-pipeline-step" data-aos="fade-up" data-aos-delay="400">
                <div class="ca-step-num">STEP 04</div>
                <div class="ca-step-icon"><i class="bi bi-mortarboard-fill"></i></div>
                <div class="ca-step-title">Learn What's Missing</div>
                <p class="ca-step-desc">Curated YouTube courses for every gap — picked for relevance, length, and rating.</p>
            </div>
        </div>
    </div>
</section>

{{-- ============================================================
     SECTION 5 — FEATURES
     ============================================================ --}}
<section class="ca-section alt" id="features">
    <div class="container">
        <div class="text-center" data-aos="fade-up">
            <span class="ca-section-eyebrow">Features</span>
            <h2 class="ca-section-title mx-auto">Everything you need.<br>Nothing you don't.</h2>
            <p class="ca-section-sub">
                A unified platform that replaces five tabs with one intelligent assistant powered by reinforcement learning and natural language processing.
            </p>
        </div>

        <div class="row g-4 mt-5">
            <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="100">
                <div class="ca-card">
                    <div class="ca-card-icon"><i class="bi bi-stars"></i></div>
                    <h3>AI-Powered Recommendations</h3>
                    <p>Tailored to your unique skills, goals, and learning preferences — not generic advice for everyone.</p>
                </div>
            </div>
            <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="150">
                <div class="ca-card">
                    <div class="ca-card-icon"><i class="bi bi-graph-up-arrow"></i></div>
                    <h3>Adaptive Learning Paths</h3>
                    <p>Reinforcement learning evolves your roadmap as you progress and your goals shift over time.</p>
                </div>
            </div>
            <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="200">
                <div class="ca-card">
                    <div class="ca-card-icon"><i class="bi bi-bullseye"></i></div>
                    <h3>Real-Time Skill Gap Analysis</h3>
                    <p>Compare your profile against live job-market requirements with importance-ranked gaps.</p>
                </div>
            </div>
            <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="250">
                <div class="ca-card">
                    <div class="ca-card-icon"><i class="bi bi-collection-play-fill"></i></div>
                    <h3>Curated Course Library</h3>
                    <p>YouTube courses targeted at your specific gaps — vetted for relevance, length, and quality.</p>
                </div>
            </div>
            <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="300">
                <div class="ca-card">
                    <div class="ca-card-icon"><i class="bi bi-broadcast"></i></div>
                    <h3>Real-Time Market Analytics</h3>
                    <p>Trending roles, in-demand skills, and salary insights pulled from live job-market APIs.</p>
                </div>
            </div>
            <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="350">
                <div class="ca-card">
                    <div class="ca-card-icon"><i class="bi bi-file-earmark-person-fill"></i></div>
                    <h3>Resume Intelligence</h3>
                    <p>NLP-driven CV parsing extracts skills, experience, and a professional summary automatically.</p>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- ============================================================
     SECTION 6 — WHY INTELLICAREER (COMPARISON)
     ============================================================ --}}
<section class="ca-section">
    <div class="container">
        <div class="text-center" data-aos="fade-up">
            <span class="ca-section-eyebrow">Why IntelliCareer</span>
            <h2 class="ca-section-title mx-auto">A modern platform.<br>Not a job board with extras.</h2>
        </div>

        <div class="row g-4 mt-5">
            <div class="col-lg-6" data-aos="fade-right" data-aos-delay="100">
                <div class="ca-compare ca-compare-bad">
                    <h3 style="color: #ef4444;">Traditional Platforms</h3>
                    <ul>
                        <li>Generic, one-size-fits-all advice</li>
                        <li>Static datasets &amp; outdated algorithms</li>
                        <li>No skill-gap analysis</li>
                        <li>Five different tabs for one task</li>
                        <li>Limited or no AI integration</li>
                        <li>No adaptive learning paths</li>
                    </ul>
                </div>
            </div>
            <div class="col-lg-6" data-aos="fade-left" data-aos-delay="200">
                <div class="ca-compare ca-compare-good">
                    <h3 class="ca-gradient-text">IntelliCareer</h3>
                    <ul>
                        <li>Personalized to your CV &amp; goals</li>
                        <li>Live job-market data, refreshed daily</li>
                        <li>Built-in importance-ranked gap detection</li>
                        <li>One unified hub for the whole journey</li>
                        <li>AI-powered NLP &amp; recommendations</li>
                        <li>Reinforcement learning that evolves with you</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- ============================================================
     SECTION 7 — TECH STACK MARQUEE
     ============================================================ --}}
<section class="ca-section alt" style="padding: 60px 0;">
    <div class="container text-center mb-4" data-aos="fade-up">
        <span class="ca-section-eyebrow">Built With</span>
        <p class="ca-section-sub mx-auto" style="margin-top: 4px;">Modern, battle-tested tools that scale.</p>
    </div>
    <div class="ca-tech-marquee" data-aos="fade-up">
        <div class="ca-tech-track">
            @for ($i = 0; $i < 2; $i++)
                <div class="ca-tech-chip"><i class="bi bi-server"></i> Laravel 12</div>
                <div class="ca-tech-chip"><i class="bi bi-database-fill"></i> MySQL</div>
                <div class="ca-tech-chip"><i class="bi bi-stars"></i> AI Engine</div>
                <div class="ca-tech-chip"><i class="bi bi-search"></i> JSearch API</div>
                <div class="ca-tech-chip"><i class="bi bi-youtube"></i> YouTube Data API</div>
                <div class="ca-tech-chip"><i class="bi bi-grid-3x3-gap-fill"></i> Tabler Icons</div>
                <div class="ca-tech-chip"><i class="bi bi-bootstrap-fill"></i> Bootstrap 5.3</div>
                <div class="ca-tech-chip"><i class="bi bi-lightning-charge-fill"></i> Queue Workers</div>
            @endfor
        </div>
    </div>
</section>

{{-- ============================================================
     SECTION 8 — TEAM
     ============================================================ --}}
<section class="ca-section" id="team">
    <div class="container">
        <div class="text-center" data-aos="fade-up">
            <span class="ca-section-eyebrow">The Team</span>
            <h2 class="ca-section-title mx-auto">Meet the people<br>behind IntelliCareer.</h2>
            <p class="ca-section-sub">
                A Final Year Project from The Islamia University of Bahawalpur, Rahim Yar Khan Campus.
            </p>
        </div>

        <div class="row g-4 mt-5 justify-content-center">
            <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="100">
                <div class="ca-team-card">
                    <div class="ca-avatar">MB</div>
                    <h4>Muhammad Basit Aslam</h4>
                    <div class="ca-role">Co-Founder · Lead Developer</div>
                    <p class="ca-reg">Reg# F22RINFT1MO1055</p>
                    <div class="d-flex justify-content-center gap-2 mt-3">
                        <a href="#" class="ca-theme-toggle" aria-label="LinkedIn"><i class="bi bi-linkedin"></i></a>
                        <a href="#" class="ca-theme-toggle" aria-label="GitHub"><i class="bi bi-github"></i></a>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="200">
                <div class="ca-team-card">
                    <div class="ca-avatar">HS</div>
                    <h4>Hania Shahid</h4>
                    <div class="ca-role">Co-Founder · AI/UX Lead</div>
                    <p class="ca-reg">Reg# F22RINFT1MO1054</p>
                    <div class="d-flex justify-content-center gap-2 mt-3">
                        <a href="#" class="ca-theme-toggle" aria-label="LinkedIn"><i class="bi bi-linkedin"></i></a>
                        <a href="#" class="ca-theme-toggle" aria-label="GitHub"><i class="bi bi-github"></i></a>
                    </div>
                </div>
            </div>
        </div>

        <div class="row justify-content-center mt-4" data-aos="fade-up" data-aos-delay="300">
            <div class="col-lg-6">
                <div class="ca-team-card" style="display: flex; align-items: center; gap: 24px; text-align: left;">
                    <div class="ca-avatar" style="margin-bottom: 0; flex-shrink: 0;">MA</div>
                    <div>
                        <h4 class="mb-1">Dr. Mirza Adnan Baig</h4>
                        <div class="ca-role">Project Supervisor</div>
                        <p class="ca-reg mt-2 mb-0">The Islamia University of Bahawalpur · RYK Campus</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- ============================================================
     SECTION 9 — FINAL CTA
     ============================================================ --}}
<section class="ca-cta-final">
    <div class="container">
        <span class="ca-section-eyebrow" data-aos="fade-up">Get Started</span>
        <h2 data-aos="fade-up" data-aos-delay="80">Stop guessing.<br><span class="ca-gradient-text">Start landing.</span></h2>
        <p data-aos="fade-up" data-aos-delay="160">Upload your CV in 30 seconds and see your matches today. Free forever.</p>
        <div data-aos="fade-up" data-aos-delay="240">
            @auth
                <a href="{{ auth()->user()->isAdmin() ? route('admin.dashboard') : route('user.dashboard') }}" class="ca-btn ca-btn-primary ca-btn-lg">
                    Open Dashboard <i class="bi bi-arrow-right"></i>
                </a>
            @else
                <a href="{{ route('register') }}" class="ca-btn ca-btn-primary ca-btn-lg">
                    Create Your Free Account <i class="bi bi-arrow-right"></i>
                </a>
            @endauth
        </div>
    </div>
</section>

@endsection
