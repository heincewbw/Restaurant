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
                                <div class="form-check mb-2">
                                    <input class="form-check-input menu-checkbox" type="checkbox" value="{{ $menu->id }}" id="menu-checkbox-{{ $menu->id }}" data-price="{{ $menu->price }}">
                                    <label class="form-check-label" for="menu-checkbox-{{ $menu->id }}">Pilih menu</label>
                                </div>
                                <div class="d-flex align-items-center gap-2">
                                    <label for="qty-{{ $menu->id }}" class="mb-0">Qty</label>
                                    <input type="number" class="form-control qty-input" id="qty-{{ $menu->id }}" name="qty_{{ $menu->id }}" value="1" min="1" disabled style="width: 100px;">
                                </div>
                                <input type="hidden" name="items[{{ $menu->id }}][menu_id]" value="{{ $menu->id }}" disabled>
                                <input type="hidden" name="items[{{ $menu->id }}][qty]" class="hidden-qty" value="1" disabled>
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
<script>
    const checkboxes = document.querySelectorAll('.menu-checkbox');
    const totalEl = document.getElementById('selected-total');
    const addSelectedButton = document.getElementById('add-selected');

    function formatRupiah(value) {
        return 'Rp ' + value.toLocaleString('id-ID', { minimumFractionDigits: 0, maximumFractionDigits: 0 });
    }

    function updateTotal() {
        let total = 0;
        let selectedCount = 0;

        checkboxes.forEach(checkbox => {
            const menuId = checkbox.value;
            const price = Number(checkbox.dataset.price);
            const qtyInput = document.getElementById('qty-' + menuId);
            const hiddenQty = document.querySelector('input[name="items[' + menuId + '][qty]"]');

            if (checkbox.checked) {
                selectedCount++;
                const qty = Number(qtyInput.value) || 1;
                total += qty * price;
                hiddenQty.value = qty;
            }
        });

        totalEl.textContent = formatRupiah(total);
        addSelectedButton.disabled = selectedCount === 0;
    }

    checkboxes.forEach(checkbox => {
        checkbox.addEventListener('change', (event) => {
            const menuId = event.target.value;
            const qtyInput = document.getElementById('qty-' + menuId);
            const hiddenQty = document.querySelector('input[name="items[' + menuId + '][qty]"]');
            const hiddenMenuIdInput = document.querySelector('input[name="items[' + menuId + '][menu_id]"]');

            if (event.target.checked) {
                qtyInput.disabled = false;
                hiddenQty.disabled = false;
                hiddenMenuIdInput.disabled = false;
            } else {
                qtyInput.disabled = true;
                hiddenQty.disabled = true;
                hiddenMenuIdInput.disabled = true;
            }

            updateTotal();
        });
    });

    const qtyInputs = document.querySelectorAll('.qty-input');
    qtyInputs.forEach(input => {
        input.addEventListener('input', () => {
            const menuId = input.id.replace('qty-', '');
            const hiddenQty = document.querySelector('input[name="items[' + menuId + '][qty]"]');
            const value = Number(input.value) > 0 ? Number(input.value) : 1;
            input.value = value;
            hiddenQty.value = value;
            updateTotal();
        });
    });

    window.addEventListener('DOMContentLoaded', updateTotal);
</script>
@endpush