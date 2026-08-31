@if (session('success'))
    <div class="mk-alert mk-alert--success flash-message">
        {{ session('success') }}
    </div>
@endif

@if (session('error'))
    <div class="mk-alert mk-ui-alert--danger flash-message">
        {{ session('error') }}
    </div>
@endif
