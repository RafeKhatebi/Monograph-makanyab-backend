@extends('layouts.admin')

@section('title', 'View Suggestion')
@section('page-title', 'Suggestion Details')

@section('content')
    <div class="card">
        <div class="card-header admin-card-header">
            <div>
                <h3 class="admin-card-title">{{ $placeSuggestion->name }}</h3>
                <p class="admin-detail-value admin-table-muted">Submitted by {{ $placeSuggestion->submitted_by_name ?? ($placeSuggestion->user->name ?? 'Guest') }}</p>
            </div>
            <a href="{{ route('admin.place-suggestions.index') }}" class="btn btn-secondary">Back to Suggestions</a>
        </div>
        <div class="card-body">
            <div class="admin-suggestion-grid">
                <div>
                    <table class="table table-borderless">
                        <tbody>
                            <tr>
                                <th>Name</th>
                                <td>{{ $placeSuggestion->name }}</td>
                            </tr>
                            <tr>
                                <th>Category</th>
                                <td>{{ $placeSuggestion->category->name ?? '—' }}</td>
                            </tr>
                            <tr>
                                <th>City</th>
                                <td>{{ $placeSuggestion->city }}</td>
                            </tr>
                            <tr>
                                <th>Province</th>
                                <td>{{ $placeSuggestion->province }}</td>
                            </tr>
                            <tr>
                                <th>Address</th>
                                <td>{{ $placeSuggestion->address }}</td>
                            </tr>
                            <tr>
                                <th>Phone</th>
                                <td>{{ $placeSuggestion->phone_1 }} @if($placeSuggestion->phone_2), {{ $placeSuggestion->phone_2 }} @endif</td>
                            </tr>
                            <tr>
                                <th>WhatsApp</th>
                                <td>{{ $placeSuggestion->whatsapp ?? '—' }}</td>
                            </tr>
                            <tr>
                                <th>Website</th>
                                <td>{{ $placeSuggestion->website ?? '—' }}</td>
                            </tr>
                            <tr>
                                <th>Status</th>
                                <td>{{ ucfirst(str_replace('_', ' ', $placeSuggestion->status->value)) }}</td>
                            </tr>
                            <tr>
                                <th>Price</th>
                                <td>{{ ucfirst($placeSuggestion->price_level->value) }}</td>
                            </tr>
                            <tr>
                                <th>Submitted</th>
                                <td>{{ $placeSuggestion->created_at->diffForHumans() }}</td>
                            </tr>
                            <tr>
                                <th>Suggestion State</th>
                                <td>{{ ucfirst($placeSuggestion->suggestion_status->value) }}</td>
                            </tr>
                            <tr>
                                <th>Admin Note</th>
                                <td>{{ $placeSuggestion->admin_note ?? 'No note yet.' }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div>
                    <div class="admin-action-panel">
                        <h4 class="admin-section-title">Admin Actions</h4>
                        @if($placeSuggestion->suggestion_status?->value === 'pending')
                            <form action="{{ route('admin.place-suggestions.approve', $placeSuggestion) }}" method="POST" class="admin-form-block">
                                @csrf
                                <div class="form-group">
                                    <label for="admin_note" class="font-semibold">Admin note</label>
                                    <textarea name="admin_note" id="admin_note" rows="4" class="form-control">{{ old('admin_note') }}</textarea>
                                </div>
                                <button type="submit" class="btn btn-success admin-btn-block admin-mt-1">Approve</button>
                            </form>
                            <form action="{{ route('admin.place-suggestions.reject', $placeSuggestion) }}" method="POST">
                                @csrf
                                <div class="form-group">
                                    <label for="reject_note" class="font-semibold">Rejection note</label>
                                    <textarea name="admin_note" id="reject_note" rows="4" class="form-control">{{ old('admin_note') }}</textarea>
                                </div>
                                <button type="submit" class="btn btn-danger admin-btn-block admin-mt-1">Reject</button>
                            </form>
                        @else
                            <div class="alert alert-info">
                                This suggestion has been {{ $placeSuggestion->suggestion_status->value }}.
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <div class="admin-section">
                <div>
                    <h4 class="admin-section-title">Description</h4>
                    <p>{{ $placeSuggestion->description ?? 'No description provided.' }}</p>
                </div>
            </div>
        </div>
    </div>
@endsection
