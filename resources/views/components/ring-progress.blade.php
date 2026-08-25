@props([
    'value' => 0,
    'size' => 64,
    'stroke' => 6,
    'label' => null,
    'color' => 'auto',
])

@php
    $val = max(0, min(100, (int) $value));
    $r = ($size - $stroke) / 2;
    $c = 2 * M_PI * $r;
    $offset = $c - ($val / 100) * $c;

    if ($color === 'auto') {
        $resolvedColor = $val >= 70 ? 'success' : ($val >= 40 ? 'warning' : 'danger');
    } else {
        $resolvedColor = $color;
    }

    $shownLabel = $label ?? ($val . '%');
@endphp

<div class="ca-ring ca-ring-{{ $resolvedColor }}" style="width: {{ $size }}px; height: {{ $size }}px;">
    <svg width="{{ $size }}" height="{{ $size }}" viewBox="0 0 {{ $size }} {{ $size }}">
        <defs>
            <linearGradient id="caRingGrad" x1="0%" y1="0%" x2="100%" y2="100%">
                <stop offset="0%" stop-color="#6366f1"/>
                <stop offset="50%" stop-color="#8b5cf6"/>
                <stop offset="100%" stop-color="#06b6d4"/>
            </linearGradient>
        </defs>
        <circle class="ca-ring-track"
                cx="{{ $size / 2 }}" cy="{{ $size / 2 }}" r="{{ $r }}"
                stroke-width="{{ $stroke }}"></circle>
        <circle class="ca-ring-fill"
                cx="{{ $size / 2 }}" cy="{{ $size / 2 }}" r="{{ $r }}"
                stroke-width="{{ $stroke }}"
                stroke-dasharray="{{ number_format($c, 3) }}"
                stroke-dashoffset="{{ number_format($offset, 3) }}"></circle>
    </svg>
    <div class="ca-ring-label" style="font-size: {{ max(11, $size * 0.28) }}px;">
        {{ $shownLabel }}
    </div>
</div>
