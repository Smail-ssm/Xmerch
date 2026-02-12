@extends('layouts.admin')

@section('content')
<div class="content-area">
    <div class="mr-breadcrumb">
        <div class="row">
            <div class="col-lg-12">
                <h4 class="heading"><i class="fas fa-chart-bar"></i> {{ __('Capacity Planning') }}</h4>
                <ul class="links">
                    <li><a href="{{ route('admin.dashboard') }}">{{ __('Dashboard') }}</a></li>
                    <li><a href="{{ route('admin-manufacturing-dashboard') }}">{{ __('Manufacturing') }}</a></li>
                    <li><a href="#">{{ __('Capacity') }}</a></li>
                </ul>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="product-description">
                <div class="body-area">
                    <h5><i class="fas fa-cog"></i> {{ __('Product Capacity Management') }}</h5>
                    <p class="text-muted">{{ __('Set daily production limits for each product') }}</p>
                    
                    <div class="table-responsive mt-4">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>{{ __('Product') }}</th>
                                    <th>{{ __('Daily Capacity') }}</th>
                                    <th>{{ __('Today\'s Orders') }}</th>
                                    <th>{{ __('Remaining') }}</th>
                                    <th>{{ __('Utilization') }}</th>
                                    <th>{{ __('Actions') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($podProducts as $product)
                                <tr>
                                    <td><strong>{{ $product->name }}</strong></td>
                                    <td>{{ $product->production_cap }} units/day</td>
                                    <td>{{ $product->daily_orders }}</td>
                                    <td>{{ $product->remaining_capacity }}</td>
                                    <td>
                                        <div class="progress" style="height: 20px;">
                                            <div class="progress-bar {{ $product->utilization_percentage >= 100 ? 'bg-danger' : ($product->utilization_percentage >= 80 ? 'bg-warning' : 'bg-success') }}" 
                                                 style="width: {{ min(100, $product->utilization_percentage) }}%;">
                                                {{ $product->utilization_percentage }}%
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <a href="{{ route('admin-prod-edit', $product->id) }}" class="btn btn-sm btn-primary">
                                            <i class="fas fa-edit"></i> Adjust
                                        </a>
                                    </td>
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
