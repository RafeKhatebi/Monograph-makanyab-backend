@extends('layouts.admin')

@section('title', __('admin.suggestions.service_details'))
@section('page-title', __('admin.suggestions.service_details'))

@section('content')
    <div class="card">
        <div class="card-header admin-card-header">
            <div>
                <h3 class="admin-card-title">{{ $serviceSuggestion->name }}</h3>
                <p class="admin-detail-value admin-table-muted">{{ __('admin.suggestions.submitted_by_name', ['name' => $serviceSuggestion->submitted_by_name ?? ($serviceSuggestion->user->name ?? __('admin.suggestions.guest'))]) }}</p>
            </div>
            <a href="{{ route('admin.service-suggestions.index') }}" class="btn btn-secondary">{{ __('admin.suggestions.back') }}</a>
        </div>

        <div class="card-body">
            <div class="admin-suggestion-grid">
                <div>
                    <table class="table table-borderless">
                        <tbody>
                            <tr>
                                <th>{{ __('admin.suggestions.name') }}</th>
                                <td>{{ $serviceSuggestion->name }}</td>
                            </tr>
                            <tr>
                                <th>{{ __('admin.suggestions.category') }}</th>
                                <td>{{ $serviceSuggestion->category->name ?? '—' }}</td>
                            </tr>
                            <tr>
                                <th>{{ __('admin.suggestions.city') }}</th>
                                <td>{{ $serviceSuggestion->city }}</td>
                            </tr>
                            <tr>
                                <th>{{ __('admin.suggestions.province') }}</th>
                                <td>{{ $serviceSuggestion->province }}</td>
                            </tr>
                            <tr>
                                <th>{{ __('admin.suggestions.address') }}</th>
                                <td>{{ $serviceSuggestion->address }}</td>
                            </tr>
                            <tr>
                                <th>{{ __('admin.suggestions.phone') }}</th>
                                <td>{{ $serviceSuggestion->phone_1 }} @if ($serviceSuggestion->phone_2)
                                        , {{ $serviceSuggestion->phone_2 }}
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <th>{{ __('admin.suggestions.whatsapp') }}</th>
                                <td>{{ $serviceSuggestion->whatsapp ?? '—' }}</td>
                            </tr>
                            <tr>
                                <th>{{ __('admin.suggestions.website') }}</th>
                                <td>{{ $serviceSuggestion->website ?? '—' }}</td>
                            </tr>
                            <tr>
                                <th>{{ __('admin.suggestions.status') }}</th>
                                <td>{{ __('common.status.'.$serviceSuggestion->status->value) }}</td>
                            </tr>
                            <tr>
                                <th>{{ __('admin.suggestions.price_level') }}</th>
                                <td>{{ __('common.price.'.$serviceSuggestion->price_level->value) }}</td>
                            </tr>
                            <tr>
                                <th>{{ __('admin.suggestions.suggestion_state') }}</th>
                                <td>{{ $serviceSuggestion->suggestion_status->label() }}</td>
                            </tr>
                            <tr>
                                <th>{{ __('admin.suggestions.submitted') }}</th>
                                <td>{{ \App\Support\LocalizedDate::date($serviceSuggestion->created_at) }}</td>
                            </tr>
                        </tbody>
                    </table>

                    <div class="admin-section">
                        <h4 class="admin-section-title">{{ __('admin.suggestions.description') }}</h4>
                        <p>{{ $serviceSuggestion->description ?? __('admin.suggestions.no_description') }}</p>
                    </div>
                </div>

                <div>
                    <div class="admin-action-panel">
                        <h4 class="admin-section-title">{{ __('admin.suggestions.review_actions') }}</h4>
                        @if ($serviceSuggestion->suggestion_status?->value === 'pending')
                            <form action="{{ route('admin.service-suggestions.approve', $serviceSuggestion) }}"
                                method="POST" class="admin-form-block">
                                @csrf
                                <div class="form-group">
                                    <label class="form-label">{{ __('admin.suggestions.admin_note') }}</label>
                                    <textarea name="admin_note" rows="4"
                                        class="form-control">{{ old('admin_note') }}</textarea>
                                </div>
                                <button type="submit" class="btn btn-success admin-btn-block">{{ __('admin.suggestions.approve_publish') }}</button>
                            </form>

                            <form action="{{ route('admin.service-suggestions.reject', $serviceSuggestion) }}"
                                method="POST">
                                @csrf
                                <div class="form-group">
                                    <label class="form-label">{{ __('admin.suggestions.rejection_note') }}</label>
                                    <textarea name="admin_note" rows="4"
                                        class="form-control">{{ old('admin_note') }}</textarea>
                                </div>
                                <button type="submit" class="btn btn-danger admin-btn-block">{{ __('admin.suggestions.reject_suggestion') }}</button>
                            </form>
                        @else
                            <div class="admin-note-box">
                                <strong>{{ __('admin.suggestions.status') }}:</strong> {{ $serviceSuggestion->suggestion_status->label() }}
                                <p class="admin-help-text">
                                    {{ $serviceSuggestion->admin_note ?? __('admin.suggestions.no_note_provided') }}</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
