@extends('layouts.admin')

@section('styles')
<style>
.stat-card {
    background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
    border-radius: 16px;
    padding: 25px;
    color: #fff;
    margin-bottom: 20px;
    box-shadow: 0 10px 30px rgba(0,0,0,0.15);
}
.stat-card.capacity { background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%); }
.stat-card.production { background: linear-gradient(135deg, #06beb6 0%, #48b1bf 100%); }
.stat-card.quality { background: linear-gradient(135deg, #56ab2f 0%, #a8e063 100%); }
.stat-card.alert { background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); }

.stat-card .stat-icon {
    font-size: 48px;
    opacity: 0.3;
    position: absolute;
    right: 20px;
    top: 20px;
}
.stat-card .stat-number {
    font-size: 48px;
    font-weight: bold;
    line-height: 1;
}
.stat-card .stat-label {
    font-size: 14px;
    opacity: 0.9;
    margin-top: 5px;
}
.stat-card a {
    color: rgba(255,255,255,0.8);
    font-size: 13px;
    text-decoration: underline;
}
.stat-card a:hover { color: #fff; }

.progress-bar-custom {
    height: 8px;
    background: rgba(255,255,255,0.3);
    border-radius: 10px;
    overflow: hidden;
    margin-top: 10px;
}
.progress-bar-custom .fill {
    height: 100%;
    background: #fff;
    border-radius: 10px;
    transition: width 0.3s;
}

.product-capacity-card {
    background: #fff;
    border-radius: 12px;
    padding: 15px;
    margin-bottom: 10px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.05);
    transition: all 0.2s;
}
.product-capacity-card:hover {
    box-shadow: 0 5px 20px rgba(0,0,0,0.1);
    transform: translateY(-2px);
}
.product-capacity-card.at-capacity {
    border-left: 4px solid #f5576c;
}
.product-capacity-card.near-capacity {
    border-left: 4px solid #ffc107;
}
.product-capacity-card.good {
    border-left: 4px solid #38ef7d;
}
</style>
@endsection

@section('content')
<div class="content-area">
    <div class="mr-breadcrumb">
        <div class="row">
            <div class="col-lg-12">
                <h4 class="heading"><i class="fas fa-industry"></i> {{ __('Manufacturing Dashboard') }}</h4>
                <ul class="links">
                    <li><a href="{{ route('admin.dashboard') }}">{{ __('Dashboard') }}</a></li>
                    <li><a href="#">{{ __('Manufacturing') }}</a></li>
                </ul>
            </div>
        </div>
    </div>

    {{-- Main Stats --}}
    <div class="row">
        <div class="col-lg-3 col-md-6">
            <div class="stat-card capacity" style="position:relative;">
                <i class="fas fa-chart-pie stat-icon"></i>
                <div class="stat-number">{{ $stats['capacity_percentage'] }}%</div>
                <div class="stat-label">{{ __('Capacity Used') }}</div>
                <small>{{ $stats['capacity_used'] }} / {{ $stats['total_capacity'] }} units</small>
                <div class="progress-bar-custom">
                    <div class="fill" style="width: {{ $stats['capacity_percentage'] }}%;"></div>
                </div>
                <a href="{{ route('admin-manufacturing-capacity') }}">Manage Capacity →</a>
            </div>
        </div>
        <div class="col-lg-3 col-md-6">
            <div class="stat-card production" style="position:relative;">
                <i class="fas fa-tachometer-alt stat-icon"></i>
                <div class="stat-number">{{ $stats['production_rate'] }}</div>
                <div class="stat-label">{{ __('Units/Hour') }}</div>
                <small>{{ __('Production Rate (24h)') }}</small>
                <a href="{{ route('admin-manufacturing-analytics') }}">View Analytics →</a>
            </div>
        </div>
        <div class="col-lg-3 col-md-6">
            <div class="stat-card quality" style="position:relative;">
                <i class="fas fa-check-circle stat-icon"></i>
                <div class="stat-number">{{ $stats['quality_score'] }}%</div>
                <div class="stat-label">{{ __('Quality Score') }}</div>
                <small>{{ __('Success Rate') }}</small>
                <a href="{{ route('admin-manufacturing-quality') }}">Quality Control →</a>
            </div>
        </div>
        <div class="col-lg-3 col-md-6">
            <div class="stat-card alert" style="position:relative;">
                <i class="fas fa-exclamation-triangle stat-icon"></i>
                <div class="stat-number">{{ $stats['products_at_capacity'] }}</div>
                <div class="stat-label">{{ __('At Capacity') }}</div>
                <small>{{ __('Products maxed out') }}</small>
                <a href="{{ route('admin-manufacturing-capacity') }}">Adjust Limits →</a>
            </div>
        </div>
    </div>

    {{-- Production Summary --}}
    <div class="row mt-4">
        <div class="col-lg-8">
            <div class="product-description">
                <div class="body-area">
                    <h5 style="margin-bottom:20px;"><i class="fas fa-boxes"></i> {{ __('Product Capacity Status') }}</h5>
                    
                    @if($podProducts->count() > 0)
                        @foreach($podProducts->take(10) as $product)
                        @php
                            $today = today();
                            $dailyOrders = 0;
                            $allOrders = \App\Models\Order::where('status', 'processing')
                                ->whereDate('created_at', $today)
                                ->get();
                            
                            foreach ($allOrders as $order) {
                                $cart = json_decode($order->cart, true);
                                if (isset($cart['items'])) {
                                    foreach ($cart['items'] as $item) {
                                        if ($item['item']['id'] == $product->id) {
                                            $dailyOrders++;
                                            break;
                                        }
                                    }
                                }
                            }
                            
                            $percentage = $product->production_cap > 0 ? ($dailyOrders / $product->production_cap) * 100 : 0;
                            $cardClass = $percentage >= 100 ? 'at-capacity' : ($percentage >= 80 ? 'near-capacity' : 'good');
                        @endphp
                        <div class="product-capacity-card {{ $cardClass }}">
                            <div class="d-flex justify-content-between align-items-center">
                                <div style="flex:1;">
                                    <strong>{{ $product->name }}</strong>
                                    <div class="progress-bar-custom" style="margin-top:8px;">
                                        <div class="fill" style="width: {{ min(100, $percentage) }}%; background: {{ $percentage >= 100 ? '#f5576c' : ($percentage >= 80 ? '#ffc107' : '#38ef7d') }};"></div>
                                    </div>
                                </div>
                                <div style="text-align:right; margin-left:20px;">
                                    <div style="font-size:24px; font-weight:bold; color: {{ $percentage >= 100 ? '#f5576c' : ($percentage >= 80 ? '#ffc107' : '#11998e') }};">
                                        {{ $dailyOrders }} / {{ $product->production_cap }}
                                    </div>
                                    <small class="text-muted">{{ round($percentage) }}% used</small>
                                </div>
                            </div>
                        </div>
                        @endforeach
                        
                        <div class="text-center mt-3">
                            <a href="{{ route('admin-manufacturing-capacity') }}" class="btn btn-primary">
                                <i class="fas fa-cog"></i> Manage All Capacities
                            </a>
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-box-open" style="font-size:48px;color:#ddd;"></i>
                            <p class="text-muted mt-3">{{ __('No POD products configured') }}</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="product-description">
                <div class="body-area">
                    <h5 style="margin-bottom:20px;"><i class="fas fa-clipboard-list"></i> {{ __('Today\'s Summary') }}</h5>
                    
                    <div style="padding: 15px; background: #f8f9fa; border-radius: 8px; margin-bottom: 10px;">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span><i class="fas fa-clock text-warning"></i> Pending</span>
                            <strong>{{ $stats['pending_orders'] }}</strong>
                        </div>
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span><i class="fas fa-print text-info"></i> Printing</span>
                            <strong>{{ $stats['printing_orders'] }}</strong>
                        </div>
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span><i class="fas fa-check text-success"></i> Printed</span>
                            <strong>{{ $stats['printed_today'] }}</strong>
                        </div>
                        <div class="d-flex justify-content-between align-items-center">
                            <span><i class="fas fa-shipping-fast text-primary"></i> Shipped</span>
                            <strong>{{ $stats['shipped_today'] }}</strong>
                        </div>
                    </div>

                    <a href="{{ route('admin-manufacturing-queue') }}" class="btn btn-outline-primary btn-block">
                        <i class="fas fa-eye"></i> Monitor Print Queue
                    </a>
                    
                    <a href="{{ route('admin-manufacturing-schedule') }}" class="btn btn-outline-secondary btn-block mt-2">
                        <i class="fas fa-calendar"></i> Production Schedule
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
