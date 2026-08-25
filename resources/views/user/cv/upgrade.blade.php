@extends('layouts.dashboard')

@section('title', 'Upgrade CV')
@section('page-eyebrow', '§ AI CV Upgrade')
@section('page-heading', 'Upgrade your CV.')
@section('page-sub', 'Get a recruiter-ready, ATS-optimized version of your CV — generated from your real profile and the jobs you actually match.')

@section('content')

    {{-- Guard: must have a parsed CV first --}}
    @if(! $cvProfile || ! $cvProfile->isParsed())
        <div class="ca-card text-center py-6">
            <div class="ca-empty-state">
                <div class="ico"><i class="ti ti-file-cv"></i></div>
                <h4>Upload your CV first</h4>
                <p class="text-secondary mb-3">
                    The upgrade engine needs your parsed profile to work. Once your CV is uploaded and analyzed, come back here to generate the upgraded version.
                </p>
                <a href="{{ route('user.cv.index') }}" class="btn ca-glow-btn">
                    <i class="ti ti-upload me-1"></i> Go to My CV
                </a>
            </div>
        </div>
    @else

    {{-- ═══════════════════════════════════════════════════════════════
         AI CV UPGRADE — 4 states (none / generating / ready / failed)
         ═══════════════════════════════════════════════════════════════ --}}
    <div class="ca-card ca-upgrade-card mb-4">

        @if($cvProfile->upgrade_status === 'generating')
            {{-- ───── STATE 2: Generating ───── --}}
            <div class="row align-items-center g-4">
                <div class="col-md-8">
                    <h4 class="mb-2">Crafting your <span class="ca-gradient-text">upgraded CV</span>…</h4>
                    <p class="text-secondary mb-3">
                        Cross-referencing your profile against your top job matches, weaving in ATS keywords, and sharpening every bullet. This usually takes 10–20 seconds.
                    </p>
                    <p class="text-secondary small mb-0" id="upgradeStatusText" style="transition: opacity 0.2s ease;">
                        <i class="ti ti-loader-2 me-1"></i> Analyzing your profile…
                    </p>
                </div>
                <div class="col-md-4 text-center">
                    <div class="ca-upgrade-loader">
                        <svg viewBox="0 0 120 120" aria-hidden="true">
                            <defs>
                                <linearGradient id="caRingGrad" x1="0%" y1="0%" x2="100%" y2="100%">
                                    <stop offset="0%" stop-color="#6366f1"/>
                                    <stop offset="50%" stop-color="#8b5cf6"/>
                                    <stop offset="100%" stop-color="#06b6d4"/>
                                </linearGradient>
                            </defs>
                            <circle class="track" cx="60" cy="60" r="50"></circle>
                            <circle class="arc" cx="60" cy="60" r="50"></circle>
                        </svg>
                        <div class="ca-upgrade-loader-label">AI</div>
                    </div>
                </div>
            </div>

        @elseif($cvProfile->upgrade_status === 'ready' && filled($cvProfile->upgraded_data))
            {{-- ───── STATE 3: Ready ───── --}}
            <div class="d-flex align-items-start justify-content-between flex-wrap gap-3 mb-3">
                <div>
                    <h4 class="mb-1">Your upgraded CV is ready.</h4>
                    <p class="text-secondary small mb-0">Generated {{ $cvProfile->upgraded_at?->diffForHumans() }}.</p>
                </div>
                <div class="d-flex gap-2 flex-wrap">
                    <a href="{{ route('user.cv.download') }}" class="btn ca-glow-btn">
                        <i class="ti ti-download me-1"></i> Download PDF
                    </a>
                    <form method="POST" action="{{ route('user.cv.upgrade') }}" class="d-inline">
                        @csrf
                        <button type="submit" class="btn btn-outline-secondary">
                            <i class="ti ti-refresh me-1"></i> Regenerate
                        </button>
                    </form>
                </div>
            </div>

            <div class="row g-3 mt-2">
                <div class="col-lg-7">
                    <div class="ca-upgrade-preview h-100">
                        <div class="ca-up-lbl">Headline</div>
                        <div class="ca-up-headline mb-3">{{ $cvProfile->upgraded_data['headline'] ?? '' }}</div>
                        <div class="ca-up-lbl">Professional summary</div>
                        <p class="mb-0 small">{{ \Illuminate\Support\Str::limit($cvProfile->upgraded_data['summary'] ?? '', 280) }}</p>
                    </div>
                </div>
                <div class="col-lg-5">
                    <div class="ca-upgrade-preview h-100">
                        <div class="ca-up-lbl">ATS keywords woven in</div>
                        <div class="d-flex flex-wrap gap-1 mt-2">
                            @foreach(array_slice($cvProfile->upgraded_data['ats_keywords'] ?? [], 0, 10) as $kw)
                                <span class="ca-job-skill">{{ $kw }}</span>
                            @endforeach
                        </div>
                        <div class="ca-up-lbl mt-3">Top prioritized skills</div>
                        <div class="d-flex flex-wrap gap-1 mt-2">
                            @foreach(array_slice($cvProfile->upgraded_data['skills'] ?? [], 0, 6) as $sk)
                                <span class="ca-job-skill" style="background: rgba(6,182,212,0.12); color: #06b6d4; border-color: rgba(6,182,212,0.22);">{{ $sk }}</span>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

            {{-- Bullets preview --}}
            @if(!empty($cvProfile->upgraded_data['work_experience']))
                <div class="ca-upgrade-preview mt-3">
                    <div class="ca-up-lbl">Experience preview ({{ count($cvProfile->upgraded_data['work_experience']) }} roles rewritten)</div>
                    @foreach(array_slice($cvProfile->upgraded_data['work_experience'], 0, 1) as $exp)
                        <div class="mt-2">
                            <div class="fw-bold">{{ $exp['title'] ?? '' }} <span class="text-secondary fw-normal">— {{ $exp['company'] ?? '' }}</span></div>
                            @if(!empty($exp['bullets']))
                                <ul class="small text-secondary mb-0 mt-2" style="padding-left: 18px;">
                                    @foreach(array_slice($exp['bullets'], 0, 3) as $b)
                                        <li class="mb-1">{{ $b }}</li>
                                    @endforeach
                                </ul>
                            @endif
                        </div>
                    @endforeach
                    <p class="text-secondary small mt-2 mb-0"><i class="ti ti-info-circle me-1"></i> Full content is in your downloaded PDF.</p>
                </div>
            @endif

        @elseif($cvProfile->upgrade_status === 'failed')
            {{-- ───── STATE 4: Failed ───── --}}
            <div class="d-flex align-items-start justify-content-between flex-wrap gap-3">
                <div>
                    <h4 class="mb-2"><i class="ti ti-alert-circle text-danger me-1"></i> Upgrade failed</h4>
                    <p class="text-secondary mb-0">Something went wrong while generating your upgraded CV. This is usually a temporary AI service issue — please try again.</p>
                </div>
                <form method="POST" action="{{ route('user.cv.upgrade') }}">
                    @csrf
                    <button type="submit" class="btn ca-glow-btn">
                        <i class="ti ti-refresh me-1"></i> Try Again
                    </button>
                </form>
            </div>

        @else
            {{-- ───── STATE 1: Never upgraded ───── --}}
            <div class="row align-items-center g-4">
                <div class="col-md-8">
                    <h4 class="mb-2">Get a <span class="ca-gradient-text">recruiter-ready</span> version of your CV.</h4>
                    <p class="text-secondary mb-4">
                        We'll rewrite your summary, sharpen every bullet, prioritize skills for
                        <strong>{{ $cvProfile->parsed_data['target_niche'] ?? 'your target role' }}</strong>,
                        and weave in the keywords employers are actually searching for — using real signal from your top job matches.
                    </p>
                    <form method="POST" action="{{ route('user.cv.upgrade') }}">
                        @csrf
                        <button type="submit" class="btn ca-glow-btn">
                            <i class="ti ti-sparkles me-1"></i> Generate Upgraded CV
                        </button>
                    </form>
                </div>
                <div class="col-md-4 d-none d-md-block text-center">
                    <div class="ca-upgrade-illus">
                        <i class="ti ti-file-cv"></i>
                        <i class="ti ti-arrow-right ca-arrow"></i>
                        <i class="ti ti-sparkles ca-sparkles"></i>
                    </div>
                </div>
            </div>
        @endif
    </div>

    {{-- Info row: 3 small cards explaining how it works --}}
    @if($cvProfile->upgrade_status !== 'generating')
        <div class="row g-3 mb-6">
            <div class="col-md-4">
                <div class="ca-card h-100">
                    <div class="ca-card-icon"><i class="ti ti-target"></i></div>
                    <h5 class="mb-1" style="font-size: 1rem;">Tailored to your target</h5>
                    <p class="text-secondary small mb-0">Aligned to the role you're aiming for — not a generic template.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="ca-card h-100">
                    <div class="ca-card-icon"><i class="ti ti-search"></i></div>
                    <h5 class="mb-1" style="font-size: 1rem;">ATS-optimized</h5>
                    <p class="text-secondary small mb-0">Keywords pulled from your real matched jobs so it reads what employers scan for.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="ca-card h-100">
                    <div class="ca-card-icon"><i class="ti ti-bolt"></i></div>
                    <h5 class="mb-1" style="font-size: 1rem;">Action-driven bullets</h5>
                    <p class="text-secondary small mb-0">Every bullet starts with a strong verb and surfaces measurable impact.</p>
                </div>
            </div>
        </div>
    @endif

    @endif

@endsection

@push('scripts')
@if($cvProfile && $cvProfile->upgrade_status === 'generating')
<script>
(function () {
    let attempts = 0;
    const max = 30;
    const messages = [
        'Analyzing your profile…',
        'Cross-referencing your top job matches…',
        'Identifying high-impact ATS keywords…',
        'Rewriting your professional summary…',
        'Sharpening work-experience bullets…',
        'Prioritizing skills for your target role…',
        'Polishing the final draft…'
    ];
    let msgIdx = 0;
    const statusEl = document.getElementById('upgradeStatusText');

    setInterval(() => {
        if (!statusEl) return;
        msgIdx = (msgIdx + 1) % messages.length;
        statusEl.style.opacity = '0';
        setTimeout(() => {
            statusEl.innerHTML = '<i class="ti ti-loader-2 me-1"></i> ' + messages[msgIdx];
            statusEl.style.opacity = '1';
        }, 200);
    }, 2400);

    const tick = async () => {
        if (++attempts > max) return;
        try {
            const res = await fetch('{{ route('user.cv.upgrade.status') }}');
            const data = await res.json();
            if (data.status === 'ready' || data.status === 'failed') {
                window.location.reload();
                return;
            }
        } catch (e) {}
        setTimeout(tick, 3000);
    };
    setTimeout(tick, 3000);
})();
</script>
@endif
@endpush

@push('styles')
<style>
    .ca-upgrade-card { position: relative; overflow: hidden; }
    .ca-upgrade-card::before {
        content: '';
        position: absolute;
        inset: 0;
        background: radial-gradient(circle at 100% 0%, rgba(99, 102, 241, 0.10), transparent 50%),
                    radial-gradient(circle at 0% 100%, rgba(6, 182, 212, 0.06), transparent 55%);
        pointer-events: none;
        z-index: 0;
    }
    .ca-upgrade-card > * { position: relative; z-index: 1; }

    .ca-upgrade-loader { width: 120px; height: 120px; position: relative; display: inline-flex; align-items: center; justify-content: center; }
    .ca-upgrade-loader svg { width: 100%; height: 100%; animation: ca-up-rotate 2.4s linear infinite; }
    .ca-upgrade-loader .track { fill: none; stroke: rgba(148, 163, 184, 0.18); stroke-width: 5; }
    .ca-upgrade-loader .arc { fill: none; stroke: url(#caRingGrad); stroke-width: 5; stroke-linecap: round; stroke-dasharray: 80 360; animation: ca-up-dash 2.4s ease-in-out infinite; }
    .ca-upgrade-loader-label { position: absolute; inset: 0; display: flex; align-items: center; justify-content: center; font-family: var(--ca-font-mono); font-size: 13px; font-weight: 600; color: var(--ca-primary); letter-spacing: 0.1em; }
    @keyframes ca-up-rotate { 100% { transform: rotate(270deg); } }
    @keyframes ca-up-dash {
        0% { stroke-dasharray: 1 360; stroke-dashoffset: 0; }
        50% { stroke-dasharray: 180 360; stroke-dashoffset: -60; }
        100% { stroke-dasharray: 1 360; stroke-dashoffset: -360; }
    }

    .ca-upgrade-preview { background: var(--bs-tertiary-bg); border: 1px solid var(--bs-border-color); border-radius: 12px; padding: 1rem 1.1rem; }
    [data-bs-theme="dark"] .ca-upgrade-preview { background: rgba(255, 255, 255, 0.03); border-color: rgba(255, 255, 255, 0.08); }
    .ca-up-lbl { font-family: var(--ca-font-mono); font-size: 10px; text-transform: uppercase; letter-spacing: 0.1em; color: var(--ca-primary); margin-bottom: 6px; }
    .ca-up-headline { font-family: var(--ca-font-heading); font-weight: 700; font-size: 1.1rem; color: var(--bs-body-color); }

    .ca-upgrade-illus { position: relative; height: 140px; display: inline-flex; align-items: center; justify-content: center; gap: 12px; font-size: 48px; color: var(--ca-primary); }
    .ca-upgrade-illus .ti-arrow-right { font-size: 28px; opacity: 0.6; }
    .ca-upgrade-illus .ca-sparkles { background: var(--ca-gradient); -webkit-background-clip: text; background-clip: text; color: transparent; animation: ca-shimmer 6s ease-in-out infinite; }
</style>
@endpush
