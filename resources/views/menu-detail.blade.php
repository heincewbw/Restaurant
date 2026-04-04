<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Menu Detail - Restaurant</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h3>{{ $menu->name }}</h3>
                    </div>
                    <div class="card-body">
                        <p><strong>Harga:</strong> Rp {{ number_format($menu->price, 0, ',', '.') }}</p>
                        <p><strong>Deskripsi:</strong> {{ $menu->description }}</p>
                        <p><strong>Stok:</strong> {{ $menu->stock }}</p>
                        <form action="{{ route('cart.add') }}" method="POST">
                            @csrf
                            <input type="hidden" name="menu_id" value="{{ $menu->id }}">
                            <div class="mb-3">
                                <label for="qty" class="form-label">Jumlah</label>
                                <input type="number" class="form-control" id="qty" name="qty" value="1" min="1" max="{{ $menu->stock }}">
                            </div>
                            <button type="submit" class="btn btn-success">Tambah ke Keranjang</button>
                        </form>
                        <div class="mt-3">
                            <a href="{{ route('scan.submit') }}" class="btn btn-secondary">Scan Lagi</a>
                            <a href="{{ route('cart') }}" class="btn btn-primary">Lihat Keranjang</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>