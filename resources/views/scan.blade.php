<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Scan Barcode - Restaurant</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h3>Pilih Meja (Scan/Isi Kode Meja)</h3>
                    </div>
                    <div class="card-body">
                        @if(session('error'))
                            <div class="alert alert-danger">{{ session('error') }}</div>
                        @endif
                        @if(session('success'))
                            <div class="alert alert-success">{{ session('success') }}</div>
                        @endif
                        <form action="{{ route('scan.submit') }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label for="table_code" class="form-label">Kode Meja</label>
                                <input type="text" class="form-control" id="table_code" name="table_code" required autofocus placeholder="Contoh: MEJA-1">
                            </div>
                            <button type="submit" class="btn btn-primary">Lanjut</button>
                        </form>
                        <div class="mt-3">
                            <a href="{{ route('cart') }}" class="btn btn-secondary">Lihat Keranjang</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>