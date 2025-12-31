@extends('layouts.admin')

@section('content')
<div class="content-area">
    <div class="mr-breadcrumb">
        <div class="row">
            <div class="col-lg-12">
                <h4 class="heading"><i class="fas fa-print"></i> {{ __('Currently Printing') }}
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
                                        <th>{{ __('Items') }}</th>
                                        <th>{{ __('Started') }}</th>
                                        <th>{{ __('Actions') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($orders as $order)
                                    @php
                                        $cart = json_decode($order->cart, true);
                                        $itemCount = isset($cart['items']) ? count($cart['items']) : 0;
                                    @endphp
                                    <tr>
                                        <td><strong>#{{ $order->order_number }}</strong></td>
                                        <td>{{ $order->customer_name }}</td>
                                        <td>{{ $itemCount }} item(s)</td>
                                        <td>{{ $order->updated_at->diffForHumans() }}</td>
                                        <td>
                                            <a href="{{ route('admin-printer-show', $order->id) }}" class="btn btn-sm btn-info">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('admin-printer-printed', $order->id) }}" class="btn btn-sm btn-success">
                                                <i class="fas fa-check"></i> Done
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
                            <i class="fas fa-print" style="font-size:48px;color:#ddd;"></i>
                            <p class="text-muted mt-3">{{ __('No orders currently printing') }}</p>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
