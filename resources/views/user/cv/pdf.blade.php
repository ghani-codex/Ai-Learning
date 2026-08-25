<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>{{ $user->name }} — CV</title>
    <style>
        @page { margin: 14mm 14mm 12mm 14mm; }
        * { box-sizing: border-box; }
        body {
            font-family: DejaVu Sans, sans-serif;
            color: #111;
            font-size: 10.5pt;
            line-height: 1.5;
            margin: 0;
        }

        /* ── Header ── */
        .header { padding-bottom: 10px; border-bottom: 2px solid #6366f1; margin-bottom: 16px; }
        .name {
            font-size: 26pt;
            font-weight: 700;
            color: #0f172a;
            letter-spacing: -0.5px;
            line-height: 1.05;
            margin: 0 0 4px;
        }
        .headline {
            font-size: 11.5pt;
            color: #6366f1;
            font-weight: 600;
            margin: 0 0 8px;
        }
        .contact {
            font-size: 9.5pt;
            color: #475569;
        }
        .contact span { display: inline; }
        .contact .sep { color: #cbd5e1; padding: 0 5px; }

        /* ── Sections ── */
        .section { margin-top: 16px; }
        .section-title {
            font-size: 10pt;
            font-weight: 700;
            color: #6366f1;
            text-transform: uppercase;
            letter-spacing: 1.2px;
            border-bottom: 1px solid #e2e8f0;
            padding-bottom: 4px;
            margin: 0 0 8px;
        }
        .summary {
            color: #1e293b;
            margin: 0;
            text-align: justify;
        }

        /* ── Skills pills ── */
        .skill-pill {
            display: inline-block;
            padding: 2px 9px;
            border: 1px solid #c7d2fe;
            border-radius: 100px;
            font-size: 9pt;
            color: #4f46e5;
            background: #eef2ff;
            margin: 0 3px 4px 0;
            line-height: 1.4;
        }

        /* ── Experience ── */
        .exp-item { margin-bottom: 12px; }
        .exp-header { width: 100%; margin-bottom: 4px; }
        .exp-header td { vertical-align: top; padding: 0; }
        .exp-title {
            font-size: 11pt;
            font-weight: 700;
            color: #0f172a;
        }
        .exp-company {
            font-size: 10pt;
            color: #475569;
            font-weight: 500;
        }
        .exp-duration {
            font-size: 9.5pt;
            color: #64748b;
            text-align: right;
            white-space: nowrap;
            font-style: italic;
        }
        .bullets {
            margin: 4px 0 0;
            padding: 0 0 0 14px;
            list-style: disc;
        }
        .bullets li {
            margin: 2px 0;
            color: #1e293b;
            font-size: 10pt;
            line-height: 1.45;
        }

        /* ── Education ── */
        .edu-item { width: 100%; margin-bottom: 6px; }
        .edu-item td { vertical-align: top; padding: 0; }
        .edu-degree { font-weight: 700; color: #0f172a; font-size: 10.5pt; }
        .edu-school { color: #475569; font-size: 10pt; }
        .edu-year { text-align: right; color: #64748b; font-size: 9.5pt; font-style: italic; white-space: nowrap; }

        /* ── Footer ── */
        .footer {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            text-align: center;
            font-size: 7.5pt;
            color: #94a3b8;
            border-top: 1px solid #e2e8f0;
            padding-top: 4px;
        }
        .footer .brand-c, .footer .brand-r, .footer .brand-a-up { color: #6366f1; font-weight: 700; }
    </style>
</head>
<body>

    {{-- ───────────── HEADER ───────────── --}}
    <div class="header">
        <div class="name">{{ $user->name }}</div>
        @if(!empty($data['headline']))
            <div class="headline">{{ $data['headline'] }}</div>
        @endif
        <div class="contact">
            <span>{{ $user->email }}</span>
            @if(!empty($parsed['phone']))
                <span class="sep">·</span><span>{{ $parsed['phone'] }}</span>
            @endif
            @if(!empty($parsed['preferred_location']))
                <span class="sep">·</span><span>{{ $parsed['preferred_location'] }}</span>
            @endif
        </div>
    </div>

    {{-- ───────────── PROFESSIONAL SUMMARY ───────────── --}}
    @if(!empty($data['summary']))
        <div class="section">
            <div class="section-title">Professional Summary</div>
            <p class="summary">{{ $data['summary'] }}</p>
        </div>
    @endif

    {{-- ───────────── CORE SKILLS ───────────── --}}
    @if(!empty($data['skills']) && is_array($data['skills']))
        <div class="section">
            <div class="section-title">Core Skills</div>
            <div>
                @foreach(array_slice($data['skills'], 0, 18) as $skill)
                    <span class="skill-pill">{{ $skill }}</span>
                @endforeach
            </div>
        </div>
    @endif

    {{-- ───────────── PROFESSIONAL EXPERIENCE ───────────── --}}
    @if(!empty($data['work_experience']) && is_array($data['work_experience']))
        <div class="section">
            <div class="section-title">Professional Experience</div>

            @foreach($data['work_experience'] as $exp)
                <div class="exp-item">
                    <table class="exp-header">
                        <tr>
                            <td>
                                <span class="exp-title">{{ $exp['title'] ?? '' }}</span>
                                @if(!empty($exp['company']))
                                    <span class="exp-company"> — {{ $exp['company'] }}</span>
                                @endif
                            </td>
                            <td class="exp-duration">{{ $exp['duration'] ?? '' }}</td>
                        </tr>
                    </table>

                    @if(!empty($exp['bullets']) && is_array($exp['bullets']))
                        <ul class="bullets">
                            @foreach($exp['bullets'] as $bullet)
                                <li>{{ $bullet }}</li>
                            @endforeach
                        </ul>
                    @endif
                </div>
            @endforeach
        </div>
    @endif

    {{-- ───────────── EDUCATION ───────────── --}}
    @if(!empty($parsed['education_details']) && is_array($parsed['education_details']))
        <div class="section">
            <div class="section-title">Education</div>
            @foreach($parsed['education_details'] as $edu)
                <table class="edu-item">
                    <tr>
                        <td>
                            <span class="edu-degree">{{ $edu['degree'] ?? '' }}</span>
                            @if(!empty($edu['institution']))
                                <span class="edu-school"> — {{ $edu['institution'] }}</span>
                            @endif
                        </td>
                        <td class="edu-year">{{ $edu['year'] ?? '' }}</td>
                    </tr>
                </table>
            @endforeach
        </div>
    @elseif(!empty($parsed['education_level']))
        <div class="section">
            <div class="section-title">Education</div>
            <div>{{ $parsed['education_level'] }}</div>
        </div>
    @endif

    {{-- ───────────── FOOTER ───────────── --}}
    <div class="footer">
        Generated by
        <span class="brand-c">C</span>a<span class="brand-r">R</span>i<span class="brand-a-up">A</span>
        — {{ now()->format('M d, Y') }}
    </div>

</body>
</html>
