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
                        @if ($service->media->first())
                            <img src="{{ asset('storage/' . $service->media->first()->file_path) }}"
                                style="width:100%; height:420px; object-fit:cover;">
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
                        </div>

                        <h2 style="font-size:32px; font-weight:700; margin-bottom:18px;">{{ $service->name }}</h2>
                        <p style="font-size:16px; color:#6B7280; line-height:1.8; margin-bottom:25px;">
                            {{ $service->tagline ?? 'Professional service available in your city.' }}
                        </p>

                        <div style="display:flex; flex-wrap:wrap; gap:12px; margin-bottom:25px;">
                            <div style="background:#F3F4F6; padding:12px 18px; border-radius:12px; font-size:14px;">
                                <strong>City:</strong> {{ $service->city }}</div>
                            <div style="background:#F3F4F6; padding:12px 18px; border-radius:12px; font-size:14px;">
                                <strong>Status:</strong> {{ ucfirst($service->status) }}</div>
                            <div style="background:#F3F4F6; padding:12px 18px; border-radius:12px; font-size:14px;">
                                <strong>Price:</strong> {{ ucfirst($service->price_level) }}</div>
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
                                    <a href="{{ $service->website }}" target="_blank" style="color:#10B981; text-decoration:none;">
                                        {{ $service->website }}
                                    </a>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="box-two" style="padding:30px; border-radius:16px;">
                        <h3 style="font-size:24px; font-weight:700; margin-bottom:18px;">Location Details</h3>
                        <p style="color:#6B7280; margin-bottom:10px;">
                            <strong>Address:</strong> {{ $service->address }}, {{ $service->city }}, {{ $service->district }}
                        </p>
                        @if ($service->latitude && $service->longitude)
                            <p style="color:#6B7280; margin-bottom:10px;"><strong>Coordinates:</strong>
                                {{ $service->latitude }}, {{ $service->longitude }}</p>
                        @endif
                        <p style="color:#6B7280; margin-bottom:10px;"><strong>Province:</strong> {{ $service->province }}</p>
                        <p style="color:#6B7280; margin-bottom:0;"><strong>Country:</strong> {{ $service->country }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
