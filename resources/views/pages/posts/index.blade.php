@extends('layouts.app')

@section('title', 'Blog & Posts')

@section('content')

    <!-- Hero -->
    <div class="page-head" style="background:linear-gradient(135deg,#10B981 0%,#059669 100%); padding:70px 0;">
        <div class="container">
            <div class="row">
                <div class="col-md-12 text-center">
                    <h1 style="font-size:42px; font-weight:700; color:#ffffff; margin-bottom:15px;">
                        Latest Posts
                    </h1>

                    <p style="font-size:18px; color:rgba(255,255,255,.92); max-width:760px; margin:auto; line-height:1.8;">
                        Discover travel tips, food guides, local stories, and updates from Makanyab.
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Content -->
    <div class="content-area" style="background:#F8FAFC; padding:70px 0;">
        <div class="container">

            <div class="row">

                <!-- Posts List -->
                <div class="col-md-8">

                    @if (isset($posts) && count($posts))

                        @foreach ($posts as $post)
                            <div class="box-two"
                                style="padding:0; border-radius:16px; overflow:hidden; margin-bottom:35px;">

                                <!-- Image -->
                                <a href="{{ route('posts.show', $post->slug) }}">
                                    <img src="{{ $post->image ? asset('storage/' . $post->image) : asset('assets/img/demo-property-1.jpg') }}"
                                        alt="{{ $post->title }}" style="width:100%; height:320px; object-fit:cover;">
                                </a>

                                <!-- Content -->
                                <div style="padding:30px;">

                                    <div style="margin-bottom:15px;">
                                        <span
                                            style="background:#ECFDF5; color:#10B981; padding:6px 14px; border-radius:50px; font-size:13px; font-weight:600;">
                                            {{ $post->category->name ?? 'News' }}
                                        </span>

                                        <span style="color:#9CA3AF; margin-left:10px; font-size:14px;">
                                            {{ $post->created_at->format('M d, Y') }}
                                        </span>
                                    </div>

                                    <h2 style="font-size:30px; font-weight:700; line-height:1.4; margin-bottom:15px;">
                                        <a href="{{ route('posts.show', $post->slug) }}"
                                            style="color:#111827; text-decoration:none;">
                                            {{ $post->title }}
                                        </a>
                                    </h2>

                                    <p style="font-size:16px; color:#6B7280; line-height:1.9; margin-bottom:25px;">
                                        {{ Str::limit(strip_tags($post->content), 180) }}
                                    </p>

                                    <a href="{{ route('posts.show', $post->slug) }}"
                                        style="display:inline-block; background:#10B981; color:#fff; text-decoration:none; padding:12px 28px; border-radius:10px; font-weight:600;">
                                        Read More
                                    </a>

                                </div>
                            </div>
                        @endforeach

                        <!-- Pagination -->
                        <div class="text-center">
                            {{ $posts->links() }}
                        </div>
                    @else
                        <div class="box-two text-center" style="padding:60px 30px; border-radius:16px;">
                            <div style="font-size:70px; margin-bottom:20px;">📝</div>

                            <h3 style="font-size:28px; font-weight:700; color:#111827; margin-bottom:15px;">
                                No Posts Found
                            </h3>

                            <p style="font-size:16px; color:#6B7280; margin:0;">
                                New articles and updates will be published soon.
                            </p>
                        </div>

                    @endif

                </div>

                <!-- Sidebar -->
                <div class="col-md-4">

                    <!-- Search -->
                    <div class="box-two" style="padding:30px; border-radius:16px; margin-bottom:30px;">
                        <h3 style="font-size:24px; font-weight:700; color:#111827; margin-bottom:20px;">
                            Search
                        </h3>

                        <form action="{{ route('posts.index') }}" method="GET">
                            <input type="text" name="search" value="{{ request('search') }}"
                                placeholder="Search posts..." class="form-control"
                                style="height:50px; border-radius:10px; margin-bottom:15px;">

                            <button type="submit"
                                style="width:100%; background:#10B981; color:#fff; border:none; height:48px; border-radius:10px; font-weight:600;">
                                Search
                            </button>
                        </form>
                    </div>

                    <!-- Recent Posts -->
                    <div class="box-two" style="padding:30px; border-radius:16px; margin-bottom:30px;">
                        <h3 style="font-size:24px; font-weight:700; color:#111827; margin-bottom:20px;">
                            Recent Posts
                        </h3>

                        @if (isset($recentPosts))
                            @foreach ($recentPosts as $recent)
                                <div style="padding:14px 0; border-bottom:1px solid #F3F4F6;">

                                    <a href="{{ route('posts.show', $recent->slug) }}"
                                        style="font-size:16px; font-weight:600; color:#111827; text-decoration:none; line-height:1.6;">
                                        {{ $recent->title }}
                                    </a>

                                    <div style="font-size:13px; color:#9CA3AF; margin-top:5px;">
                                        {{ $recent->created_at->format('M d, Y') }}
                                    </div>

                                </div>
                            @endforeach
                        @endif
                    </div>

                    <!-- CTA -->
                    <div class="box-two text-center"
                        style="padding:35px 25px; border-radius:16px; background:linear-gradient(135deg,#10B981 0%,#059669 100%);">

                        <h3 style="font-size:26px; font-weight:700; color:#ffffff; margin-bottom:15px;">
                            Explore Places
                        </h3>

                        <p style="font-size:15px; color:rgba(255,255,255,.92); line-height:1.8; margin-bottom:20px;">
                            Find the best restaurants, cafes, and attractions near you.
                        </p>

                        <a href="{{ route('places.index') }}"
                            style="display:inline-block; background:#ffffff; color:#10B981; text-decoration:none; padding:12px 26px; border-radius:10px; font-weight:700;">
                            Browse Places
                        </a>

                    </div>

                </div>

            </div>

        </div>
    </div>

@endsection
