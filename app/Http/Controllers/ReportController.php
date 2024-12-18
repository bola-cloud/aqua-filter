<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Sales;
use App\Models\Invoice;
use Carbon\Carbon;

class ReportController extends Controller
{
    // Daily Report
    public function dailyReport()
    {
        $today = Carbon::now()->startOfDay();

        // Get invoices created today
        $invoices = Invoice::whereDate('created_at', $today)
            ->with('sales.product') // Load related sales and products
            ->get();

        // Calculate total revenue, paid amount, and total profit
        $totalRevenue = $invoices->sum('paid_amount'); // Use paid amount
        $totalQuantity = $invoices->flatMap->sales->sum('quantity'); // Total quantity from sales
        $totalProfit = $invoices->sum(function ($invoice) {
            return $invoice->sales->sum(function ($sale) {
                // Profit per sale (selling price - cost price) * quantity
                return ($sale->product->selling_price - $sale->product->cost_price) * $sale->quantity;
            });
        });

        return view('admin.reports.daily', compact('invoices', 'totalQuantity', 'totalRevenue', 'totalProfit'));
    }

    // Monthly Report
    public function monthlyReport()
    {
        $currentMonth = Carbon::now()->month;
        $currentYear = Carbon::now()->year;

        // Get invoices created this month
        $invoices = Invoice::whereMonth('created_at', $currentMonth)
            ->whereYear('created_at', $currentYear)
            ->with('sales.product') // Load related sales and products
            ->get();

        // Calculate total revenue, paid amount, and total profit
        $totalRevenue = $invoices->sum('paid_amount'); // Use paid amount
        $totalQuantity = $invoices->flatMap->sales->sum('quantity'); // Total quantity from sales
        $totalProfit = $invoices->sum(function ($invoice) {
            return $invoice->sales->sum(function ($sale) {
                // Profit per sale (selling price - cost price) * quantity
                return ($sale->product->selling_price - $sale->product->cost_price) * $sale->quantity;
            });
        });

        return view('admin.reports.monthly', compact('invoices', 'totalQuantity', 'totalRevenue', 'totalProfit'));
    }

    // Date Range Report
    public function dateRangeReport(Request $request)
    {
        $request->validate([
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
        ]);

        $startDate = Carbon::parse($request->start_date)->startOfDay();
        $endDate = Carbon::parse($request->end_date)->endOfDay(); 

        // Get invoices within the date range
        $invoices = Invoice::whereBetween('created_at', [$startDate, $endDate])
            ->with('sales.product') // Load related sales and products
            ->get();

        // Calculate total revenue, paid amount, and total profit
        $totalRevenue = $invoices->sum('paid_amount'); // Use paid amount
        $totalQuantity = $invoices->flatMap->sales->sum('quantity'); // Total quantity from sales
        $totalProfit = $invoices->sum(function ($invoice) {
            return $invoice->sales->sum(function ($sale) {
                // Profit per sale (selling price - cost price) * quantity
                return ($sale->product->selling_price - $sale->product->cost_price) * $sale->quantity;
            });
        });

        return view('admin.reports.date_report', compact('invoices', 'totalQuantity', 'totalRevenue', 'totalProfit', 'startDate', 'endDate'));
    }
    public function soldProductsReport(Request $request)
    {
        // Use today's date if no date is provided by the user
        $soldDate = Carbon::parse($request->input('sold_date', Carbon::now()->toDateString()))->startOfDay();
    
        // Retrieve all sales that occurred on the specified date
        $sales = Sales::whereDate('created_at', $soldDate)
            ->with('product') // Load related product information
            ->get();
    
        // Calculate the total quantity of sold products and total revenue
        $totalQuantity = $sales->sum('quantity'); // Sum the quantities of all sold products
        $totalRevenue = $sales->sum('total_price'); // Sum the total price of all sales
    
        return view('admin.reports.sold_products', compact('sales', 'totalQuantity', 'totalRevenue', 'soldDate'));
    }
}