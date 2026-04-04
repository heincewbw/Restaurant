<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;

class KitchenController extends Controller
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            $simpleUser = session('simple_user');
            if ($simpleUser && $simpleUser['role'] === 'koki') {
                return $next($request);
            }
            abort(403, 'Akses ditolak. Hanya koki dapat mengakses halaman ini.');
        });
    }

    public function index()
    {
        $orders = Order::whereIn('status', ['paid', 'cooking'])->with('items.menu')->get();
        return view('kitchen', compact('orders'));
    }

    public function start(Order $order)
    {
        $order->update(['status' => 'cooking']);
        return back()->with('success', 'Order mulai dimasak');
    }

    public function done(Order $order)
    {
        $order->update(['status' => 'done']);
        return back()->with('success', 'Order selesai');
    }
}
