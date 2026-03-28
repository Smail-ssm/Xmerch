@extends('layouts.admin')

@section('content')
<div class="content-area">
    <div class="mr-breadcrumb">
        <div class="row">
            <div class="col-lg-12">
                <h4 class="heading">
                    <i class="fas fa-print"></i> {{ __('Print Job Details') }} #{{ $job->id }}
                    <a class="add-btn" href="{{ route('admin-printjob-index') }}">
                        <i class="fas fa-arrow-left"></i> {{ __('Back to Dashboard') }}
                    </a>
                </h4>
            </div>
        </div>
    </div>

    <div class="row">
        {{-- Job Info --}}
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">{{ __('Job Information') }}</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tr>
                                    <th width="40%">{{ __('Status') }}</th>
                                    <td>{!! $job->status_badge !!}</td>
                                </tr>
                                <tr>
                                    <th>{{ __('Order') }}</th>
                                    <td>
                                        <a href="{{ route('admin-order-show', $job->order_id) }}" target="_blank">
                                            #{{ optional($job->order)->order_number ?? 'N/A' }}
                                        </a>
                                    </td>
                                </tr>
                                <tr>
                                    <th>{{ __('Product') }}</th>
                                    <td>{{ optional($job->product)->name ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <th>{{ __('Quantity') }}</th>
                                    <td><span class="badge badge-primary">{{ $job->quantity }}</span></td>
                                </tr>
                                <tr>
                                    <th>{{ __('Quality Tier') }}</th>
                                    <td>{{ ucfirst($job->quality_tier) }}</td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tr>
                                    <th width="40%">{{ __('Priority') }}</th>
                                    <td>{{ $job->priority_label }}</td>
                                </tr>
                                <tr>
                                    <th>{{ __('Assigned To') }}</th>
                                    <td>{{ optional($job->printer)->name ?? __('Unassigned') }}</td>
                                </tr>
                                <tr>
                                    <th>{{ __('Created') }}</th>
                                    <td>{{ $job->created_at->format('M d, Y H:i') }}</td>
                                </tr>
                                <tr>
                                    <th>{{ __('Started') }}</th>
                                    <td>{{ $job->started_at ? $job->started_at->format('M d, Y H:i') : '-' }}</td>
                                </tr>
                                <tr>
                                    <th>{{ __('Completed') }}</th>
                                    <td>{{ $job->completed_at ? $job->completed_at->format('M d, Y H:i') : '-' }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    @if($job->notes)
                    <div class="alert alert-info">
                        <strong>{{ __('Notes') }}:</strong> {{ $job->notes }}
                    </div>
                    @endif

                    {{-- Time Stats --}}
                    <div class="row mt-4">
                        <div class="col-md-4">
                            <div class="card bg-light">
                                <div class="card-body text-center">
                                    <h6 class="text-muted">{{ __('Estimated Time') }}</h6>
                                    <h3>{{ $job->estimated_time_minutes ?? 30 }} <small>{{ __('min') }}</small></h3>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card bg-light">
                                <div class="card-body text-center">
                                    <h6 class="text-muted">{{ __('Actual Time') }}</h6>
                                    <h3>{{ $job->actual_time_minutes ?? '-' }} <small>{{ $job->actual_time_minutes ? __('min') : '' }}</small></h3>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card bg-light">
                                <div class="card-body text-center">
                                    <h6 class="text-muted">{{ __('Efficiency') }}</h6>
                                    @if($job->actual_time_minutes && $job->estimated_time_minutes)
                                        @php
                                            $efficiency = round(($job->estimated_time_minutes / $job->actual_time_minutes) * 100);
                                        @endphp
                                        <h3 class="{{ $efficiency >= 100 ? 'text-success' : 'text-warning' }}">
                                            {{ $efficiency }}%
                                        </h3>
                                    @else
                                        <h3>-</h3>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Customer Info --}}
            @if($job->order)
            <div class="card mt-4">
                <div class="card-header">
                    <h5 class="mb-0">{{ __('Customer Information') }}</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <p><strong>{{ __('Name') }}:</strong> {{ $job->order->customer_name }}</p>
                            <p><strong>{{ __('Email') }}:</strong> {{ $job->order->customer_email }}</p>
                            <p><strong>{{ __('Phone') }}:</strong> {{ $job->order->customer_phone }}</p>
                        </div>
                        <div class="col-md-6">
                            <p><strong>{{ __('Shipping Address') }}:</strong></p>
                            <p>
                                {{ $job->order->shipping_address }}<br>
                                {{ $job->order->shipping_city }}, {{ $job->order->shipping_state }}<br>
                                {{ $job->order->shipping_country }} {{ $job->order->shipping_zip }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            @endif
        </div>

        {{-- Actions & Preview --}}
        <div class="col-lg-4">
            {{-- Actions --}}
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">{{ __('Actions') }}</h5>
                </div>
                <div class="card-body">
                    @if($job->status === 'queued')
                        <button class="btn btn-success btn-block mb-2 start-job" data-id="{{ $job->id }}">
                            <i class="fas fa-play"></i> {{ __('Start Printing') }}
                        </button>
                        <button class="btn btn-secondary btn-block mb-2 hold-job" data-id="{{ $job->id }}">
                            <i class="fas fa-pause"></i> {{ __('Put On Hold') }}
                        </button>
                    @elseif($job->status === 'printing')
                        <button class="btn btn-success btn-block mb-2 complete-job" data-id="{{ $job->id }}">
                            <i class="fas fa-check"></i> {{ __('Mark Complete') }}
                        </button>
                        <button class="btn btn-danger btn-block mb-2 fail-job" data-id="{{ $job->id }}">
                            <i class="fas fa-times"></i> {{ __('Mark Failed') }}
                        </button>
                    @elseif($job->status === 'on_hold')
                        <button class="btn btn-warning btn-block mb-2 resume-job" data-id="{{ $job->id }}">
                            <i class="fas fa-play"></i> {{ __('Resume') }}
                        </button>
                    @elseif($job->status === 'failed')
                        <button class="btn btn-warning btn-block mb-2 resume-job" data-id="{{ $job->id }}">
                            <i class="fas fa-redo"></i> {{ __('Retry Job') }}
                        </button>
                    @endif

                    @if($designFileUrl)
                    <a href="{{ $designFileUrl }}" class="btn btn-primary btn-block mb-2" download>
                        <i class="fas fa-download"></i> {{ __('Download Print File') }}
                    </a>
                    @else
                    <div class="alert alert-warning mb-2">
                        <i class="fas fa-exclamation-triangle"></i> {{ __('No print file available') }}
                    </div>
                    @endif
                </div>
            </div>

            {{-- Preview --}}
            <div class="card mt-4">
                <div class="card-header">
                    <h5 class="mb-0">{{ __('Product Preview') }}</h5>
                </div>
                <div class="card-body text-center">
                    @if($job->mockup_preview)
                        <img src="{{ asset('assets/images/products/' . $job->mockup_preview) }}" 
                             alt="Product Preview" 
                             class="img-fluid rounded" 
                             style="max-height: 300px;">
                    @elseif($job->product && $job->product->photo)
                        <img src="{{ asset('assets/images/products/' . $job->product->photo) }}" 
                             alt="Product" 
                             class="img-fluid rounded" 
                             style="max-height: 300px;">
                    @else
                        <div class="text-muted py-5">
                            <i class="fas fa-image fa-3x mb-2"></i>
                            <p>{{ __('No preview available') }}</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
$(document).ready(function() {
    // Start job
    $(document).on('click', '.start-job', function() {
        var id = $(this).data('id');
        $.ajax({
            url: '{{ url("admin/printjobs") }}/' + id + '/start',
            type: 'POST',
            data: {_token: '{{ csrf_token() }}'},
            success: function(response) {
                toastr.success(response.message);
                location.reload();
            },
            error: function(xhr) {
                toastr.error(xhr.responseJSON?.error || 'An error occurred');
            }
        });
    });

    // Complete job
    $(document).on('click', '.complete-job', function() {
        var id = $(this).data('id');
        var notes = prompt('{{ __("Completion notes (optional):") }}');
        if (notes !== null) {
            $.ajax({
                url: '{{ url("admin/printjobs") }}/' + id + '/complete',
                type: 'POST',
                data: {_token: '{{ csrf_token() }}', notes: notes},
                success: function(response) {
                    toastr.success(response.message);
                    location.reload();
                },
                error: function(xhr) {
                    toastr.error(xhr.responseJSON?.error || 'An error occurred');
                }
            });
        }
    });

    // Fail job
    $(document).on('click', '.fail-job', function() {
        var id = $(this).data('id');
        var reason = prompt('{{ __("Reason for failure:") }}');
        if (reason) {
            $.ajax({
                url: '{{ url("admin/printjobs") }}/' + id + '/fail',
                type: 'POST',
                data: {_token: '{{ csrf_token() }}', reason: reason},
                success: function(response) {
                    toastr.success(response.message);
                    location.reload();
                },
                error: function(xhr) {
                    toastr.error(xhr.responseJSON?.error || 'An error occurred');
                }
            });
        }
    });

    // Hold job
    $(document).on('click', '.hold-job', function() {
        var id = $(this).data('id');
        var reason = prompt('{{ __("Reason for holding:") }}');
        if (reason) {
            $.ajax({
                url: '{{ url("admin/printjobs") }}/' + id + '/hold',
                type: 'POST',
                data: {_token: '{{ csrf_token() }}', reason: reason},
                success: function(response) {
                    toastr.success(response.message);
                    location.reload();
                },
                error: function(xhr) {
                    toastr.error(xhr.responseJSON?.error || 'An error occurred');
                }
            });
        }
    });

    // Resume job
    $(document).on('click', '.resume-job', function() {
        var id = $(this).data('id');
        $.ajax({
            url: '{{ url("admin/printjobs") }}/' + id + '/resume',
            type: 'POST',
            data: {_token: '{{ csrf_token() }}'},
            success: function(response) {
                toastr.success(response.message);
                location.reload();
            },
            error: function(xhr) {
                toastr.error(xhr.responseJSON?.error || 'An error occurred');
            }
        });
    });
});
</script>
@endsection
