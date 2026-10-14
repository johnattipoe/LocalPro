@extends('layouts.app')
@section('title','Explore local services | LocalPro')
@section('content')
@include('components.navbar')
<main class="requests-page"><div class="requests-heading"><div><div class="eyebrow muted">HO & VOLTA REGION</div><h1>Explore services</h1><p>Choose a service to see verified professionals nearby.</p></div></div><div class="category-grid">@forelse($categories as $category)<x-service-card :category="$category" />@empty<p>Service categories are being set up.</p>@endforelse</div></main>
@include('components.footer')
@endsection
