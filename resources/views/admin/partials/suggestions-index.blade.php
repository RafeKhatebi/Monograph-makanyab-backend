<section class="card" aria-label="{{ $pageTitle }}">
    <div class="card-header admin-card-header">
        <h2 class="admin-card-title">{{ $pageTitle }} ({{ $suggestions->total() }})</h2>
    </div>
    <div class="card-body">
        @include('admin.partials.moderation-tabs', ['type' => $type, 'activeSection' => $section])

        <form method="GET" action="{{ url()->current() }}" role="search" aria-label="{{ __('admin.suggestions.filter_aria') }}" class="admin-filter-form">
            @if (request()->routeIs('admin.'.$suggestionType.'.index') && $section !== 'all')
                <input type="hidden" name="status" value="{{ $section }}">
            @endif
            <div class="admin-filter-field">
                <label for="search" class="sr-only">{{ __('admin.suggestions.search_label') }}</label>
                <input type="search" id="search" name="search" value="{{ request('search') }}"
                    placeholder="{{ __('admin.suggestions.search_placeholder') }}" class="form-control">
            </div>
            <button type="submit" class="btn btn-primary">
                <i class="fa fa-filter" aria-hidden="true"></i> {{ __('admin.crud.filter') }}
            </button>
            <a href="{{ route('admin.'.$type.'.'.($section === 'all' ? 'index' : $section)) }}" class="btn btn-outline-secondary">{{ __('admin.crud.clear') }}</a>
        </form>

        <div class="admin-table-wrap">
            <table class="table" aria-label="{{ $pageTitle }}">
                <thead>
                    <tr>
                        <th scope="col">{{ __('admin.suggestions.name') }}</th>
                        <th scope="col">{{ __('admin.suggestions.city') }}</th>
                        <th scope="col">{{ __('admin.suggestions.category') }}</th>
                        <th scope="col">{{ __('admin.suggestions.submitted_by') }}</th>
                        <th scope="col">{{ __('admin.suggestions.status') }}</th>
                        <th scope="col" class="admin-table-actions">{{ __('admin.suggestions.actions') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($suggestions as $suggestion)
                        <tr>
                            <td>{{ $suggestion->name }}</td>
                            <td>{{ $suggestion->city }}</td>
                            <td>{{ $suggestion->category->name ?? '—' }}</td>
                            <td>
                                {{ $suggestion->submitted_by_name ?? ($suggestion->user->name ?? __('admin.suggestions.guest')) }}
                                <br>
                                <small class="admin-inline-muted">{{ $suggestion->submitted_by_email ?? ($suggestion->user->email ?? '') }}</small>
                            </td>
                            <td>
                                <span class="badge {{ $suggestion->suggestion_status?->value === 'approved' ? 'badge-success' : ($suggestion->suggestion_status?->value === 'rejected' ? 'badge-danger' : 'badge-warning') }}">
                                    {{ $suggestion->suggestion_status?->label() }}
                                </span>
                            </td>
                            <td class="admin-table-actions">
                                <div class="admin-actions admin-actions--end">
                                    <a href="{{ route('admin.'.$suggestionType.'.show', $suggestion) }}" class="btn btn-sm btn-outline-primary"
                                        aria-label="{{ __('admin.suggestions.view_aria', ['name' => $suggestion->name]) }}">{{ __('common.actions.view') }}</a>
                                    @if ($suggestion->suggestion_status?->value === 'pending')
                                        <form action="{{ route('admin.'.$suggestionType.'.approve', $suggestion) }}" method="POST" class="admin-action-form">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-outline-success">{{ __('admin.suggestions.approve') }}</button>
                                        </form>
                                        <form action="{{ route('admin.'.$suggestionType.'.reject', $suggestion) }}" method="POST" class="admin-action-form">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-outline-danger">{{ __('admin.suggestions.reject') }}</button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="admin-empty">{{ __('admin.suggestions.empty') }}</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($suggestions->hasPages())
            <nav class="admin-pagination" aria-label="{{ __('admin.suggestions.pagination') }}">
                {{ $suggestions->links() }}
            </nav>
        @endif
    </div>
</section>
