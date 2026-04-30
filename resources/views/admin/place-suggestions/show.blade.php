@extends('layouts.admin')

@section('title', 'View Suggestion')
@section('page-title', 'Suggestion Details')

@section('content')
    <div class="panel panel-default">
        <div class="panel-heading" style="display:flex;justify-content:space-between;align-items:center;">
            <div>
                <h3 class="panel-title">{{ $placeSuggestion->name }}</h3>
                <p class="text-muted" style="margin:0;">Submitted by {{ $placeSuggestion->submitted_by_name ?? ($placeSuggestion->user->name ?? 'Guest') }}</p>
            </div>
            <a href="{{ route('admin.place-suggestions.index') }}" class="btn btn-secondary">Back to Suggestions</a>
        </div>
        <div class="panel-body">
            <div class="row">
                <div class="col-md-8">
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
                                <td>{{ ucfirst(str_replace('_', ' ', $placeSuggestion->status)) }}</td>
                            </tr>
                            <tr>
                                <th>Price</th>
                                <td>{{ ucfirst($placeSuggestion->price_level) }}</td>
                            </tr>
                            <tr>
                                <th>Submitted</th>
                                <td>{{ $placeSuggestion->created_at->diffForHumans() }}</td>
                            </tr>
                            <tr>
                                <th>Suggestion State</th>
                                <td>{{ ucfirst($placeSuggestion->suggestion_status) }}</td>
                            </tr>
                            <tr>
                                <th>Admin Note</th>
                                <td>{{ $placeSuggestion->admin_note ?? 'No note yet.' }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="col-md-4">
                    <div class="card" style="padding:20px;border:1px solid #E5E7EB;border-radius:14px;">
                        <h4 class="mb-3">Admin Actions</h4>
                        @if($placeSuggestion->suggestion_status === 'pending')
                            <form action="{{ route('admin.place-suggestions.approve', $placeSuggestion) }}" method="POST" style="margin-bottom:12px;">
                                @csrf
                                <div class="form-group">
                                    <label for="admin_note" class="font-semibold">Admin note</label>
                                    <textarea name="admin_note" id="admin_note" rows="4" class="form-control" style="width:100%;padding:10px;border:1px solid #D1D5DB;border-radius:10px;">{{ old('admin_note') }}</textarea>
                                </div>
                                <button type="submit" class="btn btn-success" style="width:100%;margin-top:12px;">Approve</button>
                            </form>
                            <form action="{{ route('admin.place-suggestions.reject', $placeSuggestion) }}" method="POST">
                                @csrf
                                <div class="form-group">
                                    <label for="reject_note" class="font-semibold">Rejection note</label>
                                    <textarea name="admin_note" id="reject_note" rows="4" class="form-control" style="width:100%;padding:10px;border:1px solid #D1D5DB;border-radius:10px;">{{ old('admin_note') }}</textarea>
                                </div>
                                <button type="submit" class="btn btn-danger" style="width:100%;margin-top:12px;">Reject</button>
                            </form>
                        @else
                            <div class="alert alert-info" style="padding:14px;border-radius:12px;background:#EFF6FF;color:#1D4ED8;">
                                This suggestion has been {{ $placeSuggestion->suggestion_status }}.
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <div class="row" style="margin-top:30px;">
                <div class="col-md-12">
                    <h4>Description</h4>
                    <p>{{ $placeSuggestion->description ?? 'No description provided.' }}</p>
                </div>
            </div>
        </div>
    </div>
@endsection
