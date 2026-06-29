<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SubscriptionPayment;
use App\Models\Subscription;
use App\Models\User;
use App\Models\SiteSetting;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    /**
     * Build financial data with optional date filtering.
     */
    private function getFinancialData(Request $request = null)
    {
        $settings = SiteSetting::first();
        $gstRate = $settings->gst_percentage ?? 0;
        $pstRate = $settings->pst_percentage ?? 0;
        $totalTaxRate = ($gstRate + $pstRate) / 100;

        // Determine date filters
        $dateFrom = null;
        $dateTo = null;

        if ($request) {
            if ($request->date_range) {
                // flatpickr range: "2026-01-01 to 2026-06-30"
                $parts = explode(' to ', $request->date_range);
                $dateFrom = Carbon::parse(trim($parts[0]))->startOfDay();
                $dateTo = isset($parts[1]) ? Carbon::parse(trim($parts[1]))->endOfDay() : Carbon::parse(trim($parts[0]))->endOfDay();
            } elseif ($request->year) {
                $year = (int) $request->year;
                if ($request->month) {
                    $month = (int) $request->month;
                    $dateFrom = Carbon::createFromDate($year, $month, 1)->startOfDay();
                    $dateTo = Carbon::createFromDate($year, $month, 1)->endOfMonth()->endOfDay();
                } else {
                    $dateFrom = Carbon::createFromDate($year, 1, 1)->startOfDay();
                    $dateTo = Carbon::createFromDate($year, 12, 31)->endOfDay();
                }
            }
        }

        // Get Subscriptions
        $paymentsQuery = SubscriptionPayment::with(['subscription.martialArtsClass', 'subscription.user'])
            ->where('status', 'succeeded');
        
        if ($dateFrom && $dateTo) {
            $paymentsQuery->whereBetween('created_at', [$dateFrom, $dateTo]);
        }

        $payments = $paymentsQuery->orderBy('created_at', 'desc')->get();

        // Get Orders
        $ordersQuery = \App\Models\Order::with(['user'])
            ->where(function($q) {
                $q->where('status', 'completed')
                  ->orWhere('payment_status', 'paid');
            });
        
        if ($dateFrom && $dateTo) {
            $ordersQuery->whereBetween('created_at', [$dateFrom, $dateTo]);
        }

        $orders = $ordersQuery->orderBy('created_at', 'desc')->get();

        $transactions = collect();
        $monthlyData = [];
        $totalRevenue = 0;
        $totalGst = 0;
        $totalPst = 0;

        // Process Subscriptions
        foreach ($payments as $payment) {
            $month = $payment->created_at->format('Y-m');
            if (!isset($monthlyData[$month])) {
                $monthlyData[$month] = ['subscription_revenue' => 0, 'product_revenue' => 0, 'gst' => 0, 'pst' => 0];
            }

            $totalPaid = $payment->amount;
            $subtotal = $totalPaid / (1 + $totalTaxRate);
            $gstAmount = $subtotal * ($gstRate / 100);
            $pstAmount = $subtotal * ($pstRate / 100);

            $monthlyData[$month]['subscription_revenue'] += $totalPaid;
            $monthlyData[$month]['gst'] += $gstAmount;
            $monthlyData[$month]['pst'] += $pstAmount;

            $totalRevenue += $totalPaid;
            $totalGst += $gstAmount;
            $totalPst += $pstAmount;

            $transactions->push([
                'type' => 'Subscription',
                'date' => $payment->created_at,
                'user' => $payment->subscription->user->name ?? 'N/A',
                'item' => $payment->subscription->martialArtsClass->name ?? 'N/A',
                'amount' => $totalPaid,
                'gst' => $gstAmount,
                'pst' => $pstAmount,
                'stripe_id' => $payment->stripe_payment_id,
                'invoice_url' => $payment->stripe_invoice_url
            ]);
        }

        // Process Orders
        foreach ($orders as $order) {
            $month = $order->created_at->format('Y-m');
            if (!isset($monthlyData[$month])) {
                $monthlyData[$month] = ['subscription_revenue' => 0, 'product_revenue' => 0, 'gst' => 0, 'pst' => 0];
            }

            $totalPaid = $order->total_amount;
            $gstAmount = $order->gst_amount;
            $pstAmount = $order->pst_amount;

            $monthlyData[$month]['product_revenue'] += $totalPaid;
            $monthlyData[$month]['gst'] += $gstAmount;
            $monthlyData[$month]['pst'] += $pstAmount;

            $totalRevenue += $totalPaid;
            $totalGst += $gstAmount;
            $totalPst += $pstAmount;

            $transactions->push([
                'type' => 'Product Order',
                'date' => $order->created_at,
                'user' => $order->customer_name,
                'item' => 'Order ' . $order->order_number,
                'amount' => $totalPaid,
                'gst' => $gstAmount,
                'pst' => $pstAmount,
                'stripe_id' => $order->stripe_payment_intent ?? 'N/A',
                'invoice_url' => route('admin.orders.show', $order->id)
            ]);
        }

        // Sort transactions by date descending
        $transactions = $transactions->sortByDesc('date')->values();
        ksort($monthlyData);

        return [
            'monthlyData' => $monthlyData,
            'totalRevenue' => $totalRevenue,
            'totalGst' => $totalGst,
            'totalPst' => $totalPst,
            'transactions' => $transactions,
            'gstRate' => $gstRate,
            'pstRate' => $pstRate
        ];
    }

    public function financial(Request $request)
    {
        $data = $this->getFinancialData($request);
        
        $chartLabels = array_keys($data['monthlyData']);
        $chartSubscriptionRevenue = array_column($data['monthlyData'], 'subscription_revenue');
        $chartProductRevenue = array_column($data['monthlyData'], 'product_revenue');

        // Manual Pagination for collection
        $currentPage = \Illuminate\Pagination\LengthAwarePaginator::resolveCurrentPage();
        $perPage = 15;
        $currentItems = $data['transactions']->slice(($currentPage - 1) * $perPage, $perPage)->all();
        $invoices = new \Illuminate\Pagination\LengthAwarePaginator($currentItems, count($data['transactions']), $perPage, $currentPage, [
            'path' => $request->url(),
            'query' => $request->query(),
        ]);

        // Available years for dropdown (from 2024 to current year)
        $availableYears = range(2024, now()->year);

        return view('modules.admin.reports.financial', array_merge($data, [
            'chartLabels' => $chartLabels,
            'chartSubscriptionRevenue' => $chartSubscriptionRevenue,
            'chartProductRevenue' => $chartProductRevenue,
            'invoices' => $invoices,
            'availableYears' => $availableYears,
            'selectedYear' => $request->year,
            'selectedMonth' => $request->month,
            'selectedDateRange' => $request->date_range,
        ]));
    }

    public function downloadFinancialCsv(Request $request)
    {
        $data = $this->getFinancialData($request);
        
        // Build filename with filter info
        $filterLabel = '';
        if ($request->date_range) {
            $filterLabel = '_' . str_replace([' to ', ' '], ['_to_', ''], $request->date_range);
        } elseif ($request->year) {
            $filterLabel = '_' . $request->year;
            if ($request->month) {
                $filterLabel .= '-' . str_pad($request->month, 2, '0', STR_PAD_LEFT);
            }
        }
        
        $fileName = "Financial_Report" . $filterLabel . "_" . date('Y-m-d') . ".csv";

        $headers = array(
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=$fileName",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        );

        $columns = ['Date', 'Type', 'Customer', 'Item/Order', 'Total Amount', 'GST Amount', 'PST Amount', 'Stripe/Payment ID'];

        $callback = function() use($data, $columns, $request) {
            $file = fopen('php://output', 'w');
            
            // Add Summary Data
            fputcsv($file, ['Financial Summary Report', 'Date Generated: ' . date('Y-m-d')]);
            
            // Show active filter
            if ($request->date_range) {
                fputcsv($file, ['Filter: Date Range', $request->date_range]);
            } elseif ($request->year) {
                $filterInfo = 'Year: ' . $request->year;
                if ($request->month) {
                    $filterInfo .= ', Month: ' . Carbon::createFromDate($request->year, $request->month, 1)->format('F');
                }
                fputcsv($file, ['Filter', $filterInfo]);
            } else {
                fputcsv($file, ['Filter', 'All Time']);
            }
            
            fputcsv($file, []);
            fputcsv($file, ['Total Revenue', '$' . number_format($data['totalRevenue'], 2)]);
            fputcsv($file, ['Total GST Collected', '$' . number_format($data['totalGst'], 2)]);
            fputcsv($file, ['Total PST Collected', '$' . number_format($data['totalPst'], 2)]);
            fputcsv($file, []);
            
            // Add Transaction History
            fputcsv($file, ['Transaction History']);
            fputcsv($file, $columns);

            foreach ($data['transactions'] as $row) {
                fputcsv($file, [
                    $row['date']->format('Y-m-d H:i'),
                    $row['type'],
                    $row['user'],
                    $row['item'],
                    '$' . number_format($row['amount'], 2),
                    '$' . number_format($row['gst'], 2),
                    '$' . number_format($row['pst'], 2),
                    $row['stripe_id']
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function members(Request $request)
    {
        $totalUsers = User::where('user_role', 'user')->count();
        $activeSubscriptions = Subscription::where('status', 'active')->count();
        $canceledSubscriptions = Subscription::where('status', 'canceled')->count();

        // Calculate churn rate: (Canceled / Total ever created) * 100
        $totalSubscriptions = $activeSubscriptions + $canceledSubscriptions;
        $churnRate = $totalSubscriptions > 0 ? ($canceledSubscriptions / $totalSubscriptions) * 100 : 0;

        // Signups per month (based on users table)
        $signups = User::where('user_role', 'user')
            ->select(DB::raw('DATE_FORMAT(created_at, "%Y-%m") as month'), DB::raw('count(*) as count'))
            ->groupBy('month')
            ->orderBy('month', 'asc')
            ->get();

        $signupLabels = $signups->pluck('month')->toArray();
        $signupData = $signups->pluck('count')->toArray();

        // Program Distribution
        $programs = Subscription::with('martialArtsClass')
            ->where('status', 'active')
            ->get()
            ->groupBy('martial_arts_class_id')
            ->map(function ($group) {
                return [
                    'name' => $group->first()->martialArtsClass->name ?? 'Unknown',
                    'count' => $group->count()
                ];
            })
            ->values();

        $programLabels = $programs->pluck('name')->toArray();
        $programData = $programs->pluck('count')->toArray();

        return view('modules.admin.reports.members', compact(
            'totalUsers', 'activeSubscriptions', 'canceledSubscriptions', 'churnRate',
            'signupLabels', 'signupData', 'programLabels', 'programData'
        ));
    }
}
