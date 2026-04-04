<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class DokuQrisController extends Controller
{
    public function showQris(Request $request)
    {
        $cart = session('cart', []);
        $tableCode = session('table_code');
        if (!$tableCode || empty($cart)) {
            return redirect()->route('cart')->with('error', 'Keranjang kosong atau meja belum dipilih');
        }
        $total = array_sum(array_map(fn($item) => $item['price'] * $item['qty'], $cart));
        $invoice = 'INV-' . time();

        // Request ke DOKU
        $response = Http::withBasicAuth(env('DOKU_CLIENT_ID'), env('DOKU_SECRET_KEY'))
            ->post('https://api.doku.com/qris/v1/payment-code', [
                'order' => [
                    'invoice_number' => $invoice,
                    'amount' => $total,
                ],
                'merchant' => [
                    'id' => env('DOKU_MERCHANT_ID'),
                ],
                'additional_info' => [
                    'integration' => 'laravel-demo'
                ]
            ]);

        $data = $response->json();
        $qrString = $data['data']['qr_string'] ?? null;
        $qrUrl = 'https://api.qrserver.com/v1/create-qr-code/?size=220x220&data=' . urlencode($qrString);

        // Simpan invoice ke session untuk validasi webhook
        session(['doku_invoice' => $invoice]);

        return view('checkout-qris', compact('qrUrl', 'total'));
    }

    public function webhook(Request $request)
    {
        $payload = $request->all();
        // Validasi signature DOKU di sini (lihat dokumen DOKU)
        if (($payload['order']['invoice_number'] ?? null) === session('doku_invoice') &&
            ($payload['transaction']['status'] ?? null) === 'SUCCESS') {
            // Proses order: panggil checkout() atau logic order ke dapur
            // ...
        }
        return response()->json(['status' => 'ok']);
    }
}
