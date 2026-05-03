<div class="col-sm-6 col-md-4 p-2">
    <div
        style="background:#fff;border-radius:14px;overflow:hidden;border:1px solid #E5E7EB;height:100%;display:flex;flex-direction:column;transition:box-shadow .2s;">
        <div style="position:relative;overflow:hidden;height:180px;">
            <a href="{{ route('places.show', $place) }}">
                @if ($place->media->first())
                    <img src="{{ asset('storage/' . $place->media->first()->file_path) }}" alt="{{ $place->name }}"
                        style="width:100%;height:180px;object-fit:cover;transition:transform .3s;">
                @else
                    <img src="{{ asset('assets/img/demo/property-1.jpg') }}" alt="{{ $place->name }}"
                        style="width:100%;height:180px;object-fit:cover;">
                @endif
            </a>

            <div style="position:absolute;top:10px;left:10px;">
                @if ($place->is_verified)
                    <span
                        style="background:rgba(16,185,129,.9);color:#fff;padding:3px 10px;border-radius:20px;font-size:11px;font-weight:600;">
                        <i class="fa fa-check-circle"></i> Verified
                    </span>
                @endif
            </div>

            @auth
                <div style="position:absolute;top:10px;right:10px;">
                    <form method="POST" action="{{ route('favorites.toggle') }}">
                        @csrf
                        <input type="hidden" name="place_id" value="{{ $place->id }}">
                        <button type="submit"
                            style="width:35px;height:35px;border-radius:50%;border:none;background:rgba(255,255,255,.95);box-shadow:0 4px 12px rgba(0,0,0,.08);display:flex;align-items:center;justify-content:center;">
                            <i class="fa {{ $place->is_favorited ? 'fa-heart text-danger' : 'fa-heart-o' }}"></i>
                        </button>
                    </form>
                </div>
            @endauth
        </div>

        <div style="padding:14px 16px;flex:1;display:flex;flex-direction:column;">
            <div style="display:flex;justify-content:space-between;align-items:flex-start;margin-bottom:6px;">
                <h5 style="margin:0;font-size:15px;font-weight:700;color:#111827;line-height:1.3;">
                    <a href="{{ route('places.show', $place) }}"
                        style="color:inherit;text-decoration:none;">{{ $place->name }}</a>
                </h5>
                @if ($place->category)
                    <span
                        style="background:#F0FDF4;color:#10B981;padding:2px 8px;border-radius:20px;font-size:11px;font-weight:600;white-space:nowrap;margin-left:6px;">{{ $place->category->name }}</span>
                @endif
            </div>

            <p style="margin:0 0 8px;font-size:13px;color:#6B7280;">
                <i class="fa fa-map-marker" style="color:#EF4444;margin-right:4px;"></i>
                {{ $place->city }}@if ($place->district)
                    , {{ $place->district }}
                @endif
            </p>

            @if ($place->tagline)
                <p style="margin:0 0 10px;font-size:13px;color:#6B7280;line-height:1.5;flex:1;">
                    {{ Str::limit($place->tagline, 70) }}</p>
            @endif

            <div
                style="display:flex;justify-content:space-between;align-items:center;margin-top:auto;padding-top:10px;border-top:1px solid #F3F4F6;">
                <span style="font-size:12px;color:#9CA3AF;">
                    {{ ucfirst(str_replace('_', ' ', $place->price_level)) }}
                </span>
                <a href="{{ route('places.show', $place) }}"
                    style="background:#10B981;color:#fff;padding:5px 14px;border-radius:8px;font-size:13px;font-weight:600;text-decoration:none;">View</a>
            </div>
        </div>
    </div>
</div>
