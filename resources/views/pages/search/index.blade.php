@extends('layouts.app')

@section('title', 'Search Places')

@section('content')

    <!-- Content -->
    <div class="content-area recent-property" style="background:#F8FAFC; padding:70px 0;">
        <div>

            <div class="row">

                <!-- Sidebar -->
                <div class="col-md-4">

                    <div class="box-two" style="padding:30px; border-radius:16px; margin-bottom:30px;">

                        <h3 style="font-size:28px; font-weight:700; color:#111827; margin-bottom:25px;">
                            Filters
                        </h3>

                        <form action="{{ route('search.index') }}" method="GET">

                            <!-- Search -->
                            <div class="form-group" style="margin-bottom:18px;">
                                <label style="font-weight:600; margin-bottom:8px; display:block;">Keyword</label>

                                <input type="text" name="search" value="{{ request('search') }}"
                                    placeholder="Name or description..." class="form-control"
                                    style="height:48px; border-radius:10px;">
                            </div>

                            <!-- Category -->
                            <div class="form-group" style="margin-bottom:18px;">
                                <label style="font-weight:600; margin-bottom:8px; display:block;">Category</label>

                                <select name="category" class="form-control" style="height:48px; border-radius:10px;">
                                    <option value="">All Categories</option>

                                    @foreach ($categories as $cat)
                                        <option value="{{ $cat->slug }}"
                                            {{ request('category') == $cat->slug ? 'selected' : '' }}>
                                            {{ $cat->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- City -->
                            <div class="form-group" style="margin-bottom:18px;">
                                <label style="font-weight:600; margin-bottom:8px; display:block;">City</label>

                                <input type="text" name="city" value="{{ request('city') }}" placeholder="City..."
                                    class="form-control" style="height:48px; border-radius:10px;">
                            </div>

                            <!-- Status -->
                            <div class="form-group" style="margin-bottom:18px;">
                                <label style="font-weight:600; margin-bottom:8px; display:block;">Status</label>

                                <select name="status" class="form-control" style="height:48px; border-radius:10px;">
                                    <option value="">Any Status</option>
                                    <option value="open" {{ request('status') == 'open' ? 'selected' : '' }}>Open</option>
                                    <option value="closed" {{ request('status') == 'closed' ? 'selected' : '' }}>Closed
                                    </option>
                                    <option value="temporarily_closed"
                                        {{ request('status') == 'temporarily_closed' ? 'selected' : '' }}>
                                        Temporarily Closed
                                    </option>
                                </select>
                            </div>

                            <!-- Market Level -->
                            <div class="form-group" style="margin-bottom:18px;">
                                <label style="font-weight:600; margin-bottom:8px; display:block;">Market Level</label>

                                <select name="market_level" class="form-control" style="height:48px; border-radius:10px;">
                                    <option value="">Any Level</option>
                                    <option value="low" {{ request('market_level') == 'low' ? 'selected' : '' }}>Small
                                    </option>
                                    <option value="medium" {{ request('market_level') == 'medium' ? 'selected' : '' }}>
                                        Medium</option>
                                    <option value="high" {{ request('market_level') == 'high' ? 'selected' : '' }}>Large
                                    </option>
                                    <option value="luxury" {{ request('market_level') == 'luxury' ? 'selected' : '' }}>Mall
                                    </option>
                                </select>
                            </div>

                            <!-- Verified -->
                            <div class="form-group" style="margin-bottom:25px;">
                                <label style="font-weight:600;">
                                    <input type="checkbox" name="verified" value="1"
                                        {{ request('verified') ? 'checked' : '' }}>
                                    Verified Only
                                </label>
                            </div>

                            <!-- Submit -->
                            <button type="submit"
                                style="width:100%; height:50px; border:none; border-radius:10px; background:#10B981; color:#fff; font-weight:700;">
                                Search Now
                            </button>

                            <a href="{{ route('search.index') }}"
                                style="display:block; text-align:center; margin-top:12px; color:#6B7280; text-decoration:none;">
                                Reset Filters
                            </a>

                        </form>

                    </div>
                </div>
                <!-- Results -->
                <div class="col-md-8">
                    <!-- Header -->
                    <div class="box-two" style="padding:25px 30px; border-radius:16px; margin-bottom:30px;">
                        <div class="row">
                            <div class="col-md-6">
                                <h3 style="font-size:28px; font-weight:700; color:#111827; margin:0;">
                                    Search Results
                                </h3>
                            </div>

                            <div class="col-md-6 text-right">
                                <span style="color:#6B7280;">
                                    {{ $places->total() }} results found
                                </span>
                            </div>
                        </div>
                    </div>
                    <!-- Places -->
                    @forelse($places as $place)
                        <div class="box-two" style="padding:0; border-radius:16px; overflow:hidden; margin-bottom:30px;">
                            <div class="row">
                                <!-- Image -->
                                <div class="col-md-4">
                                    <a href="{{ route('places.show', $place) }}">
                                        @if ($place->media->first())
                                            <img src="{{ asset('storage/' . $place->media->first()->file_path) }}"
                                                style="width:100%; height:240px; object-fit:cover;">
                                        @else
                                            <img src="{{ asset('assets/img/demo/property-1.jpg') }}"
                                                style="width:100%; height:240px; object-fit:cover;">
                                        @endif
                                    </a>
                                </div>
                                <!-- Info -->
                                <div class="col-md-8">
                                    <div style="padding:28px;">
                                        <div style="margin-bottom:10px;">
                                            @if ($place->category)
                                                <span
                                                    style="background:#ECFDF5; color:#10B981; padding:6px 12px; border-radius:50px; font-size:13px; font-weight:600;">
                                                    {{ $place->category->name }}
                                                </span>
                                            @endif
                                            @if ($place->is_verified)
                                                <span
                                                    style="margin-left:8px; color:#10B981; font-size:13px; font-weight:700;">
                                                    ✓ Verified
                                                </span>
                                            @endif
                                        </div>
                                        <h2 style="font-size:28px; font-weight:700; margin-bottom:12px;">
                                            <a href="{{ route('places.show', $place) }}"
                                                style="color:#111827; text-decoration:none;">
                                                {{ $place->name }}
                                            </a>
                                        </h2>
                                        <div style="font-size:15px; color:#6B7280; margin-bottom:10px;">
                                            📍 {{ $place->city }}
                                            @if ($place->district)
                                                , {{ $place->district }}
                                            @endif
                                        </div>
                                        <div style="margin-bottom:15px;">
                                            @include('components.rating-stars', [
                                                'rating' => $place->avg_rating ?? 0,
                                            ])
                                        </div>
                                        <p style="font-size:15px; color:#6B7280; line-height:1.8; margin-bottom:18px;">
                                            {{ Str::limit(strip_tags($place->description), 140) }}
                                        </p>
                                        <div class="row">
                                            <div class="col-md-6">
                                                @include('components.status-badge', [
                                                    'status' => $place->status,
                                                ])
                                            </div>
                                            <div class="col-md-6 text-right">
                                                <a href="{{ route('places.show', $place) }}"
                                                    style="display:inline-block; background:#10B981; color:#fff; padding:10px 24px; border-radius:10px; text-decoration:none; font-weight:600;">
                                                    View Details
                                                </a>
                                            </div>
                                        </div>

                                    </div>
                                </div>

                            </div>

                        </div>

                    @empty

                        <div class="box-two text-center" style="padding:70px 30px; border-radius:16px;">

                            <div style="font-size:70px; margin-bottom:20px;">🔍</div>

                            <h3 style="font-size:30px; font-weight:700; color:#111827; margin-bottom:15px;">
                                No Places Found
                            </h3>

                            <p style="font-size:16px; color:#6B7280;">
                                Try changing filters or search another keyword.
                            </p>

                        </div>
                    @endforelse

                    <!-- Pagination -->
                    <div class="text-center">
                        {{ $places->appends(request()->query())->links() }}
                    </div>

                </div>

            </div>

        </div>
    </div>

@endsection
