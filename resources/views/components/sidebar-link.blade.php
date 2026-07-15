@props(['route', 'permission', 'icon', 'pattern', 'label', 'id' => null])

@can($permission)
    <a href="{{ route($route) }}"
       class="{{ request()->routeIs($pattern) ? 'active' : '' }}"
       @if($id) id="{{ $id }}" @endif>
        <i class="fa-solid {{ $icon }}"></i> {{ $label }}
    </a>
@endcan
