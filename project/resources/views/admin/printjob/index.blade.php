@extends('layouts.admin')

@section('content')
<div class="content-area">
    <div class="mr-breadcrumb">
        <div class="row">
            <div class="col-lg-12">
                <h4 class="heading"><i class="fas fa-print"></i> {{ __('Print Queue Dashboard') }}</h4>
            </div>
        </div>
    </div>

    {{-- Stats Cards --}}
    <div class="row mb-4">
        <div class="col-lg-3 col-md-6">
            <div class="card card-stats">
                <div class="card-body">
                    <div class="row">
                        <div class="col">
                            <h5 class="card-title text-uppercase text-muted mb-0">{{ __('Queued') }}</h5>
                            <span class="h2 font-weight-bold mb-0">{{ $stats['queued'] }}</span>
                        </div>
                        <div class="col-auto">
                            <div class="icon icon-shape bg-warning text-white rounded-circle shadow">
                                <i class="fas fa-clock"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6">
            <div class="card card-stats">
                <div class="card-body">
                    <div class="row">
                        <div class="col">
                            <h5 class="card-title text-uppercase text-muted mb-0">{{ __('Printing') }}</h5>
                            <span class="h2 font-weight-bold mb-0">{{ $stats['printing'] }}</span>
                        </div>
                        <div class="col-auto">
                            <div class="icon icon-shape bg-info text-white rounded-circle shadow">
                                <i class="fas fa-spinner fa-spin"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6">
            <div class="card card-stats">
                <div class="card-body">
                    <div class="row">
                        <div class="col">
                            <h5 class="card-title text-uppercase text-muted mb-0">{{ __('Completed Today') }}</h5>
                            <span class="h2 font-weight-bold mb-0">{{ $stats['completed_today'] }}</span>
                        </div>
                        <div class="col-auto">
                            <div class="icon icon-shape bg-success text-white rounded-circle shadow">
                                <i class="fas fa-check"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6">
            <div class="card card-stats">
                <div class="card-body">
                    <div class="row">
                        <div class="col">
                            <h5 class="card-title text-uppercase text-muted mb-0">{{ __('Failed Today') }}</h5>
                            <span class="h2 font-weight-bold mb-0">{{ $stats['failed_today'] }}</span>
                        </div>
                        <div class="col-auto">
                            <div class="icon icon-shape bg-danger text-white rounded-circle shadow">
                                <i class="fas fa-times"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Quick Actions --}}
    <div class="row mb-4">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">{{ __('Quick Actions') }}</h5>
                </div>
                <div class="card-body">
                    <a href="{{ route('admin-printjob-queue') }}" class="btn btn-warning mr-2">
                        <i class="fas fa-clock"></i> {{ __('View Queue') }} ({{ $stats['queued'] }})
                    </a>
                    <a href="{{ route('admin-printjob-printing') }}" class="btn btn-info mr-2">
                        <i class="fas fa-print"></i> {{ __('Currently Printing') }} ({{ $stats['printing'] }})
                    </a>
                    <a href="{{ route('admin-printjob-completed') }}" class="btn btn-success mr-2">
                        <i class="fas fa-check"></i> {{ __('Completed') }}
                    </a>
                    <a href="{{ route('admin-printjob-failed') }}" class="btn btn-danger mr-2">
                        <i class="fas fa-times"></i> {{ __('Failed') }}
                    </a>
                    <a href="{{ route('admin-printjob-capacity') }}" class="btn btn-secondary">
                        <i class="fas fa-chart-bar"></i> {{ __('Capacity Stats') }}
                    </a>
                </div>
            </div>
        </div>
    </div>

    {{-- Recent Activity --}}
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">{{ __('All Print Jobs') }}</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="printjobs-table" class="table table-hover dt-responsive" cellspacing="0" width="100%">
                            <thead>
                                <tr>
                                    <th>{{ __('ID') }}</th>
                                    <th>{{ __('Order') }}</th>
                                    <th>{{ __('Product') }}</th>
                                    <th>{{ __('Quantity') }}</th>
                                    <th>{{ __('Quality') }}</th>
                                    <th>{{ __('Priority') }}</th>
                                    <th>{{ __('Status') }}</th>
                                    <th>{{ __('Printer') }}</th>
                                    <th>{{ __('Created') }}</th>
                                    <th>{{ __('Actions') }}</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.card-stats {
    margin-bottom: 20px;
}
.icon-shape {
    width: 48px;
    height: 48px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.5rem;
}
</style>

@endsection

@section('scripts')
<script type="text/javascript">
$(document).ready(function() {
    var table = $('#printjobs-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: '{{ route("admin-printjob-datatables", "all") }}',
        columns: [
            {data: 'id', name: 'id'},
            {data: 'order_id', name: 'order_id'},
            {data: 'product_id', name: 'product_id'},
            {data: 'quantity', name: 'quantity'},
            {data: 'quality_tier', name: 'quality_tier'},
            {data: 'priority', name: 'priority'},
            {data: 'status', name: 'status'},
            {data: 'printer.name', name: 'printer.name', defaultContent: 'Unassigned'},
            {data: 'created_at', name: 'created_at'},
            {data: 'action', searchable: false, orderable: false}
        ],
        order: [[0, 'desc']]
    });

    // Start printing
    $(document).on('click', '.start-print', function(e) {
        e.preventDefault();
        var url = $(this).data('href');
        
        if (confirm('Start printing this job?')) {
            $.ajax({
                url: url,
                type: 'POST',
                data: {_token: '{{ csrf_token() }}'},
                success: function(response) {
                    toastr.success(response.message);
                    table.ajax.reload();
                },
                error: function(xhr) {
                    toastr.error(xhr.responseJSON?.error || 'An error occurred');
                }
            });
        }
    });

    // Complete printing
    $(document).on('click', '.complete-print', function(e) {
        e.preventDefault();
        var url = $(this).data('href');
        
        var notes = prompt('Add completion notes (optional):');
        if (notes !== null) {
            $.ajax({
                url: url,
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    notes: notes
                },
                success: function(response) {
                    toastr.success(response.message);
                    table.ajax.reload();
                },
                error: function(xhr) {
                    toastr.error(xhr.responseJSON?.error || 'An error occurred');
                }
            });
        }
    });

    // Fail printing
    $(document).on('click', '.fail-print', function(e) {
        e.preventDefault();
        var url = $(this).data('href');
        
        var reason = prompt('Reason for failure:');
        if (reason) {
            $.ajax({
                url: url,
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    reason: reason
                },
                success: function(response) {
                    toastr.success(response.message);
                    table.ajax.reload();
                },
                error: function(xhr) {
                    toastr.error(xhr.responseJSON?.error || 'An error occurred');
                }
            });
        }
    });

    // Hold printing
    $(document).on('click', '.hold-print', function(e) {
        e.preventDefault();
        var url = $(this).data('href');
        
        var reason = prompt('Reason for holding:');
        if (reason) {
            $.ajax({
                url: url,
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    reason: reason
                },
                success: function(response) {
                    toastr.success(response.message);
                    table.ajax.reload();
                },
                error: function(xhr) {
                    toastr.error(xhr.responseJSON?.error || 'An error occurred');
                }
            });
        }
    });

    // Resume printing
    $(document).on('click', '.resume-print', function(e) {
        e.preventDefault();
        var url = $(this).data('href');
        
        if (confirm('Resume this print job?')) {
            $.ajax({
                url: url,
                type: 'POST',
                data: {_token: '{{ csrf_token() }}'},
                success: function(response) {
                    toastr.success(response.message);
                    table.ajax.reload();
                },
                error: function(xhr) {
                    toastr.error(xhr.responseJSON?.error || 'An error occurred');
                }
            });
        }
    });
});
</script>
@endsection
