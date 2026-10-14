@props(['category'])
<a class="category-card" href="{{ route('services.show',$category->slug) }}"><span class="category-icon">{{ $category->icon }}</span><strong>{{ $category->name }}</strong><small>{{ $category->description }}</small></a>
