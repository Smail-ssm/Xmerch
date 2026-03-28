@extends('layouts.admin')

@section('content')
<div class="content-area">
    <div class="mr-breadcrumb">
        <div class="row">
            <div class="col-lg-12">
                <h4 class="heading">
                    <i class="fas fa-clock"></i> {{ __('Print Queue') }}
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
                    <a href="{{ route('admin-printjob-queue') }}" class="btn btn-warning btn-sm mr-2">
                        <i class="fas fa-clock"></i> {{ __('Queued') }}
                    </a>
                    <a href="{{ route('admin-printjob-printing') }}" class="btn btn-outline-info btn-sm mr-2">
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

    {{-- Queue Table --}}
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><i class="fas fa-clock text-warning"></i> {{ __('Queued Jobs') }} ({{ $jobs->total() }})</h5>
                    <div>
                        <button class="btn btn-sm btn-success" id="bulk-start" disabled>
                            <i class="fas fa-play"></i> {{ __('Start Selected') }}
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    @if($jobs->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th><input type="checkbox" id="select-all"></th>
                                    <th>{{ __('ID') }}</th>
                                    <th>{{ __('Order') }}</th>
                                    <th>{{ __('Product') }}</th>
                                    <th>{{ __('Qty') }}</th>
                                    <th>{{ __('Quality') }}</th>
                                    <th>{{ __('Priority') }}</th>
                                    <th>{{ __('Est. Time') }}</th>
                                    <th>{{ __('Created') }}</th>
                                    <th>{{ __('Actions') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($jobs as $job)
                                <tr>
                                    <td><input type="checkbox" class="job-checkbox" value="{{ $job->id }}"></td>
                                    <td>{{ $job->id }}</td>
                                    <td>
                                        <a href="{{ route('admin-order-show', $job->order_id) }}" target="_blank">
                                            #{{ optional($job->order)->order_number ?? 'N/A' }}
                                        </a>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            @if($job->mockup_preview)
                                            <img src="{{ asset('assets/images/products/' . $job->mockup_preview) }}" 
                                                 alt="" style="width: 40px; height: 40px; object-fit: cover; margin-right: 10px; border-radius: 4px;">
                                            @endif
                                            <span>{{ Str::limit(optional($job->product)->name ?? 'N/A', 30) }}</span>
                                        </div>
                                    </td>
                                    <td><span class="badge badge-primary">{{ $job->quantity }}</span></td>
                                    <td>
                                        @php
                                            $qualityBadge = [
                                                'standard' => 'secondary',
                                                'premium' => 'info',
                                                'deluxe' => 'warning'
                                            ];
                                        @endphp
                                        <span class="badge badge-{{ $qualityBadge[$job->quality_tier] ?? 'secondary' }}">
                                            {{ ucfirst($job->quality_tier) }}
                                        </span>
                                    </td>
                                    <td>
                                        @php
                                            $priorityBadge = [1 => 'danger', 2 => 'warning', 3 => 'secondary'];
                                            $priorityLabel = [1 => 'High', 2 => 'Medium', 3 => 'Low'];
                                        @endphp
                                        <span class="badge badge-{{ $priorityBadge[$job->priority] ?? 'secondary' }}">
                                            {{ $priorityLabel[$job->priority] ?? 'Normal' }}
                                        </span>
                                    </td>
                                    <td>{{ $job->estimated_time_minutes ?? 30 }} {{ __('min') }}</td>
                                    <td>{{ $job->created_at->diffForHumans() }}</td>
                                    <td>
                                        <div class="btn-group btn-group-sm">
                                            <a href="{{ route('admin-printjob-show', $job->id) }}" class="btn btn-info" title="{{ __('View') }}">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <button class="btn btn-success start-job" data-id="{{ $job->id }}" title="{{ __('Start') }}">
                                                <i class="fas fa-play"></i>
                                            </button>
                                            <button class="btn btn-secondary hold-job" data-id="{{ $job->id }}" title="{{ __('Hold') }}">
                                                <i class="fas fa-pause"></i>
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
                        <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                        <p class="text-muted">{{ __('No jobs in queue') }}</p>
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
    // Select all
    $('#select-all').on('change', function() {
        $('.job-checkbox').prop('checked', $(this).prop('checked'));
        updateBulkButtons();
    });

    $('.job-checkbox').on('change', function() {
        updateBulkButtons();
    });

    function updateBulkButtons() {
        var selected = $('.job-checkbox:checked').length;
        $('#bulk-start').prop('disabled', selected === 0);
    }

    // Start single job
    $(document).on('click', '.start-job', function() {
        var id = $(this).data('id');
        if (confirm('{{ __("Start printing this job?") }}')) {
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

    // Bulk start
    $('#bulk-start').on('click', function() {
        var ids = [];
        $('.job-checkbox:checked').each(function() {
            ids.push($(this).val());
        });

        if (ids.length > 0 && confirm('{{ __("Start printing") }} ' + ids.length + ' {{ __("jobs?") }}')) {
            $.ajax({
                url: '{{ route("admin-printjob-bulk") }}',
                type: 'POST',
                data: {_token: '{{ csrf_token() }}', action: 'start', job_ids: ids},
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
