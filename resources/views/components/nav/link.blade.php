@props(['active' => false, 'href' => '#'])
@php $classes = 'px-3 py-2 rounded-lg hover:bg-slate-100 dark:hover:bg-white/10';
if ($active) $classes .= ' text-sky-700 dark:text-sky-400 bg-sky-50/70 dark:bg-sky-900/20';
@endphp
<a {{ $attributes->merge(['href' => $href, 'class' => $classes]) }}>{{ $slot }}</a>