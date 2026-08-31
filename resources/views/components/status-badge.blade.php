@php
    $map = [
        'open' => ['label' => __('common.status.open'), 'bg' => '#10B981', 'text' => '#FFFFFF'],
        'closed' => ['label' => __('common.status.closed'), 'bg' => '#EF4444', 'text' => '#FFFFFF'],
        'temporarily_closed' => ['label' => __('common.status.temporarily_closed'), 'bg' => '#F59E0B', 'text' => '#FFFFFF'],
    ];
    $info = $map[$status ?? ''] ?? ['label' => ucfirst($status ?? ''), 'bg' => '#6B7280', 'text' => '#FFFFFF'];
    $badgeClass = in_array($status ?? '', array_keys($map), true)
        ? 'mk-status-badge--' . str_replace('_', '-', $status)
        : 'mk-status-badge--default';
@endphp
<span class="label mk-status-badge {{ $badgeClass }}">{{ $info['label'] }}</span>
