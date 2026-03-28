@extends('layouts.admin')

@section('content')
<div class="content-area">
    <div class="mr-breadcrumb">
        <div class="row">
            <div class="col-lg-12">
                <h4 class="heading"><i class="fas fa-industry"></i> {{ __('Manufacturing Queue') }}
                    <a class="add-btn" href="{{ route('admin-manufacturing-dashboard') }}">
                        <i class="fas fa-arrow-left"></i> {{ __('Back to Dashboard') }}
                    </a>
                </h4>
            </div>
        </div>
    </div>

    {{-- Stats Cards --}}
    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card bg-warning text-white">
                <div class="card-body">
                    <h5 class="mb-0"><i class="fas fa-cogs"></i> {{ __('In Manufacturing') }}</h5>
                    <h2 class="mb-0">{{ $stats['in_manufacturing'] ?? 0 }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <h5 class="mb-0"><i class="fas fa-check-circle"></i> {{ __('Print Ready') }}</h5>
                    <h2 class="mb-0">{{ $stats['print_ready'] ?? 0 }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <h5 class="mb-0"><i class="fas fa-print"></i> {{ __('Now Printing') }}</h5>
                    <h2 class="mb-0">{{ $stats['printing'] ?? 0 }}</h2>
                </div>
            </div>
        </div>
    </div>

    {{-- Batch Actions --}}
    <div class="card mb-4">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <input type="checkbox" id="select-all" class="mr-2">
                    <span>{{ __('Select All') }}</span>
                </div>
                <button class="btn btn-success" id="batch-ready" disabled>
                    <i class="fas fa-check"></i> {{ __('Mark Selected as Print Ready') }}
                </button>
            </div>
        </div>
    </div>

    {{-- Orders Table --}}
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">{{ __('Orders in Manufacturing') }}</h5>
        </div>
        <div class="card-body">
            @if($orders->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th width="40px"></th>
                            <th>{{ __('Order #') }}</th>
                            <th>{{ __('Customer') }}</th>
                            <th>{{ __('Items') }}</th>
                            <th>{{ __('Ordered') }}</th>
                            <th>{{ __('Actions') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($orders as $order)
                        <tr>
                            <td>
                                <input type="checkbox" class="order-checkbox" value="{{ $order->id }}">
                            </td>
                            <td>
                                <a href="{{ route('admin-manufacturing-show', $order->id) }}">
                                    <strong>#{{ $order->order_number }}</strong>
                                </a>
                            </td>
                            <td>
                                <div>{{ $order->customer_name }}</div>
                                <small class="text-muted">{{ $order->customer_email }}</small>
                            </td>
                            <td>
                                {{-- Show item details with size/color --}}
                                @foreach($order->cart_items as $item)
                                <div class="mb-2 p-2 bg-light rounded">
                                    <strong>{{ $item['item']['name'] ?? 'Product' }}</strong>
                                    <div class="d-flex gap-2 mt-1">
                                        <span class="badge badge-primary">{{ __('Qty:') }} {{ $item['qty'] ?? 1 }}</span>
                                        @if(isset($item['size']))
                                        <span class="badge badge-info">{{ __('Size:') }} {{ $item['size'] }}</span>
                                        @endif
                                        @if(isset($item['color']))
                                        <span class="badge badge-secondary" style="background: {{ $item['color'] }}; color: {{ in_array(strtolower($item['color']), ['white', 'yellow', 'beige', 'cream']) ? '#000' : '#fff' }}">
                                            {{ __('Color:') }} {{ $item['color'] }}
                                        </span>
                                        @endif
                                    </div>
                                </div>
                                @endforeach
                            </td>
                            <td>
                                <div>{{ $order->created_at->format('M d, Y') }}</div>
                                <small class="text-muted">{{ $order->created_at->diffForHumans() }}</small>
                            </td>
                            <td>
                                <div class="btn-group">
                                    <a href="{{ route('admin-manufacturing-show', $order->id) }}" class="btn btn-sm btn-info" title="{{ __('View Details') }}">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <form action="{{ route('admin-manufacturing-mark-ready', $order->id) }}" method="POST" style="display:inline;">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-success" title="{{ __('Mark Print Ready') }}" onclick="return confirm('{{ __('Mark this order as Print Ready?') }}')">
                                            <i class="fas fa-check"></i> {{ __('Ready') }}
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            <div class="mt-3">
                {{ $orders->links() }}
            </div>
            @else
            <div class="text-center py-5">
                <i class="fas fa-check-circle fa-4x text-success mb-3"></i>
                <h4>{{ __('Manufacturing Queue is Empty') }}</h4>
                <p class="text-muted">{{ __('All orders have been processed and sent to printing.') }}</p>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
$(document).ready(function() {
    // Select all checkbox
    $('#select-all').on('change', function() {
        $('.order-checkbox').prop('checked', $(this).is(':checked'));
        updateBatchButton();
    });

    // Individual checkbox change
    $(document).on('change', '.order-checkbox', function() {
        updateBatchButton();
    });

    function updateBatchButton() {
        var selectedCount = $('.order-checkbox:checked').length;
        $('#batch-ready').prop('disabled', selectedCount === 0);
        if (selectedCount > 0) {
            $('#batch-ready').html('<i class="fas fa-check"></i> {{ __("Mark") }} ' + selectedCount + ' {{ __("as Print Ready") }}');
        } else {
            $('#batch-ready').html('<i class="fas fa-check"></i> {{ __("Mark Selected as Print Ready") }}');
        }
    }

    // Batch mark as ready
    $('#batch-ready').on('click', function() {
        var selectedIds = [];
        $('.order-checkbox:checked').each(function() {
            selectedIds.push($(this).val());
        });

        if (selectedIds.length === 0) {
            return;
        }

        if (!confirm('{{ __("Mark") }} ' + selectedIds.length + ' {{ __("orders as Print Ready?") }}')) {
            return;
        }

        $.ajax({
            url: '{{ route("admin-manufacturing-batch-ready") }}',
            type: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                order_ids: selectedIds
            },
            success: function(response) {
                toastr.success(response.message);
                location.reload();
            },
            error: function(xhr) {
                toastr.error(xhr.responseJSON?.error || '{{ __("An error occurred") }}');
            }
        });
    });
});
</script>
@endsection
