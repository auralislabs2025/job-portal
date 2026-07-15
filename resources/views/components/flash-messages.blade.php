@php
    $messages = [];
    foreach (['success', 'error', 'info'] as $type) {
        if (session($type)) $messages[] = ['type' => $type, 'message' => session($type)];
    }
@endphp

@foreach ($messages as $msg)
    <div class="toast toast-{{ $msg['type'] }} flash-toast">
        <i class="fa-solid {{ $msg['type'] === 'success' ? 'fa-check-circle' : ($msg['type'] === 'error' ? 'fa-exclamation-circle' : 'fa-info-circle') }}"></i>
        <span>{{ $msg['message'] }}</span>
        <button onclick="this.parentElement.remove()">&times;</button>
    </div>
@endforeach

<script>
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('.flash-toast').forEach(function(el) {
            setTimeout(function() {
                el.style.opacity = '0';
                setTimeout(function() { el.remove(); }, 500);
            }, 4000);
        });
    });
</script>
