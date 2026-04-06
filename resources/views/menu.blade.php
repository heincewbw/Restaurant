@extends('layouts.app')
@section('content')
<div class="container">
    <h2 class="mb-4">Menu Restoran - Meja: {{ $tableCode }}</h2>
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    <a href="{{ route('cart') }}" class="btn btn-primary mb-3">Lihat Keranjang</a>
    <form id="bulk-menu-form" action="{{ route('cart.addMultiple') }}" method="POST">
        @csrf
        <div class="mb-3">
            <strong>Total terpilih:</strong> <span id="selected-total">Rp 0</span>
        </div>
        @foreach($menus as $group => $groupMenus)
            <h4 class="mt-4 mb-3 text-primary">{{ $group }}</h4>
            <div class="row">
                @foreach($groupMenus as $menu)
                    <div class="col-md-4 mb-4">
                        <div class="card h-100 border-0">
                            <div class="card-body d-flex flex-column align-items-center">
                                @if($menu->image)
                                    <img src="{{ $menu->image }}" alt="{{ $menu->name }}" class="img-fluid mb-3 rounded" style="height: 180px; object-fit: cover; width:100%;" loading="lazy">
                                @endif
                                <h5 class="card-title text-primary">{{ $menu->name }}</h5>
                                <p class="card-text text-muted">{{ $menu->description }}</p>
                                <p class="card-text"><strong>Harga:</strong> <span class="text-success">Rp {{ number_format($menu->price,0,',','.') }}</span></p>
                                <div class="d-flex align-items-center gap-2 mt-auto">
                                    <button type="button" class="btn btn-outline-secondary btn-sm qty-down" data-id="{{ $menu->id }}" data-price="{{ $menu->price }}"><i class="bi bi-dash"></i></button>
                                    <span class="fw-bold qty-display" id="qty-display-{{ $menu->id }}" style="min-width:28px; text-align:center;">0</span>
                                    <button type="button" class="btn btn-outline-secondary btn-sm qty-up" data-id="{{ $menu->id }}" data-price="{{ $menu->price }}"><i class="bi bi-plus"></i></button>
                                </div>
                                <input type="hidden" name="items[{{ $menu->id }}][menu_id]" value="{{ $menu->id }}" disabled>
                                <input type="hidden" name="items[{{ $menu->id }}][qty]" class="hidden-qty" value="0" disabled>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endforeach
        <button id="add-selected" type="submit" class="btn btn-success mt-3" disabled>Tambah ke Keranjang (terpilih)</button>
    </form>
</div>
@endsection
@push('scripts')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
<script>
    const totalEl = document.getElementById('selected-total');
    const addSelectedButton = document.getElementById('add-selected');

    function formatRupiah(value) {
        return 'Rp ' + value.toLocaleString('id-ID', { minimumFractionDigits: 0, maximumFractionDigits: 0 });
    }

    function updateTotal() {
        let total = 0;
        let selectedCount = 0;

        document.querySelectorAll('.qty-display').forEach(display => {
            const id = display.id.replace('qty-display-', '');
            const qty = Number(display.textContent);
            if (qty > 0) {
                selectedCount++;
                const btn = document.querySelector('.qty-up[data-id="' + id + '"]');
                const price = Number(btn.dataset.price);
                total += qty * price;
            }
        });

        totalEl.textContent = formatRupiah(total);
        addSelectedButton.disabled = selectedCount === 0;
    }

    function setQty(menuId, qty) {
        if (qty < 0) qty = 0;
        const display = document.getElementById('qty-display-' + menuId);
        const hiddenQty = document.querySelector('input[name="items[' + menuId + '][qty]"]');
        const hiddenMenuId = document.querySelector('input[name="items[' + menuId + '][menu_id]"]');
        const card = display.closest('.card');

        display.textContent = qty;
        hiddenQty.value = qty;

        if (qty > 0) {
            hiddenQty.disabled = false;
            hiddenMenuId.disabled = false;
            card.classList.add('border-success', 'shadow-sm');
            card.classList.remove('border-0');
        } else {
            hiddenQty.disabled = true;
            hiddenMenuId.disabled = true;
            card.classList.remove('border-success', 'shadow-sm');
            card.classList.add('border-0');
        }

        updateTotal();
    }

    document.querySelectorAll('.qty-up').forEach(btn => {
        btn.addEventListener('click', () => {
            const id = btn.dataset.id;
            const current = Number(document.getElementById('qty-display-' + id).textContent);
            setQty(id, current + 1);
        });
    });

    document.querySelectorAll('.qty-down').forEach(btn => {
        btn.addEventListener('click', () => {
            const id = btn.dataset.id;
            const current = Number(document.getElementById('qty-display-' + id).textContent);
            setQty(id, current - 1);
        });
    });

    window.addEventListener('DOMContentLoaded', updateTotal);
</script>
@endpush