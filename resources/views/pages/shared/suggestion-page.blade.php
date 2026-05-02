@props([
    'title',
    'description',
    'switchText',
    'switchRoute',
    'switchLinkText',
    'formTitle',
    'formAction',
    'categoryField',
    'categoryLabel',
    'categories' => [],
    'submitText' => 'Submit',
])

<div style="background:linear-gradient(135deg,#064e3b,#10B981);padding:50px 0;">
    <div class="container">
        <div class="row">
            <div class="col-md-10 col-md-offset-1 text-center">
                <h1 style="font-size:36px;font-weight:800;color:#fff;margin-bottom:10px;">{{ $title }}</h1>
                <p style="color:rgba(255,255,255,.85);font-size:16px;">{{ $description }}</p>
                <p style="color:rgba(255,255,255,.75);font-size:14px;">
                    {{ $switchText }}
                    <a href="{{ route($switchRoute) }}"
                        style="color:#D1FAE5;text-decoration:underline;">{{ $switchLinkText }}</a>.
                </p>
            </div>
        </div>
    </div>
</div>

<div style="background:#F8FAFC;padding:60px 0;">
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                @if (session('success'))
                    <div
                        style="background:#D1FAE5;border:1px solid #10B981;color:#065F46;padding:18px;border-radius:14px;margin-bottom:24px;">
                        {{ session('success') }}
                    </div>
                @endif

                <div style="background:#fff;border-radius:18px;padding:32px;border:1px solid #E5E7EB;">
                    <h3 style="font-size:22px;font-weight:700;color:#111827;margin-bottom:18px;">{{ $formTitle }}
                    </h3>

                    @include('pages.shared.suggestion-form', [
                        'action' => $formAction,
                        'categoryField' => $categoryField,
                        'categoryLabel' => $categoryLabel,
                        'categories' => $categories,
                        'submitText' => $submitText,
                    ])
                </div>
            </div>
        </div>
    </div>
</div>
