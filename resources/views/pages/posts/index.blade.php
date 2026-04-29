@extends('layouts.app')
@section('title', 'Blog & Posts')
@section('content')

    {{-- Header --}}
    <div style="background:linear-gradient(135deg,#064e3b,#10B981);padding:40px 0;">
        <div class="container">
            <h1 style="font-size:30px;font-weight:800;color:#fff;margin:0 0 6px;">Blog & Articles</h1>
            <p style="color:rgba(255,255,255,.8);margin:0;font-size:15px;">Latest news, updates and stories from Makanyab.
            </p>
        </div>
    </div>

    <div style="background:#F8FAFC;padding:50px 0 70px;">
        <div class="container">
            @if (isset($posts) && $posts->count())
                <div class="row">
                    @foreach ($posts as $post)
                        <div class="col-md-4 col-sm-6" style="margin-bottom:28px;">
                            <div class="post-card"
                                style="background:#fff;border-radius:14px;overflow:hidden;border:1px solid #E5E7EB;height:100%;display:flex;flex-direction:column;transition:box-shadow .2s;">
                                <a href="{{ route('posts.show', $post->slug) }}">
                                    <img src="{{ $post->image ? asset('storage/' . $post->image) : asset('assets/img/demo/property-1.jpg') }}"
                                        alt="{{ $post->title }}" style="width:100%;height:200px;object-fit:cover;">
                                </a>
                                <div style="padding:20px;flex:1;display:flex;flex-direction:column;">
                                    <div style="display:flex;align-items:center;gap:10px;margin-bottom:12px;">
                                        <span
                                            style="background:#ECFDF5;color:#10B981;padding:4px 12px;border-radius:20px;font-size:12px;font-weight:600;">
                                            {{ $post->category->name ?? 'News' }}
                                        </span>
                                        <span
                                            style="color:#9CA3AF;font-size:13px;">{{ $post->created_at->format('M d, Y') }}</span>
                                    </div>
                                    <h3
                                        style="font-size:17px;font-weight:700;color:#111827;margin-bottom:10px;line-height:1.4;flex:1;">
                                        <a href="{{ route('posts.show', $post->slug) }}"
                                            style="color:inherit;text-decoration:none;">{{ $post->title }}</a>
                                    </h3>
                                    <p style="font-size:14px;color:#6B7280;line-height:1.7;margin-bottom:16px;">
                                        {{ Str::limit(strip_tags($post->content), 120) }}
                                    </p>
                                    <a href="{{ route('posts.show', $post->slug) }}"
                                        style="display:inline-block;background:#10B981;color:#fff;padding:9px 20px;border-radius:8px;font-weight:600;font-size:13px;text-decoration:none;align-self:flex-start;">
                                        Read More
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                <div style="text-align:center;margin-top:20px;">{{ $posts->links() }}</div>
            @else
                <div style="text-align:center;padding:80px 0;">
                    <div style="font-size:56px;margin-bottom:16px;">📝</div>
                    <h3 style="font-size:22px;font-weight:700;color:#111827;margin-bottom:12px;">No Posts Yet</h3>
                    <p style="color:#6B7280;">New articles and updates will be published soon.</p>
                </div>
            @endif
        </div>
    </div>

    @push('styles')
        <style>
            .post-card:hover {
                box-shadow: 0 4px 20px rgba(0, 0, 0, .08);
            }
        </style>
    @endpush

@endsection
