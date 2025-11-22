<?php

namespace App\Http\Controllers;

use App\Models\Grn;
use App\Models\GrnItem;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use DB;

class GrnController extends Controller
{
    public function index()
    {
        $grns = Grn::latest()->paginate(10);
        return view('grns.index', compact('grns'));
    }

    public function create()
    {
        $suppliers = Supplier::orderBy('name')->get(); // if supplier exists
        // auto-generate GRN number
        $last = Grn::latest()->first();
        $num = $last ? intval(Str::after($last->grn_number, 'GRN-')) + 1 : 1;
        $grnNumber = 'GRN-' . str_pad($num, 3, '0', STR_PAD_LEFT);

        return view('grns.create', compact('suppliers','grnNumber'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'grn_number' => 'required|unique:grns,grn_number',
            'invoice_number' => 'nullable|string|max:100',
            'supplier_id' => 'nullable|exists:suppliers,id',
            'grn_date' => 'nullable|date',
            'discount_percent' => 'nullable|numeric|min:0',
            'items' => 'required|array|min:1',
            'items.*.item_name' => 'required|string',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.price_per_unit' => 'required|numeric|min:0',
        ]);

        DB::beginTransaction();
        try {
            $data = $request->only(['grn_number','invoice_number','supplier_id','grn_date','discount_percent','remarks']);
            $itemsData = $request->input('items', []);
            $total = 0;

            foreach ($itemsData as $row) {
                $lineTotal = (float)$row['quantity'] * (float)$row['price_per_unit'];
                $total += $lineTotal;
            }

            $discountPercent = $request->input('discount_percent', 0);
            $discountAmount = ($discountPercent/100) * $total;
            $netTotal = round($total - $discountAmount, 2);

            $data['total'] = $total;
            $data['net_total'] = $netTotal;

            $grn = Grn::create($data);

            foreach ($itemsData as $row) {
                GrnItem::create([
                    'grn_id' => $grn->id,
                    'item_code' => $row['item_code'] ?? null,
                    'item_name' => $row['item_name'],
                    'quantity' => intval($row['quantity']),
                    'price_per_unit' => number_format((float)$row['price_per_unit'],2,'.',''),
                    'total_price' => number_format((intval($row['quantity']) * (float)$row['price_per_unit']),2,'.',''),
                ]);
            }

            DB::commit();
            return redirect()->route('grns.show', $grn)->with('success','GRN saved.');
        } catch (\Throwable $e) {
            DB::rollBack();
            return back()->withInput()->withErrors(['error' => $e->getMessage()]);
        }
    }

    public function show(Grn $grn)
    {
        $grn->load('items','supplier');
        return view('grns.show', compact('grn'));
    }

    public function edit(Grn $grn)
    {
        $suppliers = Supplier::orderBy('name')->get();
        $grn->load('items');
        return view('grns.edit', compact('grn','suppliers'));
    }

    public function update(Request $request, Grn $grn)
    {
        $request->validate([
            'invoice_number' => 'nullable|string|max:100',
            'supplier_id' => 'nullable|exists:suppliers,id',
            'grn_date' => 'nullable|date',
            'discount_percent' => 'nullable|numeric|min:0',
            'items' => 'required|array|min:1',
            'items.*.item_name' => 'required|string',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.price_per_unit' => 'required|numeric|min:0',
        ]);

        DB::beginTransaction();
        try {
            $itemsData = $request->input('items', []);
            $total = 0;
            foreach ($itemsData as $row) {
                $lineTotal = (float)$row['quantity'] * (float)$row['price_per_unit'];
                $total += $lineTotal;
            }
            $discountPercent = $request->input('discount_percent', 0);
            $discountAmount = ($discountPercent/100) * $total;
            $netTotal = round($total - $discountAmount, 2);

            $grn->update($request->only(['invoice_number','supplier_id','grn_date','discount_percent','remarks']));
            $grn->update(['total' => $total, 'net_total' => $netTotal]);

            $grn->items()->delete();
            foreach ($itemsData as $row) {
                GrnItem::create([
                    'grn_id' => $grn->id,
                    'item_code' => $row['item_code'] ?? null,
                    'item_name' => $row['item_name'],
                    'quantity' => intval($row['quantity']),
                    'price_per_unit' => number_format((float)$row['price_per_unit'],2,'.',''),
                    'total_price' => number_format((intval($row['quantity']) * (float)$row['price_per_unit']),2,'.',''),
                ]);
            }

            DB::commit();
            return redirect()->route('grns.show', $grn)->with('success','GRN updated.');
        } catch (\Throwable $e) {
            DB::rollBack();
            return back()->withInput()->withErrors(['error' => $e->getMessage()]);
        }
    }

    public function destroy(Grn $grn)
    {
        $grn->delete();
        return redirect()->route('grns.index')->with('success','GRN deleted.');
    }
}
