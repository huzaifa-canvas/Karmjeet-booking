@extends('layouts.master')
@section('title', 'Member Statistics | ' . config('app.name'))

@section('content')
<div class="app-content content">
    <div class="content-overlay"></div>
    <div class="header-navbar-shadow"></div>
    <div class="content-wrapper container-xxl p-0">
        <div class="content-header row">
            <div class="content-header-left col-md-9 col-12 mb-2">
                <h2 class="content-header-title float-start mb-0">Member Statistics</h2>
            </div>
        </div>

        <div class="content-body">
            <!-- Stats Row -->
            <div class="row">
                <div class="col-lg-3 col-sm-6 col-12">
                    <div class="card">
                        <div class="card-header">
                            <div>
                                <h2 class="fw-bolder mb-0">{{ $totalUsers }}</h2>
                                <p class="card-text">Total Registered Users</p>
                            </div>
                            <div class="avatar bg-light-primary p-50 m-0">
                                <div class="avatar-content"><i data-feather="users" class="font-medium-5"></i></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-sm-6 col-12">
                    <div class="card">
                        <div class="card-header">
                            <div>
                                <h2 class="fw-bolder mb-0">{{ $activeSubscriptions }}</h2>
                                <p class="card-text">Active Subscriptions</p>
                            </div>
                            <div class="avatar bg-light-success p-50 m-0">
                                <div class="avatar-content"><i data-feather="check-circle" class="font-medium-5"></i></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-sm-6 col-12">
                    <div class="card">
                        <div class="card-header">
                            <div>
                                <h2 class="fw-bolder mb-0">{{ $canceledSubscriptions }}</h2>
                                <p class="card-text">Canceled Subscriptions</p>
                            </div>
                            <div class="avatar bg-light-danger p-50 m-0">
                                <div class="avatar-content"><i data-feather="x-circle" class="font-medium-5"></i></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-sm-6 col-12">
                    <div class="card">
                        <div class="card-header">
                            <div>
                                <h2 class="fw-bolder mb-0">{{ number_format($churnRate, 1) }}%</h2>
                                <p class="card-text">Overall Churn Rate</p>
                            </div>
                            <div class="avatar bg-light-warning p-50 m-0">
                                <div class="avatar-content"><i data-feather="activity" class="font-medium-5"></i></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Charts Row -->
            <div class="row">
                <!-- Signups Chart -->
                <div class="col-md-8 col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">User Signups by Month</h4>
                        </div>
                        <div class="card-body">
                            <canvas id="signupsChart" height="150"></canvas>
                        </div>
                    </div>
                </div>

                <!-- Program Distribution Chart -->
                <div class="col-md-4 col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">Program Distribution (Active)</h4>
                        </div>
                        <div class="card-body">
                            <canvas id="programChart" height="300"></canvas>
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
    // Signups Bar Chart
    const signupCtx = document.getElementById('signupsChart').getContext('2d');
    const signupLabels = {!! json_encode($signupLabels) !!};
    const signupData = {!! json_encode($signupData) !!};

    new Chart(signupCtx, {
        type: 'bar',
        data: {
            labels: signupLabels,
            datasets: [{
                label: 'New Users',
                data: signupData,
                backgroundColor: '#7367f0',
                borderRadius: 4
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: { beginAtZero: true }
            }
        }
    });

    // Program Distribution Doughnut Chart
    const programCtx = document.getElementById('programChart').getContext('2d');
    const programLabels = {!! json_encode($programLabels) !!};
    const programData = {!! json_encode($programData) !!};
    
    // Generate some colors
    const colors = ['#7367f0', '#28c76f', '#ea5455', '#ff9f43', '#00cfe8', '#82868b'];

    new Chart(programCtx, {
        type: 'doughnut',
        data: {
            labels: programLabels,
            datasets: [{
                data: programData,
                backgroundColor: colors.slice(0, programLabels.length),
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom'
                }
            }
        }
    });
});
</script>
@endsection
