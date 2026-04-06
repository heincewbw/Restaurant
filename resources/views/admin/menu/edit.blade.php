<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Menu</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container mt-5">
        <h2>Edit Menu</h2>

        @if($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('admin.menu.update', $menu->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label for="name" class="form-label">Nama</label>
                <input type="text" name="name" id="name" class="form-control" value="{{ old('name', $menu->name) }}" required>
            </div>
            <div class="mb-3">
                <label for="price" class="form-label">Harga</label>
                <input type="number" name="price" id="price" class="form-control" value="{{ old('price', $menu->price) }}" required>
            </div>
            <div class="mb-3">
                <label for="group" class="form-label">Grup</label>
                <select name="group" id="group" class="form-select" required>
                    <option value="Makanan" {{ old('group', $menu->group) == 'Makanan' ? 'selected' : '' }}>Makanan</option>
                    <option value="Minuman" {{ old('group', $menu->group) == 'Minuman' ? 'selected' : '' }}>Minuman</option>
                    <option value="Sup" {{ old('group', $menu->group) == 'Sup' ? 'selected' : '' }}>Sup</option>
                </select>
            </div>
            <div class="mb-3">
                <label for="barcode" class="form-label">Barcode</label>
                <input type="text" name="barcode" id="barcode" class="form-control" value="{{ old('barcode', $menu->barcode) }}" required>
            </div>
            <div class="mb-3">
                <label for="image" class="form-label">URL Gambar</label>
                <input type="url" name="image" id="image" class="form-control" value="{{ old('image', $menu->image) }}">
            </div>
            <div class="mb-3">
                <label for="description" class="form-label">Deskripsi</label>
                <textarea name="description" id="description" class="form-control">{{ old('description', $menu->description) }}</textarea>
            </div>
            <button type="submit" class="btn btn-primary">Perbarui</button>
            <a href="{{ route('admin.menu.index') }}" class="btn btn-secondary">Batal</a>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>