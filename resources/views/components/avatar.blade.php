@props(['member', 'size' => 'w-10 h-10'])

@php
    $classes = $size . ' rounded-full border-2 border-primary';
    if (isset($attributes['class'])) {
        $classes .= ' ' . $attributes['class'];
    }
@endphp

@if($member->foto->first())
    <img src="{{ $member->foto->first()->url }}" 
         class="{{ $classes }} object-cover" 
         alt="Foto de perfil de {{ $member->full_name }}" />
@else
    @php
        $names = explode(' ', $member->full_name);
        $initials = '';
        if (count($names) >= 2) {
            $initials = strtoupper(substr($names[0], 0, 1) . substr($names[count($names) - 1], 0, 1));
        } else {
            $initials = strtoupper(substr($member->full_name, 0, 2));
        }
    @endphp
    <div class="{{ $classes }} bg-primary dark:bg-primary/20 flex items-center justify-center text-white font-semibold text-sm">
        {{ $initials }}
    </div>
@endif 