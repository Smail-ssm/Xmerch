@extends('layouts.admin')

@section('styles')
<style>
.stat-card {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border-radius: 16px;
    padding: 25px;
    color: #fff;
    margin-bottom: 20px;
    box-shadow: 0 10px 30px rgba(0,0,0,0.15);
}
.stat-card.eligible { background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); }
.stat-card.pending { background: linear-gradient(135deg, #6c757d 0%, #495057 100%); }
.stat-card.printing { background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%); }
.stat-card.printed { background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%); }
.stat-card.shipped { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); }

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

.queue-table {
    background: #fff;
    border-radius: 12px;
    box-shadow: 0 5px 20px rgba(0,0,0,0.08);
    overflow: hidden;
}
.queue-table th {
    background: #f8f9fa;
    font-weight: 600;
    padding: 15px;
    border: none;
}
.queue-table td {
    padding: 15px;
    vertical-align: middle;
}
.queue-table tr:hover {
    background: #f8f9fa;
}

.status-badge {
    padding: 6px 12px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 600;
}
.status-badge.pending { background: #fff3cd; color: #856404; }
.status-badge.printing { background: #cce5ff; color: #004085; }
.status-badge.printed { background: #d4edda; color: #155724; }
.status-badge.shipped { background: #d1ecf1; color: #0c5460; }

.action-btn {
    padding: 8px 16px;
    border-radius: 6px;
    font-size: 13px;
    border: none;
    cursor: pointer;
    transition: all 0.2s;
}
.action-btn.start { background: #4facfe; color: #fff; }
.action-btn.finish { background: #11998e; color: #fff; }
.action-btn.view { background: #6c757d; color: #fff; }
.action-btn:hover { transform: translateY(-2px); box-shadow: 0 5px 15px rgba(0,0,0,0.2); }
</style>
@endsection

@section('content')
<div class="content-area">
    <div class="mr-breadcrumb">
        <div class="row">
            <div class="col-lg-12">
                <h4 class="heading"><i class="fas fa-print"></i> {{ __('Print Production Dashboard') }}</h4>
                <ul class="links">
                    <li><a href="{{ route('admin.dashboard') }}">{{ __('Dashboard') }}</a></li>
                    <li><a href="#">{{ __('Printer') }}</a></li>
                </ul>
            </div>
        </div>
    </div>

    {{-- Stats Cards --}}
    <div class="row">
        <div class="col-lg-3 col-md-6">
            <div class="stat-card pending" style="position:relative;">
                <i class="fas fa-clock stat-icon"></i>
                <div class="stat-number">{{ $stats['pending'] }}</div>
                <div class="stat-label">{{ __('Total Pending') }}</div>
                <a href="{{ route('admin-printer-queue') }}">View All →</a>
            </div>
        </div>
        <div class="col-lg-3 col-md-6">
            <div class="stat-card eligible" style="position:relative;">
                <i class="fas fa-rocket stat-icon"></i>
                <div class="stat-number">{{ $stats['eligible'] }}</div>
                <div class="stat-label">{{ __('Ready for Production') }}</div>
                <small class="text-white-50">Cap reached</small>
            </div>
        </div>
        <div class="col-lg-3 col-md-6">
            <div class="stat-card printing" style="position:relative;">
                <i class="fas fa-print stat-icon"></i>
                <div class="stat-number">{{ $stats['printing'] }}</div>
                <div class="stat-label">{{ __('Currently Printing') }}</div>
                <a href="{{ route('admin-printer-printing') }}">View →</a>
            </div>
        </div>
        <div class="col-lg-3 col-md-6">
            <div class="stat-card printed" style="position:relative;">
                <i class="fas fa-check-circle stat-icon"></i>
                <div class="stat-number">{{ $stats['printed'] }}</div>
                <div class="stat-label">{{ __('Ready to Ship') }}</div>
                <a href="{{ route('admin-printer-ready') }}">View →</a>
            </div>
        </div>
        <div class="col-lg-3 col-md-6">
            <div class="stat-card shipped" style="position:relative;">
                <i class="fas fa-shipping-fast stat-icon"></i>
                <div class="stat-number">{{ $stats['shipped_today'] }}</div>
                <div class="stat-label">{{ __('Shipped Today') }}</div>
                <a href="{{ route('admin-printer-shipped') }}">View All →</a>
            </div>
        </div>
    </div>

    @if(Auth::guard('admin')->user()->IsSuper() || Auth::guard('admin')->user()->sectionCheck('manage_staffs'))
    <div class="row">
        <div class="col-lg-12">
            <div class="alert alert-info d-flex justify-content-between align-items-center" style="border-radius:12px; background: #fff; border: 1px solid #eef2ff; box-shadow: 0 4px 15px rgba(0,0,0,0.05); color: #333;">
                <div class="d-flex align-items-center">
                    <div style="width: 50px; height: 50px; background: #eef2ff; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin-right: 15px;">
                        <i class="fas fa-users-cog text-primary"></i>
                    </div>
                    <div>
                        <h6 class="mb-0">Production Team Selection</h6>
                        <small class="text-muted">You can manage printer and manufacturer accounts here.</small>
                    </div>
                </div>
                <a href="{{ route('admin-printer-accounts') }}" class="btn btn-primary btn-sm" style="border-radius: 8px;">
                    <i class="fas fa-user-plus"></i> Manage Accounts
                </a>
            </div>
        </div>
    </div>
    @endif

    {{-- Recent Queue --}}
    <div class="row mt-4">
        <div class="col-lg-12">
            <div class="product-description">
                <div class="body-area">
                    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:20px;">
                        <h5 style="margin:0;"><i class="fas fa-list"></i> {{ __('Print Queue') }}</h5>
                        <a href="{{ route('admin-printer-queue') }}" class="btn btn-primary btn-sm">
                            View All <i class="fas fa-arrow-right"></i>
                        </a>
                    </div>
                    
                    @if($recentOrders->count() > 0)
                    <div class="table-responsive">
                        <table class="table queue-table">
                            <thead>
                                <tr>
                                    <th>{{ __('Order') }}</th>
                                    <th>{{ __('Customer') }}</th>
                                    <th>{{ __('Items') }}</th>
                                    <th>{{ __('Status') }}</th>
                                    <th>{{ __('Date') }}</th>
                                    <th>{{ __('Actions') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($recentOrders as $order)
                                @php
                                    $cart = json_decode($order->cart, true);
                                    $itemCount = isset($cart['items']) ? count($cart['items']) : 0;
                                    $isEligible = $order->isEligibleForProduction();
                                @endphp
                                <tr class="{{ $isEligible ? 'table-success' : '' }}">
                                    <td>
                                        <strong>#{{ $order->order_number }}</strong>
                                        @if($isEligible && in_array($order->print_status, ['print_ready', 'pending_print']))
                                            <span class="badge badge-success"><i class="fas fa-rocket"></i></span>
                                        @endif
                                    </td>
                                    <td>{{ $order->customer_name }}</td>
                                    <td>{{ $itemCount }} item(s)</td>
                                    <td>
                                        <span class="status-badge {{ str_replace('_', '-', $order->print_status) }}">
                                            {{ $order->print_status_label }}
                                        </span>
                                    </td>
                                    <td>{{ $order->created_at->format('M d, H:i') }}</td>
                                    <td>
                                        <a href="{{ route('admin-printer-show', $order->id) }}" class="action-btn view">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        @if(in_array($order->print_status, ['print_ready', 'pending_print']))
                                        <a href="{{ route('admin-printer-start', $order->id) }}" class="action-btn start">
                                            <i class="fas fa-play"></i> Start
                                        </a>
                                        @elseif($order->print_status == 'printing')
                                        <a href="{{ route('admin-printer-printed', $order->id) }}" class="action-btn finish">
                                            <i class="fas fa-check"></i> Done
                                        </a>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @else
                    <div class="text-center py-5">
                        <i class="fas fa-inbox" style="font-size:48px;color:#ddd;"></i>
                        <p class="text-muted mt-3">{{ __('No orders in print queue') }}</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
