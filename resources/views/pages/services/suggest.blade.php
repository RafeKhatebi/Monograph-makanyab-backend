@extends('layouts.app')

@section('title', 'Suggest a Service')

@section('content')
    <div style="background:linear-gradient(135deg,#064e3b,#10B981);padding:50px 0;">
        <div class="container">
            <div class="row">
                <div class="col-md-10 col-md-offset-1 text-center">
                    <h1 style="font-size:36px;font-weight:800;color:#fff;margin-bottom:10px;">Suggest a Service</h1>
                    <p style="color:rgba(255,255,255,.85);font-size:16px;">Know a service that should be listed on Makanyab? Submit it and our admin team will review it before it goes live.</p>
                    <p style="color:rgba(255,255,255,.75);font-size:14px;">If you meant to suggest a place instead, <a href="{{ route('place-suggestions.create') }}" style="color:#D1FAE5;text-decoration:underline;">click here</a>.</p>
                </div>
            </div>
        </div>
    </div>

    <div style="background:#F8FAFC;padding:60px 0;">
        <div class="container">
            <div class="row">
                <div class="col-md-8 col-md-offset-2">
                    @if(session('success'))
                        <div style="background:#D1FAE5;border:1px solid #10B981;color:#065F46;padding:18px;border-radius:14px;margin-bottom:24px;">
                            {{ session('success') }}
                        </div>
                    @endif

                    <div style="background:#fff;border-radius:18px;padding:32px;border:1px solid #E5E7EB;">
                        <h3 style="font-size:22px;font-weight:700;color:#111827;margin-bottom:18px;">Submit a new service</h3>
                        <form action="{{ route('service-suggestions.store') }}" method="POST">
                            @csrf

                            <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;">
                                <div>
                                    <label style="display:block;font-weight:600;color:#374151;margin-bottom:8px;">Service Name</label>
                                    <input type="text" name="name" value="{{ old('name') }}" placeholder="e.g. Herat Taxi Service"
                                        style="width:100%;padding:12px;border:1px solid #D1D5DB;border-radius:10px;outline:none;">
                                    @error('name') <p style="color:#dc2626;font-size:13px;margin-top:6px;">{{ $message }}</p> @enderror
                                </div>
                                <div>
                                    <label style="display:block;font-weight:600;color:#374151;margin-bottom:8px;">Category</label>
                                    <select name="service_category_id"
                                        style="width:100%;padding:12px;border:1px solid #D1D5DB;border-radius:10px;outline:none;">
                                        <option value="">Select category</option>
                                        @foreach($categories as $category)
                                            <option value="{{ $category->id }}" {{ old('service_category_id') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('service_category_id') <p style="color:#dc2626;font-size:13px;margin-top:6px;">{{ $message }}</p> @enderror
                                </div>
                            </div>

                            <div style="margin-top:16px;display:grid;grid-template-columns:1fr 1fr;gap:16px;">
                                <div>
                                    <label style="display:block;font-weight:600;color:#374151;margin-bottom:8px;">City</label>
                                    <input type="text" name="city" value="{{ old('city') }}" placeholder="Herat"
                                        style="width:100%;padding:12px;border:1px solid #D1D5DB;border-radius:10px;outline:none;">
                                    @error('city') <p style="color:#dc2626;font-size:13px;margin-top:6px;">{{ $message }}</p> @enderror
                                </div>
                                <div>
                                    <label style="display:block;font-weight:600;color:#374151;margin-bottom:8px;">Province</label>
                                    <input type="text" name="province" value="{{ old('province') }}" placeholder="Herat"
                                        style="width:100%;padding:12px;border:1px solid #D1D5DB;border-radius:10px;outline:none;">
                                    @error('province') <p style="color:#dc2626;font-size:13px;margin-top:6px;">{{ $message }}</p> @enderror
                                </div>
                            </div>

                            <div style="margin-top:16px;display:grid;grid-template-columns:1fr 1fr;gap:16px;">
                                <div>
                                    <label style="display:block;font-weight:600;color:#374151;margin-bottom:8px;">District</label>
                                    <input type="text" name="district" value="{{ old('district') }}" placeholder="District"
                                        style="width:100%;padding:12px;border:1px solid #D1D5DB;border-radius:10px;outline:none;">
                                    @error('district') <p style="color:#dc2626;font-size:13px;margin-top:6px;">{{ $message }}</p> @enderror
                                </div>
                                <div>
                                    <label style="display:block;font-weight:600;color:#374151;margin-bottom:8px;">Address</label>
                                    <input type="text" name="address" value="{{ old('address') }}" placeholder="Full address"
                                        style="width:100%;padding:12px;border:1px solid #D1D5DB;border-radius:10px;outline:none;">
                                    @error('address') <p style="color:#dc2626;font-size:13px;margin-top:6px;">{{ $message }}</p> @enderror
                                </div>
                            </div>

                            <div style="margin-top:16px;display:grid;grid-template-columns:1fr 1fr;gap:16px;">
                                <div>
                                    <label style="display:block;font-weight:600;color:#374151;margin-bottom:8px;">Phone</label>
                                    <input type="text" name="phone_1" value="{{ old('phone_1') }}" placeholder="+93 700 000 000"
                                        style="width:100%;padding:12px;border:1px solid #D1D5DB;border-radius:10px;outline:none;">
                                    @error('phone_1') <p style="color:#dc2626;font-size:13px;margin-top:6px;">{{ $message }}</p> @enderror
                                </div>
                                <div>
                                    <label style="display:block;font-weight:600;color:#374151;margin-bottom:8px;">WhatsApp</label>
                                    <input type="text" name="whatsapp" value="{{ old('whatsapp') }}" placeholder="+93700..."
                                        style="width:100%;padding:12px;border:1px solid #D1D5DB;border-radius:10px;outline:none;">
                                    @error('whatsapp') <p style="color:#dc2626;font-size:13px;margin-top:6px;">{{ $message }}</p> @enderror
                                </div>
                            </div>

                            <div style="margin-top:16px;display:grid;grid-template-columns:1fr 1fr;gap:16px;">
                                <div>
                                    <label style="display:block;font-weight:600;color:#374151;margin-bottom:8px;">Website</label>
                                    <input type="text" name="website" value="{{ old('website') }}" placeholder="https://example.com"
                                        style="width:100%;padding:12px;border:1px solid #D1D5DB;border-radius:10px;outline:none;">
                                    @error('website') <p style="color:#dc2626;font-size:13px;margin-top:6px;">{{ $message }}</p> @enderror
                                </div>
                                <div>
                                    <label style="display:block;font-weight:600;color:#374151;margin-bottom:8px;">Price Level</label>
                                    <select name="price_level"
                                        style="width:100%;padding:12px;border:1px solid #D1D5DB;border-radius:10px;outline:none;">
                                        <option value="low" {{ old('price_level') == 'low' ? 'selected' : '' }}>Low</option>
                                        <option value="medium" {{ old('price_level', 'medium') == 'medium' ? 'selected' : '' }}>Medium</option>
                                        <option value="high" {{ old('price_level') == 'high' ? 'selected' : '' }}>High</option>
                                        <option value="luxury" {{ old('price_level') == 'luxury' ? 'selected' : '' }}>Luxury</option>
                                    </select>
                                    @error('price_level') <p style="color:#dc2626;font-size:13px;margin-top:6px;">{{ $message }}</p> @enderror
                                </div>
                            </div>

                            <div style="margin-top:16px;display:grid;grid-template-columns:1fr 1fr;gap:16px;">
                                <div>
                                    <label style="display:block;font-weight:600;color:#374151;margin-bottom:8px;">Status</label>
                                    <select name="status"
                                        style="width:100%;padding:12px;border:1px solid #D1D5DB;border-radius:10px;outline:none;">
                                        <option value="open" {{ old('status', 'open') == 'open' ? 'selected' : '' }}>Open</option>
                                        <option value="closed" {{ old('status') == 'closed' ? 'selected' : '' }}>Closed</option>
                                        <option value="temporarily_closed" {{ old('status') == 'temporarily_closed' ? 'selected' : '' }}>Temporarily Closed</option>
                                    </select>
                                    @error('status') <p style="color:#dc2626;font-size:13px;margin-top:6px;">{{ $message }}</p> @enderror
                                </div>
                                <div>
                                    <label style="display:block;font-weight:600;color:#374151;margin-bottom:8px;">Tagline</label>
                                    <input type="text" name="tagline" value="{{ old('tagline') }}" placeholder="Reliable local service"
                                        style="width:100%;padding:12px;border:1px solid #D1D5DB;border-radius:10px;outline:none;">
                                    @error('tagline') <p style="color:#dc2626;font-size:13px;margin-top:6px;">{{ $message }}</p> @enderror
                                </div>
                            </div>

                            <div style="margin-top:16px;">
                                <label style="display:block;font-weight:600;color:#374151;margin-bottom:8px;">Description</label>
                                <textarea name="description" rows="6"
                                    style="width:100%;padding:12px;border:1px solid #D1D5DB;border-radius:10px;outline:none;">{{ old('description') }}</textarea>
                                @error('description') <p style="color:#dc2626;font-size:13px;margin-top:6px;">{{ $message }}</p> @enderror
                            </div>

                            <div style="margin-top:20px;display:grid;grid-template-columns:1fr 1fr;gap:16px;">
                                <div>
                                    <label style="display:block;font-weight:600;color:#374151;margin-bottom:8px;">Latitude</label>
                                    <input type="text" name="latitude" value="{{ old('latitude') }}" placeholder="34.3529"
                                        style="width:100%;padding:12px;border:1px solid #D1D5DB;border-radius:10px;outline:none;">
                                </div>
                                <div>
                                    <label style="display:block;font-weight:600;color:#374151;margin-bottom:8px;">Longitude</label>
                                    <input type="text" name="longitude" value="{{ old('longitude') }}" placeholder="62.2044"
                                        style="width:100%;padding:12px;border:1px solid #D1D5DB;border-radius:10px;outline:none;">
                                </div>
                            </div>

                            @guest
                                <div style="margin-top:24px;padding:20px;border:1px solid #E5E7EB;border-radius:14px;background:#F8FAFC;">
                                    <h4 style="font-size:18px;font-weight:700;color:#111827;margin-bottom:12px;">Your contact details</h4>
                                    <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;">
                                        <div>
                                            <label style="display:block;font-weight:600;color:#374151;margin-bottom:8px;">Your Name</label>
                                            <input type="text" name="submitted_by_name" value="{{ old('submitted_by_name') }}"
                                                style="width:100%;padding:12px;border:1px solid #D1D5DB;border-radius:10px;outline:none;">
                                            @error('submitted_by_name') <p style="color:#dc2626;font-size:13px;margin-top:6px;">{{ $message }}</p> @enderror
                                        </div>
                                        <div>
                                            <label style="display:block;font-weight:600;color:#374151;margin-bottom:8px;">Email</label>
                                            <input type="email" name="submitted_by_email" value="{{ old('submitted_by_email') }}"
                                                style="width:100%;padding:12px;border:1px solid #D1D5DB;border-radius:10px;outline:none;">
                                            @error('submitted_by_email') <p style="color:#dc2626;font-size:13px;margin-top:6px;">{{ $message }}</p> @enderror
                                        </div>
                                    </div>
                                </div>
                            @endguest

                            <div style="margin-top:28px;text-align:center;">
                                <button type="submit"
                                    style="background:#10B981;color:#fff;padding:14px 28px;border:none;border-radius:12px;font-weight:700;font-size:15px;cursor:pointer;">Submit Suggestion</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
