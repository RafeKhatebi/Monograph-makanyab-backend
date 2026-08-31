<div class="box-two mk-card auth-card">
    <div class="text-center mk-stack-xs">
        <img src="{{ asset('assets/img/branding/makanyab-logo-primary.svg') }}" alt="Makanyab" class="auth-card__logo">
    </div>

    <h3 class="mk-heading mk-heading--md text-center mk-stack-xs">
        {{ $title }}
    </h3>

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
