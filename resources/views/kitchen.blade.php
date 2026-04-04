@extends('layouts.app')
@section('content')
<div class="container">
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-primary text-white">
                    <h3 class="mb-0">Kitchen Orders</h3>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif
                    @if($orders->isEmpty())
                        <p>Tidak ada order masuk.</p>
                    @else
                        @foreach($orders as $order)
                            <div class="card mb-3 border-0 shadow-sm">
                                <div class="card-header bg-light">
                                    <strong>Order #{{ $order->id }}</strong> - <span class="text-primary">Meja: {{ $order->table_code ?? '-' }}</span> - Status: <span class="text-info">{{ $order->status }}</span> - Total: <span class="text-success">Rp {{ number_format($order->total, 0, ',', '.') }}</span>
                                </div>
                                <div class="card-body">
                                    <ul class="list-group">
                                        @foreach($order->items as $item)
                                            <li class="list-group-item">
                                                {{ $item->menu->name }} - {{ $item->quantity }} x Rp {{ number_format($item->price, 0, ',', '.') }}
                                            </li>
                                        @endforeach
                                    </ul>
                                    <div class="mt-3">
                                        @if($order->status == 'paid')
                                            <form action="{{ route('kitchen.start', $order) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('POST')
                                                <button type="submit" class="btn btn-warning">Mulai Masak</button>
                                            </form>
                                        @elseif($order->status == 'cooking')
                                            <form action="{{ route('kitchen.done', $order) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('POST')
                                                <button type="submit" class="btn btn-success">Selesai</button>
                                            </form>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection