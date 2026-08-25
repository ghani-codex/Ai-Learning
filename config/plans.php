<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Subscription Plans (display-only for now)
    |--------------------------------------------------------------------------
    |
    | Single source of truth for the /user/subscription page. No billing logic
    | is wired up yet — these tiers exist purely so the page can render.
    |
    | Each plan supports:
    |   id            — internal slug (matches $currentPlan)
    |   name          — display name
    |   icon          — Tabler icon (without the "ti-" prefix)
    |   tagline       — one-line description under the name
    |   price_monthly — numeric monthly price ("0", "9") or null for custom
    |   price_yearly  — numeric yearly price ("0", "79") or null for custom
    |   price_custom  — string shown when both prices are null (e.g. "Custom")
    |   yearly_note   — optional micro-copy under yearly price (e.g. "Save 27%")
    |   popular       — bool; renders the gradient "Most Popular" pill
    |   cta_label     — button text
    |   cta_action    — "current" | "toast" | "mailto"
    |   cta_href      — used when action = mailto
    |   features      — list of [text, included]; included=true draws check, false draws ✗
    |
    */

    'plans' => [

        [
            'id'            => 'free',
            'name'          => 'Free',
            'icon'          => 'bolt',
            'tagline'       => 'Everything you need to land your first match.',
            'price_monthly' => 0,
            'price_yearly'  => 0,
            'price_custom'  => null,
            'yearly_note'   => null,
            'popular'       => false,
            'cta_label'     => 'Current Plan',
            'cta_action'    => 'current',
            'cta_href'      => null,
            'features'      => [
                ['text' => '1 CV parse',                      'included' => true],
                ['text' => 'Up to 10 job matches',            'included' => true],
                ['text' => 'Basic skill-gap analysis',        'included' => true],
                ['text' => '3 course recommendations',        'included' => true],
                ['text' => '1 CV upgrade per month',          'included' => true],
                ['text' => 'Light / dark mode',               'included' => true],
                ['text' => 'Unlimited CV upgrades',           'included' => false],
                ['text' => 'Cover letter generation',         'included' => false],
                ['text' => 'Priority support',                'included' => false],
                ['text' => 'Multiple CV templates',           'included' => false],
            ],
        ],

        [
            'id'            => 'pro',
            'name'          => 'Pro',
            'icon'          => 'rocket',
            'tagline'       => 'Unlimited matches and rewrites for serious job-seekers.',
            'price_monthly' => 9,
            'price_yearly'  => 79,
            'price_custom'  => null,
            'yearly_note'   => 'Save 27% billed yearly',
            'popular'       => true,
            'cta_label'     => 'Upgrade to Pro',
            'cta_action'    => 'toast',
            'cta_href'      => null,
            'features'      => [
                ['text' => 'Unlimited CVs',                                      'included' => true],
                ['text' => 'Unlimited job matches',                              'included' => true],
                ['text' => 'Advanced skill-gap analysis with priority scoring', 'included' => true],
                ['text' => 'Unlimited course recommendations',                   'included' => true],
                ['text' => 'Unlimited CV upgrades',                              'included' => true],
                ['text' => 'Cover letter generation',                            'included' => true],
                ['text' => 'Multiple CV templates',                              'included' => true],
                ['text' => 'Priority email support',                             'included' => true],
            ],
        ],

        [
            'id'            => 'enterprise',
            'name'          => 'Enterprise',
            'icon'          => 'building-skyscraper',
            'tagline'       => 'For universities, bootcamps, and career services.',
            'price_monthly' => null,
            'price_yearly'  => null,
            'price_custom'  => 'Custom',
            'yearly_note'   => null,
            'popular'       => false,
            'cta_label'     => 'Contact Sales',
            'cta_action'    => 'mailto',
            'cta_href'      => 'mailto:sales@caria.app?subject=Enterprise%20plan%20enquiry',
            'features'      => [
                ['text' => 'Everything in Pro',          'included' => true],
                ['text' => 'Team management dashboard',  'included' => true],
                ['text' => 'Custom branding',            'included' => true],
                ['text' => 'API access',                 'included' => true],
                ['text' => 'SSO (SAML / Google)',        'included' => true],
                ['text' => 'Dedicated account manager',  'included' => true],
                ['text' => 'Custom integrations',        'included' => true],
                ['text' => 'SLA',                        'included' => true],
            ],
        ],

    ],

    /*
    |--------------------------------------------------------------------------
    | Comparison matrix
    |--------------------------------------------------------------------------
    |
    | Each row is one feature, with a value per plan id.
    | Allowed values:
    |   true     — green check
    |   false    — muted dash
    |   string   — rendered as-is (e.g. "10", "Unlimited")
    |
    */

    'comparison' => [
        ['feature' => 'CV parses',                    'free' => '1',   'pro' => 'Unlimited', 'enterprise' => 'Unlimited'],
        ['feature' => 'Job matches',                  'free' => '10',  'pro' => 'Unlimited', 'enterprise' => 'Unlimited'],
        ['feature' => 'Skill-gap analysis',           'free' => 'Basic', 'pro' => 'Advanced', 'enterprise' => 'Advanced'],
        ['feature' => 'Course recommendations',       'free' => '3',   'pro' => 'Unlimited', 'enterprise' => 'Unlimited'],
        ['feature' => 'CV upgrades / month',          'free' => '1',   'pro' => 'Unlimited', 'enterprise' => 'Unlimited'],
        ['feature' => 'Cover letter generation',      'free' => false, 'pro' => true,        'enterprise' => true],
        ['feature' => 'Multiple CV templates',        'free' => false, 'pro' => true,        'enterprise' => true],
        ['feature' => 'Priority email support',       'free' => false, 'pro' => true,        'enterprise' => true],
        ['feature' => 'Team management dashboard',    'free' => false, 'pro' => false,       'enterprise' => true],
        ['feature' => 'Custom branding',              'free' => false, 'pro' => false,       'enterprise' => true],
        ['feature' => 'API access',                   'free' => false, 'pro' => false,       'enterprise' => true],
        ['feature' => 'SSO (SAML / Google)',          'free' => false, 'pro' => false,       'enterprise' => true],
        ['feature' => 'Dedicated account manager',    'free' => false, 'pro' => false,       'enterprise' => true],
        ['feature' => 'SLA',                          'free' => false, 'pro' => false,       'enterprise' => true],
    ],

    /*
    |--------------------------------------------------------------------------
    | FAQ
    |--------------------------------------------------------------------------
    */

    'faq' => [
        [
            'q' => 'Can I switch plans later?',
            'a' => 'Yes — upgrade or downgrade at any time. Changes take effect immediately and we prorate the difference on your next invoice.',
        ],
        [
            'q' => 'Is there a free trial of Pro?',
            'a' => 'Yes — every new account gets a 14-day Pro trial with no credit card required. After the trial you stay on Free unless you choose to upgrade.',
        ],
        [
            'q' => 'What payment methods do you accept?',
            'a' => 'All major cards (Visa, Mastercard, American Express), Apple Pay and Google Pay. Enterprise customers can pay by invoice / wire.',
        ],
        [
            'q' => 'Can I cancel anytime?',
            'a' => 'Of course. Cancel from your account settings — you keep Pro access until the end of the current billing period and never get charged again.',
        ],
        [
            'q' => 'Do you offer student discounts?',
            'a' => 'Yes — students get 50% off Pro for life. Sign up with a verified .edu email and the discount is applied automatically at checkout.',
        ],
        [
            'q' => 'Is my data secure?',
            'a' => 'Your CV and job data are encrypted in transit and at rest. We never sell or share your data with third parties, and you can delete your account at any time.',
        ],
    ],

];
