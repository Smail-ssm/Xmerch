@extends('layouts.admin')

@section('content')
<div class="content-area">
    <div class="mr-breadcrumb">
        <div class="row">
            <div class="col-lg-12">
                <h4 class="heading"><i class="fas fa-industry"></i> {{ __('Manufacturing Order') }} #{{ $order->order_number }}
                    <a class="add-btn" href="{{ route('admin-manufacturing-queue') }}">
                        <i class="fas fa-arrow-left"></i> {{ __('Back to Queue') }}
                    </a>
                </h4>
            </div>
        </div>
    </div>

    @include('alerts.admin.form-both')

    <div class="row">
        {{-- Order Details --}}
        <div class="col-lg-8">
            <div class="card mb-4">
                <div class="card-header bg-warning text-dark">
                    <h5 class="mb-0"><i class="fas fa-box"></i> {{ __('Products to Manufacture') }}</h5>
                </div>
                <div class="card-body">
                    @foreach($order->product_details as $item)
                    <div class="border rounded p-3 mb-3">
                        <div class="row">
                            <div class="col-md-2">
                                @if($item['product']->photo)
                                <img src="{{ asset('assets/images/products/' . $item['product']->photo) }}" 
                                     class="img-fluid rounded" alt="{{ $item['product']->name }}">
                                @else
                                <div class="bg-light text-center py-4 rounded">
                                    <i class="fas fa-image fa-2x text-muted"></i>
                                </div>
                                @endif
                            </div>
                            <div class="col-md-10">
                                <h5 class="mb-2">{{ $item['product']->name }}</h5>
                                <div class="row">
                                    <div class="col-md-3">
                                        <label class="text-muted mb-0">{{ __('Quantity') }}</label>
                                        <h4 class="text-primary mb-0">{{ $item['qty'] }}</h4>
                                    </div>
                                    <div class="col-md-3">
                                        <label class="text-muted mb-0">{{ __('Size') }}</label>
                                        <h4 class="mb-0">
                                            @if($item['size'])
                                            <span class="badge badge-info" style="font-size: 16px;">{{ $item['size'] }}</span>
                                            @else
                                            <span class="text-muted">-</span>
                                            @endif
                                        </h4>
                                    </div>
                                    <div class="col-md-3">
                                        <label class="text-muted mb-0">{{ __('Color') }}</label>
                                        <h4 class="mb-0">
                                            @if($item['color'])
                                            <span class="badge" style="background: {{ $item['color'] }}; color: {{ in_array(strtolower($item['color']), ['white', 'yellow', 'beige', 'cream']) ? '#000' : '#fff' }}; font-size: 16px;">
                                                {{ $item['color'] }}
                                            </span>
                                            @else
                                            <span class="text-muted">-</span>
                                            @endif
                                        </h4>
                                    </div>
                                    <div class="col-md-3">
                                        <label class="text-muted mb-0">{{ __('Price') }}</label>
                                        <h4 class="mb-0">{{ $order->currency_sign }}{{ number_format($item['price'], 2) }}</h4>
                                    </div>
                                </div>

                                {{-- Print File Download --}}
                                @if($item['product']->print_file)
                                <div class="mt-3">
                                    <a href="{{ asset('assets/files/designs/' . $item['product']->print_file) }}" 
                                       class="btn btn-primary" download>
                                        <i class="fas fa-download"></i> {{ __('Download Print File') }}
                                    </a>
                                </div>
                                @else
                                <div class="mt-3 alert alert-warning mb-0">
                                    <i class="fas fa-exclamation-triangle"></i> {{ __('No print file available for this product') }}
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            {{-- Order Notes --}}
            @if($order->order_note)
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-sticky-note"></i> {{ __('Order Notes') }}</h5>
                </div>
                <div class="card-body">
                    <p class="mb-0">{{ $order->order_note }}</p>
                </div>
            </div>
            @endif
        </div>

        {{-- Sidebar --}}
        <div class="col-lg-4">
            {{-- Order Status --}}
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-info-circle"></i> {{ __('Order Status') }}</h5>
                </div>
                <div class="card-body">
                    <table class="table table-borderless mb-0">
                        <tr>
                            <th>{{ __('Status') }}</th>
                            <td>
                                <span class="badge badge-warning">{{ __('In Manufacturing') }}</span>
                            </td>
                        </tr>
                        <tr>
                            <th>{{ __('Order Date') }}</th>
                            <td>{{ $order->created_at->format('M d, Y H:i') }}</td>
                        </tr>
                        <tr>
                            <th>{{ __('Total Items') }}</th>
                            <td>{{ $order->totalQty }}</td>
                        </tr>
                        <tr>
                            <th>{{ __('Order Total') }}</th>
                            <td><strong>{{ $order->currency_sign }}{{ number_format($order->pay_amount, 2) }}</strong></td>
                        </tr>
                    </table>
                </div>
            </div>

            {{-- Customer Info --}}
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-user"></i> {{ __('Customer Information') }}</h5>
                </div>
                <div class="card-body">
                    <p class="mb-1"><strong>{{ $order->customer_name }}</strong></p>
                    <p class="mb-1"><i class="fas fa-envelope"></i> {{ $order->customer_email }}</p>
                    <p class="mb-0"><i class="fas fa-phone"></i> {{ $order->customer_phone }}</p>
                </div>
            </div>

            {{-- Shipping Info --}}
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-shipping-fast"></i> {{ __('Shipping Address') }}</h5>
                </div>
                <div class="card-body">
                    <p class="mb-1">{{ $order->shipping_name ?? $order->customer_name }}</p>
                    <p class="mb-1">{{ $order->shipping_address ?? $order->customer_address }}</p>
                    <p class="mb-1">{{ $order->shipping_city ?? $order->customer_city }}, {{ $order->shipping_state ?? $order->customer_state }}</p>
                    <p class="mb-1">{{ $order->shipping_zip ?? $order->customer_zip }}</p>
                    <p class="mb-0">{{ $order->shipping_country ?? $order->customer_country }}</p>
                </div>
            </div>

            {{-- Action --}}
            <div class="card">
                <div class="card-body">
                    <a href="{{ route('admin-manufacturing-mark-ready', $order->id) }}" 
                       class="btn btn-success btn-block btn-lg" 
                       onclick="return confirm('{{ __('Mark this order as Print Ready and send to printer queue?') }}')">
                        <i class="fas fa-check-circle"></i> {{ __('Mark as Print Ready') }}
                    </a>
                    <small class="text-muted d-block text-center mt-2">
                        {{ __('This will send the order to the Printer Queue') }}
                    </small>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
