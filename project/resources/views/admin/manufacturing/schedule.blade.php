@extends('layouts.admin')

@section('content')
<div class="content-area">
    <div class="mr-breadcrumb">
        <div class="row">
            <div class="col-lg-12">
                <h4 class="heading"><i class="fas fa-calendar-alt"></i> {{ __('Production Schedule') }}</h4>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="product-description">
                <div class="body-area">
                    <h5>{{ __('Upcoming Production') }}</h5>
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>{{ __('Order') }}</th>
                                    <th>{{ __('Customer') }}</th>
                                    <th>{{ __('Date') }}</th>
                                   <th>{{ __('Status') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($upcomingOrders as $order)
                                <tr>
                                    <td>#{{ $order->order_number }}</td>
                                    <td>{{ $order->customer_name }}</td>
                                    <td>{{ $order->created_at->format('Y-m-d H:i') }}</td>
                                    <td><span class="badge badge-warning">{{ $order->print_status_label }}</span></td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
