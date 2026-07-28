@extends('layouts.app')

@section('title', $service->name)

@section('content')
    <div class="page-head">
        <div class="container">
            <h1 class="page-title">{{ $service->name }}</h1>
        </div>
    </div>

    <div class="content-area" style="padding:60px 0; background:#F8FAFC;">
        <div class="container">
            <div class="row">
                <div class="col-md-8">
                    <div class="box-two" style="padding:0; border-radius:16px; overflow:hidden; margin-bottom:30px;">
                        @php
                            $serviceImages = $service->media->where('type', 'image')->sortBy('sort_order');
                            $serviceCover = $serviceImages->firstWhere('is_cover', true) ?? $serviceImages->first();
                        @endphp
                        <img src="{{ $serviceCover ? asset('storage/' . $serviceCover->file_path) : asset('assets/img/demo/property-1.jpg') }}"
                            alt="{{ $service->name }}" style="width:100%; height:420px; object-fit:cover;">
                        @if ($serviceImages->count() > 1)
                            <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(90px,1fr));gap:8px;padding:10px;background:#fff;">
                                @foreach ($serviceImages as $image)
                                    <a href="{{ asset('storage/' . $image->file_path) }}" target="_blank" rel="noopener">
                                        <img src="{{ asset('storage/' . $image->file_path) }}" alt="{{ $service->name }} image {{ $loop->iteration }}"
                                            loading="lazy" style="width:100%;height:76px;object-fit:cover;border-radius:8px;">
                                    </a>
                                @endforeach
                            </div>
                        @endif
                    </div>

                    <div class="box-two" style="padding:35px; border-radius:16px; margin-bottom:30px;">
                        <div style="margin-bottom:20px;">
                            @if ($service->category)
                                <span
                                    style="background:#ECFDF5; color:#10B981; padding:8px 16px; border-radius:50px; font-size:13px; font-weight:600;">
                                    {{ $service->category->name }}
                                </span>
                            @endif
                            @if ($service->is_verified)
                                <span style="margin-left:10px; color:#10B981; font-weight:700;">✓ Verified</span>
                            @endif
                            @auth
                                <form method="POST" action="{{ route('services.favorite', $service) }}" style="display:inline-block;margin-left:10px;">
                                    @csrf
                                    <button type="submit" class="btn btn-default btn-sm">
                                        <i class="fa {{ $isFavorited ? 'fa-star' : 'fa-star-o' }}"></i>
                                        {{ $isFavorited ? 'Saved' : 'Save service' }}
                                    </button>
                                </form>
                            @endauth
                        </div>

                        <h2 style="font-size:32px; font-weight:700; margin-bottom:18px;">{{ $service->name }}</h2>
                        <div style="margin-bottom:14px;">
                            @include('components.rating-stars', ['rating' => $service->avg_rating])
                            <small>({{ $service->reviews->count() }} reviews)</small>
                        </div>
                        <p style="font-size:16px; color:#6B7280; line-height:1.8; margin-bottom:25px;">
                            {{ $service->tagline ?? 'Professional service available in your city.' }}
                        </p>

                        <div style="display:flex; flex-wrap:wrap; gap:12px; margin-bottom:25px;">
                            <div style="background:#F3F4F6; padding:12px 18px; border-radius:12px; font-size:14px;">
                                <strong>City:</strong> {{ $service->city }}
                            </div>
                            <div style="background:#F3F4F6; padding:12px 18px; border-radius:12px; font-size:14px;">
                                <strong>Status:</strong> {{ ucfirst($service->status) }}
                            </div>
                            <div style="background:#F3F4F6; padding:12px 18px; border-radius:12px; font-size:14px;">
                                <strong>Price:</strong> {{ ucfirst($service->price_level) }}
                            </div>
                        </div>

                        <div style="margin-bottom:30px;">
                            {!! nl2br(e($service->description)) !!}
                        </div>

                        <div style="display:grid; gap:12px;">
                            <div style="padding:20px; background:#F8FAFC; border-radius:16px;">
                                <strong>Phone 1:</strong> {{ $service->phone_1 }}
                            </div>
                            @if ($service->phone_2)
                                <div style="padding:20px; background:#F8FAFC; border-radius:16px;">
                                    <strong>Phone 2:</strong> {{ $service->phone_2 }}
                                </div>
                            @endif
                            @if ($service->whatsapp)
                                <div style="padding:20px; background:#F8FAFC; border-radius:16px;">
                                    <strong>WhatsApp:</strong> {{ $service->whatsapp }}
                                </div>
                            @endif
                            @if ($service->website)
                                <div style="padding:20px; background:#F8FAFC; border-radius:16px;">
                                    <strong>Website:</strong>
                                    <a href="{{ $service->website }}" target="_blank"
                                        rel="noopener noreferrer"
                                        style="color:#10B981; text-decoration:none;">
                                        {{ $service->website }}
                                    </a>
                                </div>
                            @endif
                        </div>
                    </div>

                    <section class="box-two" style="padding:30px;border-radius:16px;margin-bottom:30px;" aria-labelledby="service-reviews-heading">
                        <h2 id="service-reviews-heading" style="font-size:22px;font-weight:700;">Reviews</h2>
                        @forelse ($service->reviews as $review)
                            @include('components.review-card', ['review' => $review])
                        @empty
                            <p class="text-muted">No reviews yet. Be the first.</p>
                        @endforelse

                        @auth
                            @if (! auth()->user()->reviews()->where('service_id', $service->id)->exists())
                                <form method="POST" action="{{ route('services.reviews.store', $service) }}" style="margin-top:20px;">
                                    @csrf
                                    <label for="service-rating">Rating</label>
                                    <select id="service-rating" name="rating" class="form-control" required>
                                        @for ($rating = 5; $rating >= 1; $rating--)
                                            <option value="{{ $rating }}">{{ $rating }} star{{ $rating === 1 ? '' : 's' }}</option>
                                        @endfor
                                    </select>
                                    <label for="service-review-comment" style="margin-top:10px;">Comment</label>
                                    <textarea id="service-review-comment" name="comment" class="form-control" rows="4" maxlength="2000"></textarea>
                                    <button type="submit" class="btn btn-primary" style="margin-top:10px;">Submit review</button>
                                </form>
                            @else
                                <p class="text-muted" style="margin-top:15px;">You have already reviewed this service. Edit it from your profile after approval.</p>
                            @endif
                        @else
                            <p><a href="{{ route('login') }}">Log in</a> to review this service.</p>
                        @endauth
                    </section>
                </div>

                <div class="col-md-4">
                    <div class="box-two" style="padding:30px; border-radius:16px;">
                        <h3 style="font-size:24px; font-weight:700; margin-bottom:18px;">Location Details</h3>
                        <p style="color:#6B7280; margin-bottom:10px;">
                            <strong>Address:</strong> {{ $service->address }}, {{ $service->city }},
                            {{ $service->district }}
                        </p>
                        @if ($service->latitude && $service->longitude)
                            <p style="color:#6B7280; margin-bottom:10px;"><strong>Coordinates:</strong>
                                {{ $service->latitude }}, {{ $service->longitude }}</p>
                        @endif
                        <p style="color:#6B7280; margin-bottom:10px;"><strong>Province:</strong> {{ $service->province }}
                        </p>
                        <p style="color:#6B7280; margin-bottom:0;"><strong>Country:</strong> {{ $service->country }}</p>
                    </div>
                </div>
            </div>

            @if ($similar->isNotEmpty())
                <section style="margin-top:30px;" aria-labelledby="related-services-heading">
                    <h2 id="related-services-heading" style="font-size:22px;font-weight:700;margin-bottom:18px;">Related services</h2>
                    <div class="row">
                        @foreach ($similar as $relatedService)
                            <div class="col-sm-6 col-md-3" style="margin-bottom:20px;">
                                <x-service-card :service="$relatedService" />
                            </div>
                        @endforeach
                    </div>
                </section>
            @endif
        </div>
    </div>
@endsection
