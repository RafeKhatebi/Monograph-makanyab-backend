@extends('layouts.admin')

@section('title', __('admin.suggestions.place_details'))
@section('page-title', __('admin.suggestions.place_details'))

@section('content')
    <div class="card">
        <div class="card-header admin-card-header">
            <div>
                <h3 class="admin-card-title">{{ $placeSuggestion->name }}</h3>
                <p class="admin-detail-value admin-table-muted">{{ __('admin.suggestions.submitted_by_name', ['name' => $placeSuggestion->submitted_by_name ?? ($placeSuggestion->user->name ?? __('admin.suggestions.guest'))]) }}</p>
            </div>
            <a href="{{ route('admin.place-suggestions.index') }}" class="btn btn-secondary">{{ __('admin.suggestions.back') }}</a>
        </div>
        <div class="card-body">
            <div class="admin-suggestion-grid">
                <div>
                    <table class="table table-borderless">
                        <tbody>
                            <tr>
                                <th>{{ __('admin.suggestions.name') }}</th>
                                <td>{{ $placeSuggestion->name }}</td>
                            </tr>
                            <tr>
                                <th>{{ __('admin.suggestions.category') }}</th>
                                <td>{{ $placeSuggestion->category->name ?? '—' }}</td>
                            </tr>
                            <tr>
                                <th>{{ __('admin.suggestions.city') }}</th>
                                <td>{{ $placeSuggestion->city }}</td>
                            </tr>
                            <tr>
                                <th>{{ __('admin.suggestions.province') }}</th>
                                <td>{{ $placeSuggestion->province }}</td>
                            </tr>
                            <tr>
                                <th>{{ __('admin.suggestions.address') }}</th>
                                <td>{{ $placeSuggestion->address }}</td>
                            </tr>
                            <tr>
                                <th>{{ __('admin.suggestions.phone') }}</th>
                                <td>{{ $placeSuggestion->phone_1 }} @if($placeSuggestion->phone_2), {{ $placeSuggestion->phone_2 }} @endif</td>
                            </tr>
                            <tr>
                                <th>{{ __('admin.suggestions.whatsapp') }}</th>
                                <td>{{ $placeSuggestion->whatsapp ?? '—' }}</td>
                            </tr>
                            <tr>
                                <th>{{ __('admin.suggestions.website') }}</th>
                                <td>{{ $placeSuggestion->website ?? '—' }}</td>
                            </tr>
                            <tr>
                                <th>{{ __('admin.suggestions.status') }}</th>
                                <td>{{ __('common.status.'.$placeSuggestion->status->value) }}</td>
                            </tr>
                            <tr>
                                <th>{{ __('admin.suggestions.price') }}</th>
                                <td>{{ __('common.price.'.$placeSuggestion->price_level->value) }}</td>
                            </tr>
                            <tr>
                                <th>{{ __('admin.suggestions.submitted') }}</th>
                                <td>{{ \App\Support\LocalizedDate::date($placeSuggestion->created_at) }}</td>
                            </tr>
                            <tr>
                                <th>{{ __('admin.suggestions.suggestion_state') }}</th>
                                <td>{{ $placeSuggestion->suggestion_status->label() }}</td>
                            </tr>
                            <tr>
                                <th>{{ __('admin.suggestions.admin_note') }}</th>
                                <td>{{ $placeSuggestion->admin_note ?? __('admin.suggestions.no_note') }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div>
                    <div class="admin-action-panel">
                        <h4 class="admin-section-title">{{ __('admin.suggestions.admin_actions') }}</h4>
                        @if($placeSuggestion->suggestion_status?->value === 'pending')
                            <form action="{{ route('admin.place-suggestions.approve', $placeSuggestion) }}" method="POST" class="admin-form-block">
                                @csrf
                                <div class="form-group">
                                    <label for="admin_note" class="font-semibold">{{ __('admin.suggestions.admin_note') }}</label>
                                    <textarea name="admin_note" id="admin_note" rows="4" class="form-control">{{ old('admin_note') }}</textarea>
                                </div>
                                <button type="submit" class="btn btn-success admin-btn-block admin-mt-1">{{ __('admin.suggestions.approve') }}</button>
                            </form>
                            <form action="{{ route('admin.place-suggestions.reject', $placeSuggestion) }}" method="POST">
                                @csrf
                                <div class="form-group">
                                    <label for="reject_note" class="font-semibold">{{ __('admin.suggestions.rejection_note') }}</label>
                                    <textarea name="admin_note" id="reject_note" rows="4" class="form-control">{{ old('admin_note') }}</textarea>
                                </div>
                                <button type="submit" class="btn btn-danger admin-btn-block admin-mt-1">{{ __('admin.suggestions.reject') }}</button>
                            </form>
                        @else
                            <div class="alert alert-info">
                                {{ __('admin.suggestions.processed', ['status' => $placeSuggestion->suggestion_status->label()]) }}
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <div class="admin-section">
                <div>
                    <h4 class="admin-section-title">{{ __('admin.suggestions.description') }}</h4>
                    <p>{{ $placeSuggestion->description ?? __('admin.suggestions.no_description') }}</p>
                </div>
            </div>
        </div>
    </div>
@endsection
