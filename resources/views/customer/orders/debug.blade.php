@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row">
        <div class="col-md-8 mx-auto">
            <div class="card">
                <div class="card-header">
                    <h4>Debug Cancellation</h4>
                </div>
                <div class="card-body">
                    <h5>Orders:</h5>
                    @foreach($orders as $order)
                    <div class="border p-3 mb-3">
                        <p><strong>Order ID:</strong> {{ $order->id }}</p>
                        <p><strong>Status:</strong> {{ $order->status }}</p>
                        <p><strong>User ID:</strong> {{ $order->user_id }}</p>
                        <p><strong>Cancellation Requested:</strong> {{ $order->cancellation_requested ? 'Yes' : 'No' }}</p>
                        <form method="POST" action="{{ route('orders.requestCancellation', $order) }}" style="display: inline;">
                            @csrf
                            <button type="submit" class="btn btn-danger"
                                    onclick="return confirm('Are you sure you want to request cancellation for this order?')">
                                Test Cancellation
                            </button>
                        </form>
                    </div>
                    @endforeach
                </div>
            </div>
                </div>
    </div>
</div>


@endsection
