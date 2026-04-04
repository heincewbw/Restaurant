@extends('layouts.app')
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-primary text-white">
                    <h3 class="mb-0">Keranjang Belanja</h3>
                </div>
                <div class="card-body">
                    @php
                        $sessionTableCode = session('table_code');
                        $cartTableCode = session('cart_table_code');
                    @endphp
                    @if(!$sessionTableCode)
                        <div class="alert alert-danger">Silakan scan kode meja terlebih dahulu.</div>
                        <a href="{{ route('scan') }}" class="btn btn-primary">Kembali ke scan meja</a>
                        @php return; @endphp
                    @endif
                    @if($cartTableCode && $cartTableCode !== $sessionTableCode)
                        <div class="alert alert-warning">Keranjang saat ini milik meja {{ $cartTableCode }}. Silakan checkout atau kosongkan keranjang sebelum pesan untuk meja {{ $sessionTableCode }}.</div>
                        <a href="{{ route('menu.list') }}" class="btn btn-primary">Kembali ke menu {{ $sessionTableCode }}</a>
                        @php return; @endphp
                    @endif
                    @if(session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif
                    @if(session('error'))
                        <div class="alert alert-danger">{{ session('error') }}</div>
                    @endif
                    @if(empty($cart))
                        <p>Keranjang kosong. <a href="{{ route('menu.list') }}">Lihat menu</a></p>
                    @else
                        <table class="table align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>Menu</th>
                                    <th>Harga</th>
                                    <th>Jumlah</th>
                                    <th>Subtotal</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php $total = 0; @endphp
                                @foreach($cart as $item)
                                    @php $subtotal = $item['price'] * $item['qty']; $total += $subtotal; @endphp
                                    <tr>
                                        <td>{{ $item['name'] }}</td>
                                        <td>Rp {{ number_format($item['price'], 0, ',', '.') }}</td>
                                        <td>
                                            <div class="input-group input-group-sm qty-group" style="max-width: 120px;">
                                                <button type="button" class="btn btn-outline-secondary btn-qty-down">&#8595;</button>
                                                <input type="number" class="form-control text-center cart-qty-input" min="1" value="{{ $item['qty'] }}" data-menu-id="{{ $item['id'] }}">
                                                <button type="button" class="btn btn-outline-secondary btn-qty-up">&#8593;</button>
                                            </div>
                                        </td>
                                        <td class="cart-subtotal" data-menu-id="{{ $item['id'] }}">Rp {{ number_format($subtotal, 0, ',', '.') }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th colspan="3">Total</th>
                                    <th id="cart-total">Rp {{ number_format($total, 0, ',', '.') }}</th>
                                </tr>
                            </tfoot>
                        </table>
                        <div class="d-flex gap-2">
                            <a href="{{ route('checkout.qris') }}" class="btn btn-success flex-fill">Bayar via QRIS</a>
                        </div>
                    @endif
                    <div class="mt-3">
                        <a href="{{ route('menu.list') }}" class="btn btn-secondary">Kembali ke Menu</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@push('scripts')
<script>
        function formatRupiah(value) {
            return 'Rp ' + value.toLocaleString('id-ID', { minimumFractionDigits: 0, maximumFractionDigits: 0 });
        }

        document.querySelectorAll('.btn-qty-up').forEach(function(btn) {
            btn.addEventListener('click', function() {
                const input = btn.parentElement.querySelector('.cart-qty-input');
                input.value = parseInt(input.value) + 1;
                input.dispatchEvent(new Event('input'));
            });
        });
        document.querySelectorAll('.btn-qty-down').forEach(function(btn) {
            btn.addEventListener('click', function() {
                const input = btn.parentElement.querySelector('.cart-qty-input');
                if (parseInt(input.value) > 1) {
                    input.value = parseInt(input.value) - 1;
                    input.dispatchEvent(new Event('input'));
                }
            });
        });

        document.querySelectorAll('.cart-qty-input').forEach(function(input) {
            input.addEventListener('input', function() {
                let qty = parseInt(input.value);
                if (isNaN(qty) || qty < 1) qty = 1;
                input.value = qty;
                const menuId = input.dataset.menuId;
                const price = parseInt(input.closest('tr').querySelector('td:nth-child(2)').textContent.replace(/[^\d]/g, ''));
                const subtotal = qty * price;
                input.closest('tr').querySelector('.cart-subtotal').textContent = formatRupiah(subtotal);
                updateCartTotal();
            });
        });

        function updateCartTotal() {
            let total = 0;
            document.querySelectorAll('.cart-subtotal').forEach(function(td) {
                total += parseInt(td.textContent.replace(/[^\d]/g, ''));
            });
            document.getElementById('cart-total').textContent = formatRupiah(total);
        }
    </script>
@endpush