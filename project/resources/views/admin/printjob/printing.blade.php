@extends('layouts.admin')

@section('content')
<div class="content-area">
    <div class="mr-breadcrumb">
        <div class="row">
            <div class="col-lg-12">
                <h4 class="heading">
                    <i class="fas fa-print"></i> {{ __('Currently Printing') }}
                    <a class="add-btn" href="{{ route('admin-printjob-index') }}">
                        <i class="fas fa-arrow-left"></i> {{ __('Back to Dashboard') }}
                    </a>
                </h4>
            </div>
        </div>
    </div>

    {{-- Tab Navigation --}}
    <div class="row mb-4">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body p-2">
                    <a href="{{ route('admin-printjob-queue') }}" class="btn btn-outline-warning btn-sm mr-2">
                        <i class="fas fa-clock"></i> {{ __('Queued') }}
                    </a>
                    <a href="{{ route('admin-printjob-printing') }}" class="btn btn-info btn-sm mr-2">
                        <i class="fas fa-print"></i> {{ __('Printing') }}
                    </a>
                    <a href="{{ route('admin-printjob-completed') }}" class="btn btn-outline-success btn-sm mr-2">
                        <i class="fas fa-check"></i> {{ __('Completed') }}
                    </a>
                    <a href="{{ route('admin-printjob-failed') }}" class="btn btn-outline-danger btn-sm">
                        <i class="fas fa-times"></i> {{ __('Failed') }}
                    </a>
                </div>
            </div>
        </div>
    </div>

    {{-- Printing Jobs --}}
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-spinner fa-spin text-info"></i> {{ __('Jobs In Progress') }} ({{ $jobs->total() }})</h5>
                </div>
                <div class="card-body">
                    @if($jobs->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>{{ __('ID') }}</th>
                                    <th>{{ __('Order') }}</th>
                                    <th>{{ __('Product') }}</th>
                                    <th>{{ __('Qty') }}</th>
                                    <th>{{ __('Printer') }}</th>
                                    <th>{{ __('Started') }}</th>
                                    <th>{{ __('Elapsed') }}</th>
                                    <th>{{ __('Actions') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($jobs as $job)
                                <tr>
                                    <td>{{ $job->id }}</td>
                                    <td>
                                        <a href="{{ route('admin-order-show', $job->order_id) }}" target="_blank">
                                            #{{ $job->order->order_number ?? 'N/A' }}
                                        </a>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            @if($job->mockup_preview)
                                            <img src="{{ asset('assets/images/products/' . $job->mockup_preview) }}" 
                                                 alt="" style="width: 40px; height: 40px; object-fit: cover; margin-right: 10px; border-radius: 4px;">
                                            @endif
                                            <span>{{ Str::limit($job->product->name ?? 'N/A', 30) }}</span>
                                        </div>
                                    </td>
                                    <td><span class="badge badge-primary">{{ $job->quantity }}</span></td>
                                    <td>{{ $job->printer->name ?? __('Unassigned') }}</td>
                                    <td>{{ $job->started_at ? $job->started_at->format('H:i') : '-' }}</td>
                                    <td>
                                        @if($job->started_at)
                                            <span class="text-info">{{ $job->started_at->diffForHumans(null, true) }}</span>
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td>
                                        <div class="btn-group btn-group-sm">
                                            <a href="{{ route('admin-printjob-show', $job->id) }}" class="btn btn-info" title="{{ __('View') }}">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <button class="btn btn-success complete-job" data-id="{{ $job->id }}" title="{{ __('Complete') }}">
                                                <i class="fas fa-check"></i>
                                            </button>
                                            <button class="btn btn-danger fail-job" data-id="{{ $job->id }}" title="{{ __('Mark Failed') }}">
                                                <i class="fas fa-times"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="d-flex justify-content-center mt-4">
                        {{ $jobs->links() }}
                    </div>
                    @else
                    <div class="text-center py-5">
                        <i class="fas fa-print fa-3x text-muted mb-3"></i>
                        <p class="text-muted">{{ __('No jobs currently printing') }}</p>
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
});
</script>
@endsection
