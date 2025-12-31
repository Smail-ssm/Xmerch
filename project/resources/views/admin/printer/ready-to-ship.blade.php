@extends('layouts.admin')

@section('content')
<div class="content-area">
    <div class="mr-breadcrumb">
        <div class="row">
            <div class="col-lg-12">
                <h4 class="heading"><i class="fas fa-box"></i> {{ __('Ready to Ship') }}
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
                        @include('alerts.admin.form-both')
                        
                        @if($orders->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>{{ __('Order') }}</th>
                                        <th>{{ __('Customer') }}</th>
                                        <th>{{ __('Shipping Address') }}</th>
                                        <th>{{ __('Printed') }}</th>
                                        <th>{{ __('Actions') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($orders as $order)
                                    <tr>
                                        <td><strong>#{{ $order->order_number }}</strong></td>
                                        <td>{{ $order->customer_name }}</td>
                                        <td>
                                            {{ $order->shipping_address ?? $order->customer_address }},
                                            {{ $order->shipping_city ?? $order->customer_city }},
                                            {{ $order->shipping_country ?? $order->customer_country }}
                                        </td>
                                        <td>{{ $order->printed_at ? $order->printed_at->diffForHumans() : '-' }}</td>
                                        <td>
                                            <div class="btn-group">
                                                <a href="{{ route('admin-printer-show', $order->id) }}" class="btn btn-sm btn-info" title="View Detail">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="{{ route('admin-printer-label', $order->id) }}" target="_blank" class="btn btn-sm btn-dark" title="Print Label">
                                                    <i class="fas fa-barcode"></i>
                                                </a>
                                                <form action="{{ route('admin-printer-shipped-action', $order->id) }}" method="POST" style="display:inline;">
                                                    @csrf
                                                    <button type="submit" class="btn btn-sm btn-primary" title="Mark Shipped">
                                                        <i class="fas fa-shipping-fast"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        {{ $orders->links() }}
                        @else
                        <div class="text-center py-5">
                            <i class="fas fa-box-open" style="font-size:48px;color:#ddd;"></i>
                            <p class="text-muted mt-3">{{ __('No orders ready to ship') }}</p>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
