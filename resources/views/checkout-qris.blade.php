@extends('layouts.app')
@section('content')
<div class="container" style="max-width: 500px;">
    <h3 class="mb-4">Pembayaran QRIS DOKU</h3>
    <div class="card p-4 shadow-sm border-0">
        <p>Total: <strong>Rp {{ number_format($total ?? 0, 0, ',', '.') }}</strong></p>
        <p>Silakan scan QRIS berikut untuk membayar pesanan Anda:</p>
        <div class="text-center mb-3">
            @if(isset($qrUrl))
                <img src="{{ $qrUrl }}" alt="QRIS" class="img-fluid rounded" style="background:#fff; padding:8px;">
            @else
                <span class="text-danger">QRIS tidak tersedia.</span>
            @endif
        </div>
        <div class="alert alert-info">Setelah pembayaran berhasil, order akan otomatis diproses.</div>
        <div class="mt-3 text-center">
            <a href="{{ route('cart') }}" class="btn btn-link">Kembali ke Keranjang</a>
        </div>
    </div>
</div>
@endsection
