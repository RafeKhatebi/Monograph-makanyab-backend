@extends('layouts.admin')

@section('title', 'View Service Suggestion')
@section('page-title', 'Service Suggestion Details')

@section('content')
    <div class="panel">
        <div class="panel-heading" style="display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:12px;">
            <div>
                <h3 class="panel-title">{{ $serviceSuggestion->name }}</h3>
                <p class="text-muted" style="margin:0;">Submitted by {{ $serviceSuggestion->submitted_by_name ?? ($serviceSuggestion->user->name ?? 'Guest') }}</p>
            </div>
            <a href="{{ route('admin.service-suggestions.index') }}" class="btn btn-secondary">Back to Suggestions</a>
        </div>

        <div class="panel-body">
            <div class="row" style="gap:20px;">
                <div class="col-md-7">
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
                                <td>{{ $serviceSuggestion->phone_1 }} @if($serviceSuggestion->phone_2), {{ $serviceSuggestion->phone_2 }} @endif</td>
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
                                <td>{{ ucfirst(str_replace('_', ' ', $serviceSuggestion->status)) }}</td>
                            </tr>
                            <tr>
                                <th>Price Level</th>
                                <td>{{ ucfirst($serviceSuggestion->price_level) }}</td>
                            </tr>
                            <tr>
                                <th>Suggestion State</th>
                                <td>{{ ucfirst($serviceSuggestion->suggestion_status) }}</td>
                            </tr>
                            <tr>
                                <th>Submitted</th>
                                <td>{{ $serviceSuggestion->created_at->diffForHumans() }}</td>
                            </tr>
                        </tbody>
                    </table>

                    <div style="margin-top:24px;">
                        <h4 style="margin-bottom:12px;">Description</h4>
                        <p>{{ $serviceSuggestion->description ?? 'No description provided.' }}</p>
                    </div>
                </div>

                <div class="col-md-4" style="min-width:300px;">
                    <div style="background:#F8FAFC;border:1px solid #E5E7EB;border-radius:16px;padding:20px;">
                        <h4 style="margin-bottom:16px;">Review Actions</h4>
                        @if($serviceSuggestion->suggestion_status === 'pending')
                            <form action="{{ route('admin.service-suggestions.approve', $serviceSuggestion) }}" method="POST" style="margin-bottom:16px;">
                                @csrf
                                <div style="margin-bottom:12px;">
                                    <label style="font-weight:600;color:#374151;margin-bottom:6px;display:block;">Admin Note</label>
                                    <textarea name="admin_note" rows="4" style="width:100%;padding:12px;border:1px solid #D1D5DB;border-radius:10px;outline:none;">{{ old('admin_note') }}</textarea>
                                </div>
                                <button type="submit" class="btn btn-success" style="width:100%;">Approve and Publish</button>
                            </form>

                            <form action="{{ route('admin.service-suggestions.reject', $serviceSuggestion) }}" method="POST">
                                @csrf
                                <div style="margin-bottom:12px;">
                                    <label style="font-weight:600;color:#374151;margin-bottom:6px;display:block;">Rejection Note</label>
                                    <textarea name="admin_note" rows="4" style="width:100%;padding:12px;border:1px solid #D1D5DB;border-radius:10px;outline:none;">{{ old('admin_note') }}</textarea>
                                </div>
                                <button type="submit" class="btn btn-danger" style="width:100%;">Reject Suggestion</button>
                            </form>
                        @else
                            <div style="padding:16px;background:#fff;border:1px solid #E5E7EB;border-radius:14px;">
                                <strong>Status:</strong> {{ ucfirst($serviceSuggestion->suggestion_status) }}
                                <p style="margin-top:12px;color:#6B7280;">{{ $serviceSuggestion->admin_note ?? 'No note provided.' }}</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
