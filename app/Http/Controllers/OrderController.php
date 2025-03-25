<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index(){
        $orders = Order::orderBy('date', 'desc')->get();

        return response()->json([
            'success' => true,
            'data' => $orders,
        ], 200);
    }

    public function indexPedidos(){
        $orders = Order::whereIn('status', ['A Pagar', 'Pagado'])
                ->orderBy('date', 'desc')->get();

        return view('/orders/list')->with('orders', $orders);
    }

    public function show($orderId){
        $order = Order::with(['orderDetails.product']) ->findOrFail($orderId);

        return view('/customers/order_show', compact('order'));
    }

    public function getRecentOrders(Request $request){
        $lastChecked = $request->query('lastChecked', now()->subMinutes(5)->toDateTimeString());
        $recentOrders = Order::where('created_at', '>', $lastChecked)->get();

        return response()->json($recentOrders);
    }

    public function deliver($id){
        $order = Order::find($id);

        $order->status = "Entregado";
        $order->save();

        return redirect()->back();
    }

}
