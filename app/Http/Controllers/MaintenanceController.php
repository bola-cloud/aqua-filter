<?php

namespace App\Http\Controllers;

use App\Models\Maintenance;
use Illuminate\Http\Request;
use App\Models\Client;
use App\Models\Product;

class MaintenanceController extends Controller
{
    public function index()
    {
        $maintenances = Maintenance::with('client', 'product')->paginate(20);
        $clients = Client::where('can_have_maintenance',1)->get();  // Fetch all clients for the dropdown
        return view('admin.maintenances.index', compact('maintenances', 'clients'));
    }    

    public function search(Request $request)
    {
        $query = $request->input('query');
        $clientId = $request->input('client_id');
        $maintenanceDate = $request->input('maintenance_date');
        
        $maintenances = Maintenance::with('client', 'product')
            ->when($clientId, function ($query) use ($clientId) {
                return $query->where('client_id', $clientId);
            })
            ->when($maintenanceDate, function ($query) use ($maintenanceDate) {
                return $query->whereDate('maintenance_date', $maintenanceDate);
            })
            ->where(function($q) use ($query) {
                $q->whereHas('client', function($q) use ($query) {
                    $q->where('name', 'like', '%' . $query . '%');
                })
                ->orWhereHas('product', function($q) use ($query) {
                    $q->where('name', 'like', '%' . $query . '%');
                })
                ->orWhere('maintenance_cost', 'like', '%' . $query . '%')
                ->orWhere('description', 'like', '%' . $query . '%');
            })
            ->get();
        
        return response()->json($maintenances);
    }
    
    public function printTable()
    {
        $maintenances = Maintenance::with('client', 'product')->get(); // Get all records without pagination
        return view('admin.maintenances.print_table', compact('maintenances'));
    }

    public function clientMaintenanceStatement(Client $client)
    {
        $maintenances = Maintenance::with('product')
            ->where('client_id', $client->id)
            ->get();

        // Calculate the total cost of maintenance services
        $totalMaintenanceCost = $maintenances->sum('maintenance_cost');

        return view('admin.maintenances.client_statement', compact('client', 'maintenances', 'totalMaintenanceCost'));
    }

    public function clientIndex()
    {
        $clients = Client::withCount('maintenances') // Load clients with maintenance count
            ->paginate(20); // Adjust pagination as needed

        return view('admin.maintenances.client_index', compact('clients'));
    }

    public function create()
    {
        // Provide data for client and product selection
        $clients = Client::all(); 
        $products = Product::all(); 
        return view('admin.maintenances.create',compact('products','clients'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'client_id' => 'nullable|exists:clients,id',
            'product_id' => 'nullable|exists:products,id',
            'maintenance_cost' => 'required|numeric',
            'description' => 'nullable|string',
            'maintenance_date'=>'nullable|date',
        ]);

        Maintenance::create($request->all());

        return redirect()->route('maintenances.index')->with('success', 'تم انشاء فاتورة الصيانة بنجاح');
    }

    public function edit(Maintenance $maintenance)
    {
        $clients = Client::all(); 
        $products = Product::all(); 
        return view('admin.maintenances.edit', compact('maintenance','clients','products'));
    }

    public function update(Request $request, Maintenance $maintenance)
    {
        $request->validate([
            'client_id' => 'nullable|exists:clients,id',
            'product_id' => 'nullable|exists:products,id',
            'maintenance_cost' => 'required|numeric',
            'description' => 'nullable|string',
            'maintenance_date'=>'nullable|date',
        ]);

        $maintenance->update($request->all());

        return redirect()->route('maintenances.index')->with('success', 'تم تحديث فاتورة الصيانة بنجاح.');
    }

    public function destroy(Maintenance $maintenance)
    {
        $maintenance->delete();
        return redirect()->route('maintenances.index')->with('success', 'تم حذف فاتورة الصيانة بنجاح.');
    }

    public function print($id)
    {
        $maintenance = Maintenance::with('client', 'product')->findOrFail($id);

        // Pass the maintenance record to the print view
        return view('admin.maintenances.print', compact('maintenance'));
    }

}
