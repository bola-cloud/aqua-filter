<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Client;
use App\Models\Village;

class ClientController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $villageId = $request->input('village_id');
        $noInvoices = $request->input('no_invoices'); // New filter for clients without invoices
    
        $clients = Client::with('village', 'invoices')
            ->when($search, function($query, $search) {
                return $query->where('name', 'like', '%' . $search . '%')
                             ->orWhere('phone', 'like', '%' . $search . '%')
                             ->orWhere('address', 'like', '%' . $search . '%')
                             ->orWhere('code', 'like', '%' . $search . '%');
            })
            ->when($villageId, function($query, $villageId) {
                return $query->where('village_id', $villageId);
            })
            ->when($noInvoices, function($query) {
                return $query->doesntHave('invoices');
            })
            ->paginate(20);
    
        $villages = Village::all();
    
        return view('admin.clients.index', compact('clients', 'villages'));
    }
    
    public function printTable(Request $request)
    {
        $clients = Client::with('invoices')
            ->when($request->input('no_invoices'), function($query) {
                return $query->doesntHave('invoices');
            })
            ->get();
    
        $totalInvoicesAmount = $clients->sum(function($client) {
            return $client->invoices->sum('total_amount');
        });
    
        return view('admin.clients.print_table', compact('clients', 'totalInvoicesAmount'));
    }

    public function printStatement(Client $client)
    {
        $client->load('invoices'); // Load the client with invoices
        $totalInvoices = $client->invoices->sum('total_amount');
        $totalPaidAmount = $client->invoices->sum('paid_amount');
        $totalChange = $client->invoices->sum('change');

        return view('admin.clients.print_statement', compact('client', 'totalInvoices', 'totalPaidAmount', 'totalChange'));
    }
    
    /**
     * Display the specified client and their invoices.
     */
    public function show(Client $client)
    {
        // Load the client's invoices
        $client->load('invoices');
    
        // Calculate the total invoices amount, total paid amount, and total change
        $totalInvoices = $client->invoices->sum('total_amount');
        $totalPaidAmount = $client->invoices->sum('paid_amount');
        $totalChange = $totalInvoices - $totalPaidAmount;
    
        // Determine debtor status
        $isDebtor = $totalPaidAmount < $totalInvoices;
    
        // Pass the calculated values and debtor status to the view
        return view('admin.clients.show', compact('client', 'totalInvoices', 'totalPaidAmount', 'totalChange', 'isDebtor'));
    }
    
    public function updateFlags(Request $request, Client $client)
    {
        $request->validate([
            'can_have_maintenance' => 'required|boolean',
            'can_have_invoice' => 'required|boolean',
        ]);

        $client->update([
            'can_have_maintenance' => $request->can_have_maintenance,
            'can_have_invoice' => $request->can_have_invoice,
        ]);

        return redirect()->route('clients.show', $client->id)->with('success', 'تم تحديث الحالة بنجاح.');
    }

    /**
     * Store a newly created client in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'phone' => 'required|string',
            'address' => 'required|string',
            'code' => 'required|string|unique:clients,code',
            'village_id' => 'nullable|exists:villages,id',
            'files.*' => 'file|mimes:jpg,jpeg,png,pdf,doc,docx|max:2048', // Validate files
        ]);
    
        $client = Client::create($request->only(['name', 'phone', 'address', 'code', 'village_id']));
    
        if ($request->hasFile('files')) {
            foreach ($request->file('files') as $file) {
                $path = $file->store('client_files', 'public');
                $client->files()->create(['path' => $path]);
            }
        }
    
        return redirect()->route('clients.index')->with('success', 'تم إضافة العميل بنجاح');
    }    

    /**
     * Show the form for editing the specified client.
     */
    public function edit(Client $client)
    {
        // Load the client's related files
        $client->load('files');
    
        return response()->json([
            'id' => $client->id,
            'name' => $client->name,
            'phone' => $client->phone,
            'address' => $client->address,
            'code' => $client->code,
            'village_id' => $client->village_id,
            'files' => $client->files->map(function($file) {
                return [
                    'id' => $file->id,
                    'path' => $file->path,
                    'url' => asset('storage/' . $file->path) // Generate the file URL
                ];
            }),
        ]);
    }    

    /**
     * Update the specified client in storage.
     */
    public function update(Request $request, Client $client)
    {
        $request->validate([
            'name' => 'required|string',
            'phone' => 'required|string',
            'address' => 'required|string',
            'code' => 'required|string|unique:clients,code,' . $client->id,
            'village_id' => 'nullable|exists:villages,id',
            'files.*' => 'file|mimes:jpg,jpeg,png,pdf,doc,docx|max:2048', // Validate files
        ]);
    
        $client->update($request->only(['name', 'phone', 'address', 'code', 'village_id']));
    
        if ($request->hasFile('files')) {
            foreach ($request->file('files') as $file) {
                $path = $file->store('client_files', 'public');
                $client->files()->create(['path' => $path]);
            }
        }
    
        return redirect()->route('clients.index')->with('success', 'تم تحديث بيانات العميل بنجاح');
    }
    

    /**
     * Remove the specified client from storage.
     */
    public function destroy(Client $client)
    {
        $client->delete();

        return redirect()->route('clients.index')->with('success', 'تم حذف العميل بنجاح.');
    }
}
