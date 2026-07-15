@props(['icon' => 'fa-inbox', 'message', 'actionLabel' => null, 'actionUrl' => null])
<div class="card text-center" style="grid-column:1/-1;padding:3rem;">
    <i class="fa-solid {{ $icon }}" style="font-size:3rem;color:var(--gray-text);"></i>
    <p class="text-muted mt-2">{{ $message }}</p>
    @if($actionLabel && $actionUrl)
        <a href="{{ $actionUrl }}" class="btn btn-primary mt-2">{{ $actionLabel }}</a>
    @endif
</div>
