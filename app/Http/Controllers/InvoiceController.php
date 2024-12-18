<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Invoice;
use App\Models\Product;
use App\Models\Sales;
use Carbon\Carbon;
use App\Models\Village;
use AgeekDev\Barcode\Facades\Barcode;
use AgeekDev\Barcode\Enums\Type;
use Storage;

class InvoiceController extends Controller
{
    public function index(Request $request)
    {
        $query = Invoice::with('client', 'installments');
    
        // Add filter for invoices where the last installment exceeds one month
        if ($request->input('installment_exceeded')) {
            $query->whereHas('installments', function ($q) {
                $q->whereRaw('DATE_SUB(CURDATE(), INTERVAL 1 MONTH) >= date_paid');
            });
        }
    
        $invoices = $query->paginate(20);
        $villages = Village::all(); // Load villages for dropdown
    
        return view('admin.invoices.index', compact('invoices', 'villages'));
    }
    
    public function search(Request $request)
    {
        $query = $request->input('query');
        $date_from = $request->input('date_from') ? Carbon::parse($request->input('date_from'))->startOfDay() : null;
        $date_to = $request->input('date_to') ? Carbon::parse($request->input('date_to'))->endOfDay() : null;
        $village = $request->input('village');
        $installment_exceeded = $request->input('installment_exceeded');
        $company = $request->input('company'); // New company filter

        $invoices = Invoice::with('client.village');
    
        // Search by query for client fields (name, phone)
        if ($query) {
            $invoices->whereHas('client', function($q) use ($query) {
                $q->where('name', 'like', "%{$query}%")
                  ->orWhere('phone', 'like', "%{$query}%");
            });
        }
    
        // Search by village name in the client's village relationship
        if ($village) {
            $invoices->whereHas('client.village', function($q) use ($village) {
                $q->where('name', 'like', "%{$village}%");
            });
        }

        // Filter by company
        if ($company) {
            $invoices->where('company', $company);
        }
    
        // Filter for last installment exceeding one month
        if ($installment_exceeded) {
            $invoices->whereHas('installments', function($q) {
                $q->whereRaw('DATE_SUB(CURDATE(), INTERVAL 1 MONTH) >= date_paid');
            });
        }
    
        // Search by date range
        if ($date_from && $date_to) {
            $invoices->whereBetween('created_at', [$date_from, $date_to]);
        } elseif ($date_from) {
            $invoices->whereDate('created_at', '>=', $date_from);
        } elseif ($date_to) {
            $invoices->whereDate('created_at', '<=', $date_to);
        }
    
        $invoices = $invoices->paginate(20);
        $villages = Village::all();  // Fetch all villages for the dropdown
        return view('admin.invoices.index', compact('invoices', 'villages'));
    }
    
    public function updatePayment(Request $request, $id)
    {
        // Validate the input
        $request->validate([
            'paid_amount' => 'required|numeric|min:0',
        ]);

        // Find the invoice
        $invoice = Invoice::findOrFail($id);

        // Recalculate the change
        $totalAfterDiscount = $invoice->subtotal - $invoice->discount;
        $paidAmount = $request->input('paid_amount');
        $change = $totalAfterDiscount - $paidAmount;

        // Update the invoice with the new paid amount and change
        $invoice->update([
            'paid_amount' => $paidAmount,
            'change' => $change, // This could be negative if the user overpaid
        ]);

        // Redirect back to the invoice with a success message
        return redirect()->route('invoices.show', $invoice->id)
                        ->with('success', 'تم تحديث المبلغ المدفوع والتغيير بنجاح.');
    }    

    public function show(Invoice $invoice)
    {
        $products = Product::where('quantity', '>', 0)->get(); // Get all products with stock available
        return view('admin.invoices.details', compact('invoice', 'products'));
    }
    

    public function getDetails(Invoice $invoice)
    {
        $invoice->load('sales.product'); // Load related sales and products
        return response()->json($invoice);
    }

    public function updateDiscount(Request $request, $id)
    {
        // Validate the input
        $request->validate([
            'discount' => 'required|numeric|min:0',
        ]);

        // Find the invoice
        $invoice = Invoice::findOrFail($id);

        // Update the discount and recalculate total_amount and change
        $subtotal = $invoice->subtotal; // Retrieve the existing subtotal
        $newDiscount = $request->input('discount'); // Get the new discount

        // Recalculate total_amount and change
        $totalAfterDiscount = $subtotal - $newDiscount;
        $change = $totalAfterDiscount - $invoice->paid_amount;

        // Update the invoice with the new values
        $invoice->update([
            'discount' => $newDiscount,
            'total_amount' => $totalAfterDiscount,
            'change' => $change, // Recalculate the change based on paid_amount
        ]);

        // Redirect back to the invoice with a success message
        return redirect()->route('invoices.show', $invoice->id)
                        ->with('success', 'تم تحديث الخصم والإجمالي بنجاح.');
    }

    public function returnProducts(Request $request, Invoice $invoice)
    {
        $sales = $request->input('sales', []);
        foreach ($sales as $saleId => $quantity) {
            $sale = $invoice->sales()->findOrFail($saleId);
            if ($quantity > 0 && $quantity <= $sale->quantity) {
                // Return the products to the stock
                $product = $sale->product;
                $product->quantity += $quantity;
                $product->save();
    
                // Adjust the sale record
                $sale->quantity -= $quantity;
                $sale->total_price = $sale->quantity * $product->selling_price;
                $sale->save();
    
                // If all products are returned, remove the sale record
                if ($sale->quantity == 0) {
                    $sale->delete();
                }
            }
        }
    
        // Recalculate the subtotal
        $invoice->subtotal = $invoice->sales->sum('total_price');
    
        // Recalculate the discount based on the new subtotal
        $invoice->discount = $this->calculateDiscount($invoice->subtotal);
    
        // Update the total amount after applying the discount
        $invoice->total_amount = $invoice->subtotal - $invoice->discount;
    
        $invoice->save();
    
        return redirect()->route('invoices.show', $invoice->id)->with('success', 'تمت عملية الإرجاع بنجاح.');
    }
    
    
    public function addProduct(Request $request, Invoice $invoice)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
        ]);
    
        $product = Product::findOrFail($request->input('product_id'));
        $quantity = $request->input('quantity');
    
        // Check if the product already exists in the invoice
        $existingSale = $invoice->sales()->where('product_id', $product->id)->first();
    
        if ($existingSale) {
            // If the product exists, update the quantity and total price
            $existingSale->quantity += $quantity;
            $existingSale->total_price = $existingSale->quantity * $product->selling_price;
            $existingSale->save();
        } else {
            // If the product doesn't exist, create a new sale entry
            $sale = new Sales();
            $sale->invoice_id = $invoice->id;
            $sale->product_id = $product->id;
            $sale->quantity = $quantity;
            $sale->total_price = $quantity * $product->selling_price;
            $sale->save();
        }
    
        // Update the product stock
        $product->quantity -= $quantity;
        $product->save();
    
        // Recalculate the subtotal
        $invoice->subtotal = $invoice->sales->sum('total_price');
    
        // Recalculate the discount based on the new subtotal
        $invoice->discount = $this->calculateDiscount($invoice->subtotal);
    
        // Update the total amount after applying the discount
        $invoice->total_amount = $invoice->subtotal - $invoice->discount;
    
        $invoice->save();
    
        return redirect()->route('invoices.show', $invoice->id)->with('success', 'تمت إضافة المنتج إلى الفاتورة بنجاح.');
    }
    
    public function destroy(Invoice $invoice)
    {
        foreach ($invoice->sales as $sale) {
            // Return all products sold in this invoice to the stock
            $product = $sale->product;
            $product->quantity += $sale->quantity;
            $product->save();
    
            // Delete the sale record
            $sale->delete();
        }
    
        // Delete the invoice
        $invoice->delete();
    
        return redirect()->route('invoices.index')->with('success', 'تم حذف الفاتورة وإرجاع الكميات بنجاح.');
    }
    
    private function calculateDiscount($subtotal)
    {
        if ($subtotal >= 6000) {
            return 500;
        } elseif ($subtotal >= 5000) {
            return 400;
        } elseif ($subtotal >= 4000) {
            return 300;
        } elseif ($subtotal >= 3000) {
            return 200;
        } else {
            return 0;
        }
    }
    public function redirectToInstallment(Invoice $invoice)
    {
        return redirect()->route('sales.installments.index', $invoice->id);
    }

    public function scanBarcode()
    {
        return view('admin.invoices.scan_barcode');
    }
    public function redirectToInstallmentByBarcode(Request $request)
    {
        $validated = $request->validate([
            'barcode' => 'required|string'
        ]);
    
        $invoice = Invoice::where('invoice_code', $validated['barcode'])->first();
    
        if (!$invoice) {
            return redirect()->route('invoices.scan')->with('error', 'Invalid barcode.');
        }
    
        return redirect()->route('sales.installments.index', $invoice->id);
    }
    public function generateInvoiceBarcode(Invoice $invoice)
    {
        // Use `invoice_code` as the barcode content
        $barcodeContent = $invoice->invoice_code;
    
        // Check if `invoice_code` is set and valid
        if (!$barcodeContent) {
            return redirect()->back()->with('error', 'Invoice code is missing.');
        }
    
        // Generate the barcode image as SVG
        $barcodeImage = \DNS1D::getBarcodeSVG($barcodeContent, 'C128');
    
        // Save the barcode image to storage (optional)
        $filePath = 'barcodes/' . $barcodeContent . '.svg';
        Storage::disk('public')->put($filePath, $barcodeImage);
    
        // Update the invoice with the barcode path
        $invoice->update(['invoice_code' => $barcodeContent]);
    
        return view('admin.invoices.generate_barcode', compact('barcodeImage', 'invoice'));
    }   
    
}
