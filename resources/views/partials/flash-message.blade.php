@if (session('success') && ! request()->routeIs('add.create', 'add.edit'))
    <div class="mk-alert mk-alert--success flash-message {{ in_array(session('success'), [__('messages.review_submitted'), __('messages.review_updated')], true) ? 'flash-message--centered' : '' }}" role="status">
        {{ session('success') }}
    </div>
@endif

@if (session('error'))
    <div class="mk-alert mk-ui-alert--danger flash-message">
        {{ session('error') }}
    </div>
@endif
