@extends('layouts.app')

@section('title', $post->title)

@section('content')
    <div class="content-area" style="background:#F8FAFC; padding:50px 0;">
        <div class="container">
            <div class="row" style="margin-bottom:40px;">
                <div class="col-md-12">
                    <div class="box-two" style="padding:35px 30px; border-radius:16px; text-align:center;">
                        <h1 style="font-size:34px; font-weight:700; color:#111827; margin-bottom:10px;">{{ $post->title }}</h1>
                        <p style="color:#6B7280; font-size:15px; margin:0;">Published {{ $post->created_at->format('F d, Y') }}</p>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-8">
                    <div class="box-two" style="padding:0; border-radius:16px; overflow:hidden; margin-bottom:30px;">
                        @if ($post->image)
                            <img src="{{ asset('storage/' . $post->image) }}" style="width:100%; max-height:500px; object-fit:cover;">
                        @endif
                    </div>

                    <div class="box-two" style="padding:35px; border-radius:16px; margin-bottom:30px;">
                        <div style="color:#4B5563; line-height:1.9; font-size:16px;">
                            {!! nl2br(e($post->content)) !!}
                        </div>
                    </div>

                    <a href="{{ route('posts.index') }}" style="display:inline-block; background:#F3F4F6; color:#111827; border-radius:10px; padding:12px 24px; text-decoration:none; font-weight:600;">
                        ← Back to Articles
                    </a>
                </div>

                <div class="col-md-4">
                    <div class="box-two" style="padding:30px; border-radius:16px; margin-bottom:30px;">
                        <h3 style="font-size:24px; font-weight:700; color:#111827; margin-bottom:18px;">Recent Articles</h3>
                        @if(isset($recentPosts) && $recentPosts->count())
                            @foreach($recentPosts as $recent)
                                <a href="{{ route('posts.show', $recent->slug) }}" style="display:block; color:#111827; text-decoration:none; margin-bottom:18px;">
                                    <strong>{{ Str::limit($recent->title, 55) }}</strong>
                                    <div style="font-size:14px; color:#6B7280; margin-top:4px;">{{ $recent->created_at->format('M d, Y') }}</div>
                                </a>
                            @endforeach
                        @else
                            <p style="color:#6B7280; margin:0;">No recent articles available.</p>
                        @endif
                    </div>
                    <div class="box-two" style="padding:30px; border-radius:16px;">
                        <h3 style="font-size:24px; font-weight:700; color:#111827; margin-bottom:18px;">Need assistance?</h3>
                        <p style="color:#6B7280; line-height:1.8; margin-bottom:20px;">Have a question about our content or want to submit a story? Contact our team and we will help you.</p>
                        <a href="{{ route('contact') }}" style="display:inline-block; background:#10B981; color:#fff; text-decoration:none; padding:12px 26px; border-radius:10px; font-weight:600;">Contact Support</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
