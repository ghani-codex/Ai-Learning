@extends('layouts.dashboard')

@section('title', 'Subscription')
@section('page-eyebrow', '§ SUBSCRIPTION')
@section('page-heading', 'Pick the plan that fits your career goals.')
@section('page-sub', 'Cancel anytime. No credit card required for Free.')

@section('content')

    {{-- Billing-cycle toggle --}}
    <div class="d-flex justify-content-center mb-5">
        <div class="ca-billing-toggle" role="tablist" aria-label="Billing cycle">
            <span class="ca-billing-indicator" aria-hidden="true"></span>
            <button type="button" class="ca-billing-opt is-active" data-cycle="monthly" role="tab" aria-selected="true">
                Monthly
            </button>
            <button type="button" class="ca-billing-opt" data-cycle="yearly" role="tab" aria-selected="false">
                Yearly
                <span class="ca-billing-save">Save 27%</span>
            </button>
        </div>
    </div>

    {{-- Plan cards --}}
    <div class="row g-4 align-items-stretch ca-plan-grid">
        @foreach($plans as $plan)
            <div class="col-lg-4 col-md-6 col-12">
                <div class="ca-plan-card {{ $plan['popular'] ? 'is-popular' : '' }} {{ $plan['id'] === $currentPlan ? 'is-current' : '' }} h-100">

                    @if($plan['popular'])
                        <div class="ca-plan-pill">
                            <i class="ti ti-sparkles me-1"></i> Most Popular
                        </div>
                    @endif

                    @if($plan['id'] === $currentPlan)
                        <div class="ca-plan-current-badge">Current Plan</div>
                    @endif

                    <div class="ca-plan-icon">
                        <i class="ti ti-{{ $plan['icon'] }}"></i>
                    </div>

                    <h3 class="ca-plan-name">{{ $plan['name'] }}</h3>
                    <p class="ca-plan-tagline">{{ $plan['tagline'] }}</p>

                    {{-- Price --}}
                    <div class="ca-plan-price-wrap">
                        @if($plan['price_custom'])
                            <div class="ca-plan-price">
                                <span class="ca-price-amount">{{ $plan['price_custom'] }}</span>
                            </div>
                            <div class="ca-plan-period">Talk to sales for pricing</div>
                        @else
                            <div class="ca-plan-price" data-monthly>
                                <span class="ca-price-currency">$</span>
                                <span class="ca-price-amount">{{ $plan['price_monthly'] }}</span>
                                <span class="ca-price-period">/month</span>
                            </div>
                            <div class="ca-plan-price" data-yearly hidden>
                                <span class="ca-price-currency">$</span>
                                <span class="ca-price-amount">{{ $plan['price_yearly'] }}</span>
                                <span class="ca-price-period">/year</span>
                            </div>
                            @if($plan['yearly_note'])
                                <div class="ca-plan-yearly-note" data-yearly hidden>
                                    <i class="ti ti-discount-2 me-1"></i> {{ $plan['yearly_note'] }}
                                </div>
                            @endif
                            <div class="ca-plan-period" data-monthly>Billed monthly</div>
                            @if(!$plan['yearly_note'])
                                <div class="ca-plan-period" data-yearly hidden>Billed yearly</div>
                            @endif
                        @endif
                    </div>

                    {{-- Features --}}
                    <ul class="ca-plan-features">
                        @foreach($plan['features'] as $feature)
                            <li class="{{ $feature['included'] ? 'is-on' : 'is-off' }}">
                                @if($feature['included'])
                                    <i class="ti ti-circle-check"></i>
                                @else
                                    <i class="ti ti-circle-x"></i>
                                @endif
                                <span>{{ $feature['text'] }}</span>
                            </li>
                        @endforeach
                    </ul>

                    {{-- CTA --}}
                    <div class="ca-plan-cta">
                        @if($plan['cta_action'] === 'current')
                            <button type="button" class="btn btn-outline-secondary w-100" disabled>
                                <i class="ti ti-check me-1"></i> {{ $plan['cta_label'] }}
                            </button>
                        @elseif($plan['cta_action'] === 'mailto')
                            <a href="{{ $plan['cta_href'] }}" class="btn btn-outline-primary w-100">
                                <i class="ti ti-mail me-1"></i> {{ $plan['cta_label'] }}
                            </a>
                        @else
                            <button type="button" class="btn ca-glow-btn w-100 ca-plan-upgrade-btn">
                                <i class="ti ti-arrow-up-right me-1"></i> {{ $plan['cta_label'] }}
                            </button>
                        @endif
                    </div>

                </div>
            </div>
        @endforeach
    </div>

@endsection

@push('styles')
<style>
    /* ───────── Billing cycle toggle ───────── */
    .ca-billing-toggle {
        position: relative;
        display: inline-flex;
        background: rgba(99, 102, 241, 0.06);
        border: 1px solid var(--bs-border-color);
        border-radius: 999px;
        padding: 4px;
        gap: 0;
        /* Fixed width so each half is exactly equal and the indicator lines up. */
        width: 320px;
        max-width: 100%;
    }
    [data-bs-theme="dark"] .ca-billing-toggle {
        background: rgba(99, 102, 241, 0.10);
    }
    .ca-billing-opt {
        position: relative;
        z-index: 2;
        background: transparent;
        border: 0;
        color: var(--bs-secondary-color);
        padding: 9px 16px;
        font-size: 0.9rem;
        font-weight: 600;
        border-radius: 999px;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        transition: color 0.25s ease;
        /* Equal-width halves so the gradient indicator never bleeds into the other label. */
        flex: 1 1 50%;
        min-width: 0;
    }
    .ca-billing-opt.is-active {
        color: #fff;
    }
    .ca-billing-indicator {
        position: absolute;
        top: 4px;
        bottom: 4px;
        left: 4px;
        width: calc(50% - 4px);
        border-radius: 999px;
        background: var(--ca-gradient);
        box-shadow: 0 4px 18px rgba(99, 102, 241, 0.35);
        transition: transform 0.35s cubic-bezier(0.4, 0, 0.2, 1);
        z-index: 1;
    }
    .ca-billing-toggle[data-cycle="yearly"] .ca-billing-indicator {
        transform: translateX(100%);
    }
    .ca-billing-save {
        font-size: 0.65rem;
        font-weight: 700;
        background: rgba(34, 197, 94, 0.15);
        color: #22c55e;
        padding: 2px 7px;
        border-radius: 999px;
        letter-spacing: 0.04em;
        text-transform: uppercase;
    }
    .ca-billing-opt.is-active .ca-billing-save {
        background: rgba(255, 255, 255, 0.22);
        color: #fff;
    }

    /* ───────── Plan cards ───────── */
    /* Push the grid down so the popular pill never collides with the toggle. */
    .ca-plan-grid { padding-top: 2rem; }

    .ca-plan-card {
        position: relative;
        background: var(--bs-body-bg);
        border: 1px solid var(--bs-border-color);
        border-radius: 22px;
        padding: 2rem 1.6rem;
        display: flex;
        flex-direction: column;
        transition: transform 0.35s ease, box-shadow 0.35s ease, border-color 0.35s ease;
        overflow: hidden;
        backdrop-filter: blur(8px);
    }
    .ca-plan-card::before {
        content: '';
        position: absolute;
        inset: 0;
        background: radial-gradient(120% 80% at 50% -10%, rgba(99, 102, 241, 0.10), transparent 60%);
        opacity: 0;
        transition: opacity 0.4s ease;
        pointer-events: none;
    }
    .ca-plan-card:hover {
        transform: translateY(-6px);
        border-color: rgba(99, 102, 241, 0.45);
        box-shadow: 0 18px 50px -20px rgba(99, 102, 241, 0.35);
    }
    .ca-plan-card:hover::before { opacity: 1; }

    /* Popular card — gradient border + glow only (no scale, to avoid pill collision) */
    .ca-plan-card.is-popular {
        background:
            linear-gradient(var(--bs-body-bg), var(--bs-body-bg)) padding-box,
            var(--ca-gradient) border-box;
        border: 1.5px solid transparent;
        box-shadow: 0 20px 60px -25px rgba(139, 92, 246, 0.45);
        padding-top: 2.5rem;
    }
    .ca-plan-card.is-popular:hover {
        box-shadow: 0 28px 80px -20px rgba(139, 92, 246, 0.55);
    }

    /* "Most Popular" pill — sits inside the card so it never overlaps the toggle */
    .ca-plan-pill {
        position: absolute;
        top: 14px;
        left: 50%;
        transform: translateX(-50%);
        background: var(--ca-gradient);
        color: #fff;
        padding: 5px 14px;
        border-radius: 999px;
        font-size: 0.68rem;
        font-weight: 700;
        letter-spacing: 0.06em;
        text-transform: uppercase;
        box-shadow: 0 6px 18px rgba(139, 92, 246, 0.35);
        white-space: nowrap;
        z-index: 3;
    }

    .ca-plan-current-badge {
        position: absolute;
        top: 14px;
        right: 14px;
        background: rgba(34, 197, 94, 0.12);
        color: #22c55e;
        border: 1px solid rgba(34, 197, 94, 0.35);
        padding: 4px 10px;
        border-radius: 999px;
        font-size: 0.7rem;
        font-weight: 700;
        letter-spacing: 0.05em;
        text-transform: uppercase;
    }

    .ca-plan-icon {
        width: 52px;
        height: 52px;
        border-radius: 14px;
        background: rgba(99, 102, 241, 0.10);
        color: #6366f1;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
        margin-bottom: 1rem;
    }
    .ca-plan-card.is-popular .ca-plan-icon {
        background: var(--ca-gradient);
        color: #fff;
        box-shadow: 0 8px 22px rgba(139, 92, 246, 0.45);
    }

    .ca-plan-name {
        font-family: 'Space Grotesk', sans-serif;
        font-weight: 700;
        font-size: 1.4rem;
        margin-bottom: 0.35rem;
    }
    .ca-plan-tagline {
        color: var(--bs-secondary-color);
        font-size: 0.88rem;
        line-height: 1.45;
        min-height: 2.6em;
        margin-bottom: 1.5rem;
    }

    /* Price */
    .ca-plan-price-wrap {
        margin-bottom: 1.5rem;
        padding-bottom: 1.25rem;
        border-bottom: 1px dashed var(--bs-border-color);
    }
    .ca-plan-price {
        display: flex;
        align-items: baseline;
        gap: 4px;
        font-family: 'JetBrains Mono', monospace;
    }
    .ca-price-currency {
        font-size: 1.5rem;
        font-weight: 600;
        color: var(--bs-secondary-color);
        margin-right: 2px;
    }
    .ca-price-amount {
        font-size: 3rem;
        font-weight: 700;
        line-height: 1;
        letter-spacing: -0.02em;
    }
    .ca-plan-card.is-popular .ca-price-amount {
        background: var(--ca-gradient);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }
    .ca-price-period {
        font-size: 0.95rem;
        color: var(--bs-secondary-color);
        margin-left: 6px;
    }
    .ca-plan-period {
        font-size: 0.78rem;
        color: var(--bs-secondary-color);
        margin-top: 6px;
    }
    .ca-plan-yearly-note {
        display: inline-flex;
        align-items: center;
        margin-top: 8px;
        background: rgba(34, 197, 94, 0.12);
        color: #16a34a;
        border: 1px solid rgba(34, 197, 94, 0.30);
        padding: 3px 10px;
        border-radius: 999px;
        font-size: 0.72rem;
        font-weight: 600;
    }
    [data-bs-theme="dark"] .ca-plan-yearly-note {
        color: #4ade80;
    }

    /* Features */
    .ca-plan-features {
        list-style: none;
        padding: 0;
        margin: 0 0 1.5rem 0;
        flex-grow: 1;
    }
    .ca-plan-features li {
        display: flex;
        align-items: flex-start;
        gap: 10px;
        padding: 8px 0;
        font-size: 0.88rem;
        line-height: 1.45;
    }
    .ca-plan-features li i {
        font-size: 1.05rem;
        flex-shrink: 0;
        margin-top: 1px;
    }
    .ca-plan-features li.is-on i { color: #22c55e; }
    .ca-plan-features li.is-off { color: var(--bs-secondary-color); }
    .ca-plan-features li.is-off i { color: var(--bs-tertiary-color); }
    .ca-plan-features li.is-off span { text-decoration: line-through; opacity: 0.7; }

    .ca-plan-cta { margin-top: auto; }
</style>
@endpush

@push('scripts')
<script>
    (function () {
        const toggle = document.querySelector('.ca-billing-toggle');
        if (!toggle) return;

        const opts = toggle.querySelectorAll('.ca-billing-opt');

        function setCycle(cycle) {
            toggle.dataset.cycle = cycle;

            opts.forEach(btn => {
                const active = btn.dataset.cycle === cycle;
                btn.classList.toggle('is-active', active);
                btn.setAttribute('aria-selected', active ? 'true' : 'false');
            });

            document.querySelectorAll('[data-monthly]').forEach(el => {
                el.hidden = (cycle !== 'monthly');
            });
            document.querySelectorAll('[data-yearly]').forEach(el => {
                el.hidden = (cycle !== 'yearly');
            });
        }

        opts.forEach(btn => {
            btn.addEventListener('click', () => setCycle(btn.dataset.cycle));
        });

        // Upgrade CTA → friendly toast (no real billing yet)
        document.querySelectorAll('.ca-plan-upgrade-btn').forEach(btn => {
            btn.addEventListener('click', () => {
                if (typeof window.showToast === 'function') {
                    window.showToast('info', 'Subscriptions launching soon', 'We\'re polishing the billing flow — stay tuned!');
                }
            });
        });
    })();
</script>
@endpush
