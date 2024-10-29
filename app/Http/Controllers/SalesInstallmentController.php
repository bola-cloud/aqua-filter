<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Invoice;
use App\Models\SalesInstallment;

class SalesInstallmentController extends Controller
{
    // Show all installments for a given invoice
    public function indexInstallments($invoiceId)
    {
        $invoice = Invoice::findOrFail($invoiceId);
        $installments = $invoice->installments()->get();

        return view('admin.invoices.installments', compact('invoice', 'installments'));
    }

    // Store a new installment for a given invoice
    public function storeInstallment(Request $request, $invoiceId)
    {
        $request->validate([
            'amount_paid' => 'required|numeric|min:0',
            'date_paid' => 'required|date',
            'agent' => 'required|string|max:255', // New validation for agent field
        ]);
    
        $invoice = Invoice::findOrFail($invoiceId);
    
        // Create the first installment (deposit)
        SalesInstallment::create([
            'invoice_id' => $invoiceId,
            'amount_paid' => $request->input('amount_paid'),
            'date_paid' => $request->input('date_paid'),
            'agent' => $request->input('agent'), // Save agent
        ]);
    
        // Recalculate the total paid amount for the invoice
        $totalPaid = SalesInstallment::where('invoice_id', $invoice->id)->sum('amount_paid');
    
        // Update the invoice with the new paid amount and change
        $invoice->update([
            'paid_amount' => $totalPaid,
            'change' => $invoice->total_amount - $totalPaid,
        ]);
    
        // If the invoice is fully paid, no future installments are needed
        if ($invoice->isFullyPaid()) {
            return redirect()->route('sales.installments.index', $invoice->id)->with('success', 'تم دفع المبلغ بالكامل.');
        }
    
        // Otherwise, set the next installment to be due in one month
        return redirect()->route('sales.installments.index', $invoice->id)->with('success', 'تمت إضافة القسط الأول بنجاح.');
    }    
    
    // Show the form to edit an installment
    public function editInstallment($invoiceId, $installmentId)
    {
        $invoice = Invoice::findOrFail($invoiceId);
        $installment = SalesInstallment::findOrFail($installmentId);

        return view('admin.invoices.edit-installment', compact('invoice', 'installment'));
    }

    // Update the installment
    public function updateInstallment(Request $request, $invoiceId, $installmentId)
    {
        $request->validate([
            'amount_paid' => 'required|numeric|min:0',
            'date_paid' => 'required|date',
            'agent' => 'required|string|max:255', // New validation for agent field
        ]);
        // Find the invoice and installment
        $invoice = Invoice::findOrFail($invoiceId);
        $installment = SalesInstallment::findOrFail($installmentId);

        // Update the installment with the new values
        $installment->update($request->only(['amount_paid', 'date_paid', 'agent'])); // Update agent\

        // Recalculate the total paid amount for the invoice
        $totalPaid = SalesInstallment::where('invoice_id', $invoice->id)->sum('amount_paid');

        // Update the paid_amount and change fields in the invoice
        $invoice->update([
            'paid_amount' => $totalPaid,
            'change' => $invoice->total_amount - $totalPaid,
        ]);

        // Redirect back to the installments page with success message
        return redirect()->route('sales.installments.index', $invoice->id)->with('success', 'تم تعديل القسط بنجاح.');
    }
    public function dailySummary(Request $request)
    {
        // Default date is today
        $date = $request->input('date', today()->toDateString());
    
        // Query installments collected on the specified date
        $installments = SalesInstallment::whereDate('date_paid', $date)->get();
    
        // Calculate total amount collected
        $totalCollected = $installments->sum('amount_paid');
    
        return view('admin.reports.daily_summary', compact('installments', 'totalCollected', 'date'));
    }
    
}
