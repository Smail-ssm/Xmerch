@extends('layouts.admin')

@section('styles')
<style>
.queue-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 20px;
}
.batch-actions {
    display: none;
    gap: 10px;
}
.batch-actions.show { display: flex; }

.order-card {
    background: #fff;
    border-radius: 12px;
    padding: 20px;
    margin-bottom: 15px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.05);
    border-left: 4px solid #f5576c;
    transition: all 0.3s;
}
.order-card:hover {
    box-shadow: 0 5px 20px rgba(0,0,0,0.1);
    transform: translateY(-2px);
}
.order-card.selected {
    border-left-color: #4facfe;
    background: #f0f7ff;
}

.order-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 15px;
}
.order-number {
    font-size: 18px;
    font-weight: bold;
    color: #333;
}
.order-date {
    color: #999;
    font-size: 13px;
}

.order-items {
    display: flex;
    gap: 15px;
    flex-wrap: wrap;
    margin-bottom: 15px;
}
.order-item {
    display: flex;
    gap: 10px;
    align-items: center;
    background: #f8f9fa;
    padding: 10px;
    border-radius: 8px;
    flex: 1;
    min-width: 200px;
}
.order-item img {
    width: 60px;
    height: 60px;
    object-fit: cover;
    border-radius: 6px;
}
.item-info h6 {
    margin: 0 0 5px;
    font-size: 14px;
}
.item-info p {
    margin: 0;
    font-size: 12px;
    color: #666;
}

.order-footer {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding-top: 15px;
    border-top: 1px solid #eee;
}
.customer-info {
    font-size: 13px;
    color: #666;
}
.customer-info i { margin-right: 5px; }

.action-btn {
    padding: 10px 20px;
    border-radius: 8px;
    border: none;
    cursor: pointer;
    font-weight: 600;
    transition: all 0.2s;
}
.action-btn.primary { background: #4facfe; color: #fff; }
.action-btn.success { background: #11998e; color: #fff; }
.action-btn.outline { background: transparent; border: 2px solid #ddd; color: #666; }
.action-btn:hover { transform: translateY(-2px); }
</style>
@endsection

@section('content')
<div class="content-area">
    <div class="mr-breadcrumb">
        <div class="row">
            <div class="col-lg-12">
                <h4 class="heading"><i class="fas fa-clock"></i> {{ __('Print Queue') }}
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
                        
                        <div class="queue-header">
                            <div>
                                <strong>{{ $orders->total() }}</strong> orders waiting to print
                            </div>
                            <div class="batch-actions" id="batch-actions">
                                <form action="{{ route('admin-printer-batch-start') }}" method="POST" style="display:inline;">
                                    @csrf
                                    <input type="hidden" name="order_ids" id="selected-ids">
                                    <button type="submit" class="action-btn primary">
                                        <i class="fas fa-play"></i> Start Selected
                                    </button>
                                </form>
                            </div>
                            <label style="cursor:pointer;">
                                <input type="checkbox" id="select-all"> Select All
                            </label>
                        </div>

                        @if($orders->count() > 0)
                            @foreach($orders as $order)
                            @php
                                $cart = json_decode($order->cart, true);
                                $items = $cart['items'] ?? [];
                                $isEligible = $order->isEligibleForProduction();
                            @endphp
                            <div class="order-card {{ $isEligible ? 'eligible' : '' }}" data-id="{{ $order->id }}" style="{{ $isEligible ? 'border-left-color: #38ef7d; background: #f0fff4;' : '' }}">
                                <div class="order-header">
                                    <div>
                                        <input type="checkbox" class="order-checkbox" value="{{ $order->id }}">
                                        <span class="order-number">#{{ $order->order_number }}</span>
                                        @if($isEligible)
                                            <span class="badge badge-success ml-2"><i class="fas fa-rocket"></i> READY</span>
                                        @endif
                                    </div>
                                    <div class="order-date">
                                        <i class="fas fa-clock"></i> {{ $order->created_at->diffForHumans() }}
                                    </div>
                                </div>

                                <div class="order-items">
                                    @foreach($items as $item)
                                    <div class="order-item">
                                        @php
                                            $photo = isset($item['item']['photo']) ? $item['item']['photo'] : 'placeholder.jpg';
                                        @endphp
                                        <img src="{{ asset('assets/images/products/' . $photo) }}" alt="">
                                        <div class="item-info">
                                            <h6>{{ $item['item']['name'] ?? 'Product' }}</h6>
                                            <p>Qty: {{ $item['qty'] ?? 1 }}</p>
                                            @if(isset($item['size']))
                                            <p>Size: {{ $item['size'] }}</p>
                                            @endif
                                            @if(isset($item['color']))
                                            <p>Color: {{ $item['color'] }}</p>
                                            @endif
                                        </div>
                                    </div>
                                    @endforeach
                                </div>

                                <div class="order-footer">
                                    <div class="customer-info">
                                        <i class="fas fa-user"></i> {{ $order->customer_name }}
                                        <span style="margin-left:15px;">
                                            <i class="fas fa-map-marker-alt"></i> {{ $order->customer_city }}, {{ $order->customer_country }}
                                        </span>
                                    </div>
                                    <div>
                                        <a href="{{ route('admin-printer-show', $order->id) }}" class="action-btn outline">
                                            <i class="fas fa-eye"></i> View Details
                                        </a>
                                        <a href="{{ route('admin-printer-start', $order->id) }}" class="action-btn primary">
                                            <i class="fas fa-play"></i> Start Printing
                                        </a>
                                    </div>
                                </div>
                            </div>
                            @endforeach

                            {{ $orders->links() }}
                        @else
                        <div class="text-center py-5">
                            <i class="fas fa-check-circle" style="font-size:64px;color:#11998e;"></i>
                            <h4 class="mt-3">{{ __('All caught up!') }}</h4>
                            <p class="text-muted">{{ __('No orders waiting to print.') }}</p>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
$(document).ready(function() {
    // Select all
    $('#select-all').on('change', function() {
        $('.order-checkbox').prop('checked', $(this).is(':checked'));
        updateBatchActions();
    });
    
    // Individual checkbox
    $('.order-checkbox').on('change', function() {
        updateBatchActions();
        $(this).closest('.order-card').toggleClass('selected', $(this).is(':checked'));
    });
    
    function updateBatchActions() {
        var selected = $('.order-checkbox:checked').map(function() {
            return $(this).val();
        }).get();
        
        $('#selected-ids').val(JSON.stringify(selected));
        $('#batch-actions').toggleClass('show', selected.length > 0);
    }
});
</script>
@endsection
