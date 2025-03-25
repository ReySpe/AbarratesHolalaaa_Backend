<?php

namespace App\Http\Controllers;

use App\Models\Purchase;
use Illuminate\Http\Request;

class PurchaseController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'supplier_id' => 'required|string',
            'date' => 'required|date',
            'status' => 'required|string',
            'total' => 'required|numeric',
        ]);

        $purchaseOrder = Purchase::create([
            'supplier_id' => $request->supplier_id,
            'products' => [], // Initially empty
            'date' => $request->date,
            'status' => $request->status,
            'total' => $request->total
        ]);

        return response()->json([
            "success" => true,
            "data" => $purchaseOrder
        ], 201);
    }

    // Add a product to an existing purchase order
    public function addProduct(Request $request, $id)
    {
        $request->validate([
            'product_id' => 'required|string',
            'quantity' => 'required|numeric',
            'unit_price' => 'required|numeric',
            'subtotal' => 'required|numeric',
        ]);

        $purchaseOrder = Purchase::find($id);

        if (!$purchaseOrder) {
            return response()->json(['message' => 'Purchase order not found'], 404);
        }

        // Append the new product to the existing array
        $purchaseOrder->push('products', [
            'product_id' => $request->product_id,
            'quantity' => $request->quantity,
            'unit_price' => $request->unit_price,
            'subtotal' => $request->subtotal
        ]);

        return response()->json([
            "success" => true,
            "data" => $purchaseOrder
        ], 200);
    }
}
