@props(['status'])
@php
$map = [
    'draft' => 'badge-draft', 'pending' => 'badge-pending',
    'published' => 'badge-published', 'rejected' => 'badge-rejected',
    'screening' => 'badge-pending', 'interview' => 'badge-interview',
    'offer' => 'badge-approved', 'hired' => 'badge-published',
    'active' => 'badge-published', 'inactive' => 'badge-inactive',
];
$class = $map[$status] ?? 'badge-draft';
@endphp
<span class="badge {{ $class }}">{{ ucfirst($status) }}</span>
