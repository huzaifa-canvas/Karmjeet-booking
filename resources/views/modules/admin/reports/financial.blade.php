@extends('layouts.master')
@section('title', 'Financial Reports | ' . config('app.name'))

@section('content')
<div class="app-content content">
    <div class="content-overlay"></div>
    <div class="header-navbar-shadow"></div>
    <div class="content-wrapper container-xxl p-0">
        <div class="content-header row">
            <div class="content-header-left col-md-9 col-12 mb-2">
                <h2 class="content-header-title float-start mb-0">Financial Reports</h2>
            </div>
            <div class="content-header-right text-md-end col-md-3 col-12 d-md-block d-none">
                <div class="mb-1 breadcrumb-right">
                    <a href="{{ route('admin.reports.financial.export') }}" class="btn btn-primary">
                        <i data-feather="download" class="me-25"></i> Download CSV
                    </a>
                </div>
            </div>
        </div>

        <div class="content-body">
            <!-- Stats Row -->
            <div class="row">
                <div class="col-lg-4 col-sm-6 col-12">
                    <div class="card">
                        <div class="card-header">
                            <div>
                                <h2 class="fw-bolder mb-0">${{ number_format($totalRevenue, 2) }}</h2>
                                <p class="card-text">Total Revenue (All Sources)</p>
                            </div>
                            <div class="avatar bg-light-primary p-50 m-0">
                                <div class="avatar-content"><i data-feather="dollar-sign" class="font-medium-5"></i></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-sm-6 col-12">
                    <div class="card">
                        <div class="card-header">
                            <div>
                                <h2 class="fw-bolder mb-0">${{ number_format($totalGst, 2) }}</h2>
                                <p class="card-text">Total GST Collected ({{ $gstRate }}%)</p>
                            </div>
                            <div class="avatar bg-light-info p-50 m-0">
                                <div class="avatar-content"><i data-feather="trending-up" class="font-medium-5"></i></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-sm-6 col-12">
                    <div class="card">
                        <div class="card-header">
                            <div>
                                <h2 class="fw-bolder mb-0">${{ number_format($totalPst, 2) }}</h2>
                                <p class="card-text">Total PST Collected ({{ $pstRate }}%)</p>
                            </div>
                            <div class="avatar bg-light-warning p-50 m-0">
                                <div class="avatar-content"><i data-feather="trending-up" class="font-medium-5"></i></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Chart Row -->
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">Monthly Revenue Trend</h4>
                        </div>
                        <div class="card-body">
                            <canvas id="revenueChart" height="100"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Invoices Table Row -->
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">Recent Transactions & Invoices</h4>
                        </div>
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Date</th>
                                        <th>Type</th>
                                        <th>Customer</th>
                                        <th>Item / Order</th>
                                        <th>Amount</th>
                                        <th>Stripe ID</th>
                                        <th>Invoice</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($invoices as $invoice)
                                    <tr>
                                        <td>{{ $invoice['date']->format('M d, Y') }}</td>
                                        <td>
                                            <span class="badge rounded-pill bg-light-{{ $invoice['type'] == 'Subscription' ? 'primary' : 'success' }}">
                                                {{ $invoice['type'] }}
                                            </span>
                                        </td>
                                        <td>{{ $invoice['user'] }}</td>
                                        <td>{{ $invoice['item'] }}</td>
                                        <td class="fw-bold">${{ number_format($invoice['amount'], 2) }}</td>
                                        <td><small class="text-muted">{{ $invoice['stripe_id'] }}</small></td>
                                        <td>
                                            @if($invoice['invoice_url'])
                                            <a href="{{ $invoice['invoice_url'] }}" target="_blank" class="btn btn-sm btn-outline-primary">
                                                View
                                            </a>
                                            @endif
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="7" class="text-center">No transactions found.</td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                        <div class="card-footer d-flex justify-content-center">
                            {{ $invoices->links('pagination::bootstrap-5') }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const ctx = document.getElementById('revenueChart').getContext('2d');
    const labels = {!! json_encode($chartLabels) !!};
    const dataSub = {!! json_encode($chartSubscriptionRevenue) !!};
    const dataProd = {!! json_encode($chartProductRevenue) !!};

    new Chart(ctx, {
        type: 'line',
        data: {
            labels: labels,
            datasets: [
                {
                    label: 'Subscription Revenue ($)',
                    data: dataSub,
                    borderColor: '#7367f0',
                    backgroundColor: 'rgba(115, 103, 240, 0.2)',
                    borderWidth: 2,
                    fill: true,
                    tension: 0.4
                },
                {
                    label: 'Product Sales ($)',
                    data: dataProd,
                    borderColor: '#28c76f',
                    backgroundColor: 'rgba(40, 199, 111, 0.2)',
                    borderWidth: 2,
                    fill: true,
                    tension: 0.4
                }
            ]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true,
                    stacked: true
                }
            }
        }
    });
});
</script>
@endsection
