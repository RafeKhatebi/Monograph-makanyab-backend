@php
    $map = [
        'open'               => ['label' => 'Open',               'bg' => '#10B981', 'text' => '#FFFFFF'],
        'closed'             => ['label' => 'Closed',             'bg' => '#EF4444', 'text' => '#FFFFFF'],
        'temporarily_closed' => ['label' => 'Temporarily Closed', 'bg' => '#F59E0B', 'text' => '#FFFFFF'],
    ];
    $info = $map[$status ?? ''] ?? ['label' => ucfirst($status ?? ''), 'bg' => '#6B7280', 'text' => '#FFFFFF'];
@endphp
<span class="label" style="background: {{ $info['bg'] }}; color: {{ $info['text'] }}; padding: 4px 12px; border-radius: 6px; font-size: 11px; font-weight: 500; letter-spacing: 0.025em;">{{ $info['label'] }}</span>
