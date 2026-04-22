@extends('layouts.app')
@section('title', 'My Profile')
@section('content')

{{-- Header --}}
<div style="background:linear-gradient(135deg,#064e3b,#10B981);padding:40px 0;">
    <div class="container">
        <div style="display:flex;align-items:center;gap:20px;">
            <img src="{{ auth()->user()->profile_picture ? asset('storage/'.auth()->user()->profile_picture) : asset('assets/img/client-face1.png') }}"
                style="width:72px;height:72px;border-radius:50%;object-fit:cover;border:3px solid rgba(255,255,255,.4);" alt="Profile">
            <div>
                <h1 style="font-size:24px;font-weight:800;color:#fff;margin:0 0 4px;">{{ auth()->user()->name }}</h1>
                <p style="color:rgba(255,255,255,.8);margin:0;font-size:14px;">{{ ucfirst(auth()->user()->role) }} · {{ auth()->user()->email }}</p>
            </div>
        </div>
    </div>
</div>

<div style="background:#F8FAFC;padding:30px 0 70px;">
    <div class="container">
        <div class="row">

            {{-- Sidebar --}}
            <div class="col-md-3" style="margin-bottom:24px;">
                <div style="background:#fff;border-radius:14px;border:1px solid #E5E7EB;overflow:hidden;">
                    <div id="profile-tabs" style="padding:8px;">
                        <a href="#tab-favorites" data-toggle="tab"
                            style="display:flex;align-items:center;gap:10px;padding:12px 16px;border-radius:10px;color:#374151;text-decoration:none;font-weight:600;font-size:14px;margin-bottom:4px;" class="profile-tab-link active-tab">
                            <i class="fa fa-heart" style="color:#10B981;width:16px;"></i> My Favorites
                        </a>
                        <a href="#tab-reviews" data-toggle="tab"
                            style="display:flex;align-items:center;gap:10px;padding:12px 16px;border-radius:10px;color:#374151;text-decoration:none;font-weight:600;font-size:14px;margin-bottom:4px;" class="profile-tab-link">
                            <i class="fa fa-star" style="color:#10B981;width:16px;"></i> My Reviews
                        </a>
                        <a href="#tab-settings" data-toggle="tab"
                            style="display:flex;align-items:center;gap:10px;padding:12px 16px;border-radius:10px;color:#374151;text-decoration:none;font-weight:600;font-size:14px;" class="profile-tab-link">
                            <i class="fa fa-cog" style="color:#10B981;width:16px;"></i> Settings
                        </a>
                    </div>
                </div>
            </div>

            {{-- Content --}}
            <div class="col-md-9">
                <div class="tab-content">

                    {{-- Favorites --}}
                    <div id="tab-favorites" class="tab-pane fade in active">
                        <div style="background:#fff;border-radius:14px;padding:24px;border:1px solid #E5E7EB;margin-bottom:20px;">
                            <h3 style="font-size:20px;font-weight:700;color:#111827;margin:0 0 20px;">My Favorites</h3>
                            <div class="row">
                                @forelse($favorites ?? [] as $place)
                                    @include('components.place-card', ['place' => $place])
                                @empty
                                    <div class="col-md-12 text-center" style="padding:40px 0;">
                                        <div style="font-size:48px;margin-bottom:12px;">❤️</div>
                                        <p style="color:#6B7280;">You haven't saved any places yet.</p>
                                        <a href="{{ route('places.index') }}" style="background:#10B981;color:#fff;padding:10px 24px;border-radius:10px;font-weight:600;text-decoration:none;font-size:14px;">Explore Places</a>
                                    </div>
                                @endforelse
                            </div>
                        </div>
                    </div>

                    {{-- Reviews --}}
                    <div id="tab-reviews" class="tab-pane fade">
                        <div style="background:#fff;border-radius:14px;padding:24px;border:1px solid #E5E7EB;margin-bottom:20px;">
                            <h3 style="font-size:20px;font-weight:700;color:#111827;margin:0 0 20px;">My Reviews</h3>
                            @forelse($reviews ?? [] as $review)
                                @include('components.review-card', ['review' => $review, 'showPlace' => true])
                            @empty
                                <div class="text-center" style="padding:40px 0;">
                                    <div style="font-size:48px;margin-bottom:12px;">⭐</div>
                                    <p style="color:#6B7280;">You haven't written any reviews yet.</p>
                                </div>
                            @endforelse
                        </div>
                    </div>

                    {{-- Settings --}}
                    <div id="tab-settings" class="tab-pane fade">
                        <div style="background:#fff;border-radius:14px;padding:24px;border:1px solid #E5E7EB;">
                            <h3 style="font-size:20px;font-weight:700;color:#111827;margin:0 0 24px;">Account Settings</h3>
                            @if(session('success'))
                                <div style="background:#ECFDF5;border:1px solid #A7F3D0;color:#065F46;padding:14px 18px;border-radius:10px;margin-bottom:20px;">{{ session('success') }}</div>
                            @endif
                            <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data">
                                @csrf
                                @method('PATCH')
                                <div class="row">
                                    <div class="col-md-6">
                                        <div style="margin-bottom:16px;">
                                            <label style="font-size:13px;font-weight:600;color:#374151;margin-bottom:6px;display:block;">First Name</label>
                                            <input type="text" name="name" value="{{ old('name', auth()->user()->name) }}" class="form-control" style="height:44px;border-radius:8px;">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div style="margin-bottom:16px;">
                                            <label style="font-size:13px;font-weight:600;color:#374151;margin-bottom:6px;display:block;">Last Name</label>
                                            <input type="text" name="lastname" value="{{ old('lastname', auth()->user()->lastname) }}" class="form-control" style="height:44px;border-radius:8px;">
                                        </div>
                                    </div>
                                </div>
                                <div style="margin-bottom:16px;">
                                    <label style="font-size:13px;font-weight:600;color:#374151;margin-bottom:6px;display:block;">Email</label>
                                    <input type="email" name="email" value="{{ old('email', auth()->user()->email) }}" class="form-control" style="height:44px;border-radius:8px;">
                                </div>
                                <div style="margin-bottom:16px;">
                                    <label style="font-size:13px;font-weight:600;color:#374151;margin-bottom:6px;display:block;">Phone</label>
                                    <input type="text" name="phone" value="{{ old('phone', auth()->user()->phone) }}" class="form-control" style="height:44px;border-radius:8px;">
                                </div>
                                <div style="margin-bottom:20px;">
                                    <label style="font-size:13px;font-weight:600;color:#374151;margin-bottom:6px;display:block;">Bio</label>
                                    <textarea name="bio" class="form-control" rows="3" style="border-radius:8px;">{{ old('bio', auth()->user()->bio) }}</textarea>
                                </div>
                                <button type="submit" style="background:#10B981;color:#fff;border:none;padding:12px 28px;border-radius:10px;font-weight:700;font-size:14px;cursor:pointer;">
                                    Save Changes
                                </button>
                            </form>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
    .profile-tab-link:hover { background:#F0FDF4; color:#10B981 !important; }
    .active-tab { background:#ECFDF5; color:#10B981 !important; }
</style>
@endpush

@endsection
