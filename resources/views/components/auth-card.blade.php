@props(['title', 'description' => null])

<div class="box-two mk-card auth-card">
    <div class="text-center mk-stack-sm">
        <img src="{{ asset('assets/img/branding/makanyab-logo-primary.svg') }}" alt="Makanyab" class="auth-card__logo">
    </div>

    <h1 class="mk-heading mk-heading--md text-center mk-stack-sm">
        {{ $title }}
    </h1>

    @if ($description)
        <p class="auth-card__description">{{ $description }}</p>
    @endif

    @if (session('status'))
        <div class="mk-alert mk-alert--success">
            {{ session('status') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="mk-alert flash-error">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{ $slot }}
</div>
