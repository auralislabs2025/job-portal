@props(['icon', 'variant' => 'blue', 'value', 'label'])
<div class="stat-card">
    <div class="stat-icon {{ $variant }}"><i class="fa-solid {{ $icon }}"></i></div>
    <div class="stat-info"><h4>{{ $value }}</h4><p>{{ $label }}</p></div>
</div>
