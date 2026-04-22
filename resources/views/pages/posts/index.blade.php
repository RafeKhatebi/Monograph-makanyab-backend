@extends('layouts.app')

@section('title', 'Blog & Posts')

@section('content')
    <div class="content-area" style="background:#F8FAFC; padding:50px 0;">
        <div class="container">
            <div class="row">
                <div class="col-md-4">
                    @if (isset($posts) && $posts->count())
                        @foreach ($posts as $post)
                            <div class="box-two"
                                style="padding:0; border-radius:16px; overflow:hidden; margin-bottom:35px;">
                                <a href="{{ route('posts.show', $post->slug) }}">
                                    <img src="{{ $post->image ? asset('storage/' . $post->image) : asset('assets/img/demo-property-1.jpg') }}"
                                        alt="{{ $post->title }}" style="width:100%; height:320px; object-fit:cover;">
                                </a>

                                <div style="padding:30px;">
                                    <div
                                        style="margin-bottom:15px; display:flex; flex-wrap:wrap; gap:10px; align-items:center;">
                                        <span
                                            style="background:#ECFDF5; color:#10B981; padding:8px 16px; border-radius:999px; font-size:13px; font-weight:600;">{{ $post->category->name ?? 'News' }}</span>
                                        <span
                                            style="color:#9CA3AF; font-size:14px;">{{ $post->created_at->format('M d, Y') }}</span>
                                    </div>

                                    <h2 style="font-size:30px; font-weight:700; line-height:1.4; margin-bottom:15px;">
                                        <a href="{{ route('posts.show', $post->slug) }}"
                                            style="color:#111827; text-decoration:none;">{{ $post->title }}</a>
                                    </h2>

                                    <p style="font-size:16px; color:#6B7280; line-height:1.9; margin-bottom:25px;">
                                        {{ Str::limit(strip_tags($post->content), 180) }}
                                    </p>

                                    <a href="{{ route('posts.show', $post->slug) }}"
                                        style="display:inline-block; background:#10B981; color:#fff; text-decoration:none; padding:12px 26px; border-radius:10px; font-weight:600;">
                                        Read More
                                    </a>
                                </div>
                            </div>
                        @endforeach

                        <div class="text-center">
                            {{ $posts->links() }}
                        </div>
                    @else
                        <div class="box-two text-center" style="padding:60px 30px; border-radius:16px;">
                            <div style="font-size:70px; margin-bottom:20px;">📝</div>
                            <h3 style="font-size:28px; font-weight:700; color:#111827; margin-bottom:15px;">No Posts Found
                            </h3>
                            <p style="font-size:16px; color:#6B7280; margin:0;">New articles and updates will be published
                                soon.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
