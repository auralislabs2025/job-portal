@props(['label', 'value'])
<div>
    <div class="detail-label">{{ $label }}</div>
    <div class="detail-value">{!! $value ?? '—' !!}</div>
</div>