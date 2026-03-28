@extends('layouts.admin')

@section('content')
<div class="content-area">
    <div class="mr-breadcrumb">
        <div class="row">
            <div class="col-lg-12">
                <h4 class="heading">
                    <i class="fas fa-exclamation-triangle"></i> {{ __('Failed Jobs') }}
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
                    <a href="{{ route('admin-printjob-printing') }}" class="btn btn-outline-info btn-sm mr-2">
                        <i class="fas fa-print"></i> {{ __('Printing') }}
                    </a>
                    <a href="{{ route('admin-printjob-completed') }}" class="btn btn-outline-success btn-sm mr-2">
                        <i class="fas fa-check"></i> {{ __('Completed') }}
                    </a>
                    <a href="{{ route('admin-printjob-failed') }}" class="btn btn-danger btn-sm">
                        <i class="fas fa-times"></i> {{ __('Failed') }}
                    </a>
                </div>
            </div>
        </div>
    </div>

    {{-- Failed Jobs --}}
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-times text-danger"></i> {{ __('Failed Jobs') }} ({{ $jobs->total() }})</h5>
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
                                    <th>{{ __('Failed At') }}</th>
                                    <th>{{ __('Reason') }}</th>
                                    <th>{{ __('Actions') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($jobs as $job)
                                <tr>
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
                                    <td><span class="badge badge-danger">{{ $job->quantity }}</span></td>
                                    <td>{{ optional($job->printer)->name ?? __('Unassigned') }}</td>
                                    <td>{{ $job->updated_at->format('M d, H:i') }}</td>
                                    <td>
                                        <span class="text-danger" title="{{ $job->notes }}">
                                            {{ Str::limit($job->notes, 40) ?? __('No reason provided') }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="btn-group btn-group-sm">
                                            <a href="{{ route('admin-printjob-show', $job->id) }}" class="btn btn-info" title="{{ __('View') }}">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <button class="btn btn-warning retry-job" data-id="{{ $job->id }}" title="{{ __('Retry') }}">
                                                <i class="fas fa-redo"></i>
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
                        <i class="fas fa-thumbs-up fa-3x text-success mb-3"></i>
                        <p class="text-muted">{{ __('No failed jobs - great work!') }}</p>
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
    // Retry failed job
    $(document).on('click', '.retry-job', function() {
        var id = $(this).data('id');
        if (confirm('{{ __("Retry this print job?") }}')) {
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
        }
    });
});
</script>
@endsection
