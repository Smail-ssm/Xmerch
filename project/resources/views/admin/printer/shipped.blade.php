@extends('layouts.admin')

@section('content')
<div class="content-area">
    <div class="mr-breadcrumb">
        <div class="row">
            <div class="col-lg-12">
                <h4 class="heading"><i class="fas fa-shipping-fast"></i> {{ __('Shipped Orders') }}
                    <a class="add-btn" href="{{ route('admin-printer-dashboard') }}">
                        <i class="fas fa-arrow-left"></i> {{ __('Dashboard') }}
                    </a>
                </h4>
            </div>
        </div>
    </div>

    <div class="add-product-content">
        <div class="row">
            <div class="col-lg-12">
                <div class="product-description">
                    <div class="body-area">
                        
                        @if($orders->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>{{ __('Order') }}</th>
                                        <th>{{ __('Customer') }}</th>
                                        <th>{{ __('Destination') }}</th>
                                        <th>{{ __('Shipped') }}</th>
                                        <th>{{ __('Actions') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($orders as $order)
                                    <tr>
                                        <td><strong>#{{ $order->order_number }}</strong></td>
                                        <td>{{ $order->customer_name }}</td>
                                        <td>{{ $order->shipping_city ?? $order->customer_city }}, {{ $order->shipping_country ?? $order->customer_country }}</td>
                                        <td>
                                            @if($order->shipped_at)
                                            {{ $order->shipped_at->format('M d, Y H:i') }}
                                            @else
                                            -
                                            @endif
                                        </td>
                                        <td>
                                            <a href="{{ route('admin-printer-show', $order->id) }}" class="btn btn-sm btn-info">
                                                <i class="fas fa-eye"></i> View
                                            </a>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        {{ $orders->links() }}
                        @else
                        <div class="text-center py-5">
                            <i class="fas fa-truck" style="font-size:48px;color:#ddd;"></i>
                            <p class="text-muted mt-3">{{ __('No shipped orders yet') }}</p>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
