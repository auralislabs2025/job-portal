@props(['name', 'title' => 'Modal', 'size' => ''])
<div class="modal-overlay" id="{{ $name }}" style="display:none;">
    <div class="modal {{ $size }}">
        <div class="modal-header">
            <h3>{{ $title }}</h3>
            <button type="button" class="modal-close" onclick="closeModal('{{ $name }}')">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>
        {{ $slot }}
    </div>
</div>
