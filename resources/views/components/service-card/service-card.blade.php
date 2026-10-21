@props(['category'])
@php($icon = in_array($category->icon, ['water', 'bolt', 'hammer', 'wrench', 'brush', 'phone', 'snowflake', 'sparkles', 'scissors', 'needle', 'camera', 'tool'], true) ? $category->icon : 'tool')
<a class="category-card" href="{{ route('services.show', $category->slug) }}"><span class="category-icon" aria-hidden="true"><i data-lucide="{{ $icon }}"></i></span><strong>{{ $category->name }}</strong><small>{{ $category->description }}</small><b aria-hidden="true"><i data-lucide="arrow-right"></i></b></a>
