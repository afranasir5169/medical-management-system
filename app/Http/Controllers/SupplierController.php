<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use Illuminate\Http\Request;

class SupplierController extends Controller
{
    public function index()
    {
        $suppliers = Supplier::latest()->paginate(10);
        return view('suppliers.index', compact('suppliers'));
    }

    public function create()
    {
        // optional separate create view — we'll combine form & list in index
        return redirect()->route('suppliers.index');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'supplier_id'  => 'nullable|string|unique:suppliers,supplier_id',
            'name'         => 'required|string|max:191',
            'company_name' => 'nullable|string|max:191',
            'email'        => 'nullable|email|max:191',
            'phone'        => 'nullable|string|max:50',
            'address'      => 'nullable|string',
            'city'         => 'nullable|string|max:100',
            'district'     => 'nullable|string|max:100',
            'supply_type'  => 'nullable|string|max:100',
            'description'  => 'nullable|string',
        ]);

        Supplier::create($data);

        return redirect()->route('suppliers.index')->with('success', 'Supplier saved.');
    }

    public function show(Supplier $supplier)
    {
        return view('suppliers.show', compact('supplier'));
    }

    public function edit(Supplier $supplier)
    {
        // We'll reuse index page and open edit form — simple approach:
        $suppliers = Supplier::latest()->paginate(10);
        return view('suppliers.index', compact('suppliers', 'supplier'));
    }

    public function update(Request $request, Supplier $supplier)
    {
        $data = $request->validate([
            'supplier_id'  => 'nullable|string|unique:suppliers,supplier_id,' . $supplier->id,
            'name'         => 'required|string|max:191',
            'company_name' => 'nullable|string|max:191',
            'email'        => 'nullable|email|max:191',
            'phone'        => 'nullable|string|max:50',
            'address'      => 'nullable|string',
            'city'         => 'nullable|string|max:100',
            'district'     => 'nullable|string|max:100',
            'supply_type'  => 'nullable|string|max:100',
            'description'  => 'nullable|string',
        ]);

        $supplier->update($data);

        return redirect()->route('suppliers.index')->with('success', 'Supplier updated.');
    }

    public function destroy(Supplier $supplier)
    {
        $supplier->delete();
        return redirect()->route('suppliers.index')->with('success', 'Supplier deleted.');
    }
}
