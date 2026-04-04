@extends('layouts.app')
@section('content')
<div class="container">
    <h2 class="mb-4">Admin - Daftar Menu</h2>
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    <div class="d-flex justify-content-between align-items-center mb-3">
        <a href="{{ route('admin.menu.create') }}" class="btn btn-primary">Tambah Menu</a>
        <form action="{{ route('logout.simple') }}" method="POST" style="display:inline-block;">
            @csrf
            <button type="submit" class="btn btn-outline-secondary">Logout</button>
        </form>
    </div>
    <table class="table table-bordered align-middle shadow-sm">
        <thead class="table-light">
            <tr>
                <th>ID</th>
                <th>Nama</th>
                <th>Harga</th>
                <th>Barcode</th>
                <th>Gambar</th>
                <th>Deskripsi</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($menus as $menu)
                <tr>
                    <td>{{ $menu->id }}</td>
                    <td>{{ $menu->name }}</td>
                    <td>Rp {{ number_format($menu->price,0,',','.') }}</td>
                    <td>{{ $menu->barcode }}</td>
                    <td>@if($menu->image)<img src="{{ $menu->image }}" alt="{{ $menu->name }}" width="120" class="rounded">@endif</td>
                    <td>{{ $menu->description }}</td>
                    <td>
                        <a href="{{ route('admin.menu.edit', $menu) }}" class="btn btn-warning btn-sm">Edit</a>
                        <form action="{{ route('admin.menu.destroy', $menu) }}" method="POST" style="display:inline-block;" onsubmit="return confirm('Yakin ingin hapus?');">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-danger btn-sm">Hapus</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection