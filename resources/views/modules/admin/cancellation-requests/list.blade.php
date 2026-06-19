@extends('layouts.master')
@section('title', 'Cancellation Requests | ' . config('app.name'))

@section('content')
<div class="app-content content">
    <div class="content-overlay"></div>
    <div class="header-navbar-shadow"></div>
    <div class="content-wrapper container-xxl p-0">
        <div class="content-header row">
            <div class="content-header-left col-md-9 col-12 mb-2">
                <h2 class="content-header-title float-start mb-0">Cancellation Requests</h2>
            </div>
        </div>
        
        <div class="content-body">
            @if(session('status') == 'success')
                <div class="alert alert-success p-1">{{ session('message') }}</div>
            @endif
            @if(session('status') == 'failed')
                <div class="alert alert-danger p-1">{{ session('message') }}</div>
            @endif

            <div class="card">
                <div class="card-header border-bottom">
                    <form action="{{ route('admin.cancellation-requests.index') }}" method="GET" class="d-flex align-items-center">
                        <select name="status" class="form-select me-1" onchange="this.form.submit()">
                            <option value="">All Statuses</option>
                            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Approved</option>
                            <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
                        </select>
                    </form>
                </div>
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>User</th>
                                <th>Class</th>
                                <th>Requested At</th>
                                <th>Reason</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($requests as $req)
                            <tr>
                                <td>{{ $req->user->name }}<br><small class="text-muted">{{ $req->user->email }}</small></td>
                                <td>{{ $req->subscription->martialArtsClass->name ?? 'N/A' }}</td>
                                <td>{{ $req->requested_at->format('M d, Y') }}</td>
                                <td>{{ Str::limit($req->notes, 30) }}</td>
                                <td>
                                    <span class="badge rounded-pill badge-light-{{ $req->status == 'completed' ? 'success' : ($req->status == 'rejected' ? 'danger' : 'warning') }}">
                                        {{ $req->status == 'completed' ? 'Approved' : ucfirst(str_replace('_', ' ', $req->status)) }}
                                    </span>
                                </td>
                                <td>
                                    <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#reviewModal-{{ $req->id }}">
                                        Review
                                    </button>
                                </td>
                            </tr>
                            
                            <!-- Review Modal -->
                            <div class="modal fade" id="reviewModal-{{ $req->id }}" tabindex="-1" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Review Cancellation Request</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <form action="{{ route('admin.cancellation-requests.update', $req->id) }}" method="POST">
                                            @csrf
                                            <div class="modal-body">
                                                <div class="mb-1">
                                                    <strong>User Notes:</strong>
                                                    <p class="text-muted border p-1 rounded bg-light">{{ $req->notes ?: 'No reason provided.' }}</p>
                                                </div>
                                                <div class="mb-1">
                                                    <label class="form-label">Status</label>
                                                    <select name="status" class="form-select" required>
                                                        <option value="pending" {{ $req->status == 'pending' ? 'selected' : '' }}>Pending</option>
                                                        <option value="completed" {{ $req->status == 'completed' ? 'selected' : '' }}>Approve (Cancels Subscription)</option>
                                                        <option value="rejected" {{ $req->status == 'rejected' ? 'selected' : '' }}>Reject</option>
                                                    </select>
                                                </div>
                                                <div class="mb-1">
                                                    <label class="form-label">Effective Cancellation Date</label>
                                                    <input type="date" name="effective_cancellation_date" class="form-control" value="{{ $req->effective_cancellation_date ? $req->effective_cancellation_date->format('Y-m-d') : \App\Models\CancellationRequest::calculateEffectiveDate($req->requested_at)->format('Y-m-d') }}">
                                                    <small class="text-muted">Defaults to 60 days from request date.</small>
                                                </div>
                                                <div class="mb-1">
                                                    <label class="form-label">Admin Notes</label>
                                                    <textarea name="admin_notes" class="form-control" rows="2" placeholder="Internal notes...">{{ $req->admin_notes }}</textarea>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Close</button>
                                                <button type="submit" class="btn btn-primary">Save Changes</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            @empty
                            <tr>
                                <td colspan="6" class="text-center p-3">No cancellation requests found.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="card-footer d-flex justify-content-center">
                    {{ $requests->links('pagination::bootstrap-5') }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
