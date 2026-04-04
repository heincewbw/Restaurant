<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;

class FrontendController extends Controller
{
    public function scanForm()
    {
        return view('scan');
    }

    /**
     * Scan QR langsung dari URL: /table/MEJA-1
     * QR di meja berisi link: http://domain.com/table/MEJA-1
     */
    public function scanDirect($code)
    {
        $validTables = config('restaurant.valid_table_codes', []);
        $tableCode = strtoupper(trim($code));

        if (ctype_digit($tableCode)) {
            $tableCode = 'MEJA-' . ltrim($tableCode, '0');
        }

        if (!in_array($tableCode, $validTables, true)) {
            return redirect()->route('scan')->with('error', 'Kode meja tidak valid.');
        }

        $currentCartTable = session('cart_table_code');
        if ($currentCartTable && $currentCartTable !== $tableCode) {
            session()->forget(['cart', 'cart_table_code']);
        }
        session(['table_code' => $tableCode]);

        return redirect()->route('menu.list');
    }

    public function scanSubmit(Request $request)
    {
        $data = $request->validate(['table_code' => 'required|string']);

        $validTables = config('restaurant.valid_table_codes', []);
        $tableCode = strtoupper(trim($data['table_code']));

        // Perbolehkan input angka langsung (misalnya 1 -> MEJA-1) untuk pengalaman scan yang mudah.
        if (ctype_digit($tableCode)) {
            $tableCode = 'MEJA-' . ltrim($tableCode, '0');
        }

        if (!in_array($tableCode, $validTables, true)) {
            return back()->withErrors(['table_code' => 'Kode meja tidak valid. Scan QR/barcode pada meja yang tersedia.'])->withInput();
        }

        // Reset cart jika meja diganti
        $currentCartTable = session('cart_table_code');
        if ($currentCartTable && $currentCartTable !== $tableCode) {
            session()->forget(['cart', 'cart_table_code']);
        }
        session(['table_code' => $tableCode]);

        return redirect()->route('menu.list')->with('success', 'Meja ' . $tableCode . ' dipilih');
    }

    public function menuList()
    {
        $tableCode = session('table_code');

        if (!$tableCode) {
            return redirect()->route('scan')->with('error', 'Masukkan kode meja terlebih dahulu');
        }

        $cartTableCode = session('cart_table_code');
        if ($cartTableCode && $cartTableCode !== $tableCode) {
            session()->forget(['cart', 'cart_table_code']);
        }

        $menus = Menu::all()->groupBy('group');
        return view('menu', compact('menus', 'tableCode'));
    }

    public function cart()
    {
        $cart = session('cart', []);
        $tableCode = session('table_code');

        if (!$tableCode) {
            return redirect()->route('scan')->with('error', 'Masukkan kode meja terlebih dahulu');
        }

        $cartTableCode = session('cart_table_code');
        if ($cartTableCode && $cartTableCode !== $tableCode) {
            session()->forget(['cart', 'cart_table_code']);
            $cart = [];
        }
        return view('cart', compact('cart', 'tableCode'));
    }

    public function cartAdd(Request $request)
    {
        $request->validate([
            'menu_id' => 'required|exists:menus,id',
            'qty' => 'required|integer|min:1'
        ]);

        $this->addMenuToCart($request->menu_id, $request->qty);

        return redirect()->route('cart')->with('success', 'Ditambahkan ke keranjang');
    }

    public function cartAddMultiple(Request $request)
    {
        $data = $request->validate([
            'items' => 'required|array|min:1',
            'items.*.menu_id' => 'required|exists:menus,id',
            'items.*.qty' => 'required|integer|min:1',
        ]);

        foreach ($data['items'] as $item) {
            $this->addMenuToCart($item['menu_id'], $item['qty']);
        }

        return redirect()->route('cart')->with('success', 'Menu terpilih berhasil ditambahkan ke keranjang');
    }

    private function addMenuToCart($menuId, $qty)
    {
        $tableCode = session('table_code');
        if (!$tableCode) {
            abort(400, 'Meja belum dipilih.');
        }

        $currentCartTable = session('cart_table_code');
        if ($currentCartTable && $currentCartTable !== $tableCode) {
            // Reset keranjang untuk memisahkan per meja
            session()->forget('cart');
        }
        session(['cart_table_code' => $tableCode]);

        $menu = Menu::findOrFail($menuId);
        $cart = session('cart', []);
        $id = $menu->id;

        if (isset($cart[$id])) {
            $cart[$id]['qty'] += $qty;
        } else {
            $cart[$id] = [
                'id' => $id,
                'name' => $menu->name,
                'price' => $menu->price,
                'qty' => $qty,
            ];
        }

        session(['cart' => $cart]);
    }

    public function checkout(Request $request)
    {
        $cart = session('cart', []);
        $tableCode = session('table_code');

        $cartTableCode = session('cart_table_code');
        if ($cartTableCode && $cartTableCode !== $tableCode) {
            return redirect()->route('scan')->with('error', 'Terdapat keranjang dari meja lain. Silakan scan kembali meja yang sesuai.');
        }

        if (!$tableCode) {
            return redirect()->route('scan')->with('error', 'Kode meja tidak ditemukan');
        }

        if (empty($cart)) {
            return redirect()->route('cart')->with('error', 'Keranjang kosong');
        }

        $total = array_sum(array_map(fn($item) => $item['price'] * $item['qty'], $cart));

        $order = Order::create([
            'total' => $total,
            'status' => 'paid',
            'paid_at' => now(),
            'table_code' => $tableCode,
        ]);

        foreach ($cart as $item) {
            OrderItem::create([
                'order_id' => $order->id,
                'menu_id' => $item['id'],
                'quantity' => $item['qty'],
                'price' => $item['price'],
            ]);
        }

        session()->forget(['cart', 'cart_table_code']);

        return redirect()->route('menu.list')->with('success', 'Pesanan meja ' . $tableCode . ' berhasil, silakan tunggu di meja.');
    }

    public function checkoutQris(Request $request)
    {
        $cart = session('cart', []);
        $tableCode = session('table_code');
        if (!$tableCode || empty($cart)) {
            return redirect()->route('cart')->with('error', 'Keranjang kosong atau meja belum dipilih');
        }
        return view('checkout-qris');
    }

    public function checkoutQrisConfirm(Request $request)
    {
        // Setelah "Sudah Bayar" ditekan, baru proses order ke dapur
        return $this->checkout($request);
    }
}
