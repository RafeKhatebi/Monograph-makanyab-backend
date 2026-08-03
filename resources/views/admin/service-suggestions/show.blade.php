@extends('layouts.admin')

@section('title', 'View Service Suggestion')
@section('page-title', 'Service Suggestion Details')

@section('content')
    <div class="card">
        <div class="card-header admin-card-header">
            <div>
                <h3 class="admin-card-title">{{ $serviceSuggestion->name }}</h3>
                <p class="admin-detail-value admin-table-muted">Submitted by
                    {{ $serviceSuggestion->submitted_by_name ?? ($serviceSuggestion->user->name ?? 'Guest') }}</p>
            </div>
            <a href="{{ route('admin.service-suggestions.index') }}" class="btn btn-secondary">Back to Suggestions</a>
        </div>

        <div class="card-body">
            <div class="admin-suggestion-grid">
                <div>
                    <table class="table table-borderless">
                        <tbody>
                            <tr>
                                <th>Name</th>
                                <td>{{ $serviceSuggestion->name }}</td>
                            </tr>
                            <tr>
                                <th>Category</th>
                                <td>{{ $serviceSuggestion->category->name ?? '—' }}</td>
                            </tr>
                            <tr>
                                <th>City</th>
                                <td>{{ $serviceSuggestion->city }}</td>
                            </tr>
                            <tr>
                                <th>Province</th>
                                <td>{{ $serviceSuggestion->province }}</td>
                            </tr>
                            <tr>
                                <th>Address</th>
                                <td>{{ $serviceSuggestion->address }}</td>
                            </tr>
                            <tr>
                                <th>Phone</th>
                                <td>{{ $serviceSuggestion->phone_1 }} @if ($serviceSuggestion->phone_2)
                                        , {{ $serviceSuggestion->phone_2 }}
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <th>WhatsApp</th>
                                <td>{{ $serviceSuggestion->whatsapp ?? '—' }}</td>
                            </tr>
                            <tr>
                                <th>Website</th>
                                <td>{{ $serviceSuggestion->website ?? '—' }}</td>
                            </tr>
                            <tr>
                                <th>Status</th>
                                <td>{{ ucfirst(str_replace('_', ' ', $serviceSuggestion->status->value)) }}</td>
                            </tr>
                            <tr>
                                <th>Price Level</th>
                                <td>{{ ucfirst($serviceSuggestion->price_level->value) }}</td>
                            </tr>
                            <tr>
                                <th>Suggestion State</th>
                                <td>{{ ucfirst($serviceSuggestion->suggestion_status->value) }}</td>
                            </tr>
                            <tr>
                                <th>Submitted</th>
                                <td>{{ $serviceSuggestion->created_at->diffForHumans() }}</td>
                            </tr>
                        </tbody>
                    </table>

                    <div class="admin-section">
                        <h4 class="admin-section-title">Description</h4>
                        <p>{{ $serviceSuggestion->description ?? 'No description provided.' }}</p>
                    </div>
                </div>

                <div>
                    <div class="admin-action-panel">
                        <h4 class="admin-section-title">Review Actions</h4>
                        @if ($serviceSuggestion->suggestion_status === 'pending')
                            <form action="{{ route('admin.service-suggestions.approve', $serviceSuggestion) }}"
                                method="POST" class="admin-form-block">
                                @csrf
                                <div class="form-group">
                                    <label class="form-label">Admin
                                        Note</label>
                                    <textarea name="admin_note" rows="4"
                                        class="form-control">{{ old('admin_note') }}</textarea>
                                </div>
                                <button type="submit" class="btn btn-success admin-btn-block">Approve and
                                    Publish</button>
                            </form>

                            <form action="{{ route('admin.service-suggestions.reject', $serviceSuggestion) }}"
                                method="POST">
                                @csrf
                                <div class="form-group">
                                    <label class="form-label">Rejection
                                        Note</label>
                                    <textarea name="admin_note" rows="4"
                                        class="form-control">{{ old('admin_note') }}</textarea>
                                </div>
                                <button type="submit" class="btn btn-danger admin-btn-block">Reject Suggestion</button>
                            </form>
                        @else
                            <div class="admin-note-box">
                                <strong>Status:</strong> {{ ucfirst($serviceSuggestion->suggestion_status->value) }}
                                <p class="admin-help-text">
                                    {{ $serviceSuggestion->admin_note ?? 'No note provided.' }}</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
