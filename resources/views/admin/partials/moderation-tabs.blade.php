<nav class="admin-status-nav" aria-label="{{ __('admin.navigation.'.$type) }}">
    @foreach (['all', 'pending', 'approved', 'rejected'] as $tab)
        <a href="{{ route('admin.'.$type.'.'.($tab === 'all' ? 'index' : $tab)) }}"
            class="admin-status-nav__link {{ $activeSection === $tab ? 'is-active' : '' }}"
            @if ($activeSection === $tab) aria-current="page" @endif>
            {{ __('admin.moderation.'.$tab.'_'.$type) }}
        </a>
    @endforeach
</nav>
