@extends('layouts.admin')

@section('content')
<div class="content-area">
    <div class="mr-breadcrumb">
        <div class="row">
            <div class="col-lg-12">
                <h4 class="heading">{{ __('Mockup Templates') }}
                    <a class="add-btn" href="{{ route('admin-mockup-create') }}">
                        <i class="fas fa-plus"></i> {{ __('Add New Template') }}
                    </a>
                </h4>
            </div>
        </div>
    </div>

    <div class="product-area">
        <div class="row">
            <div class="col-lg-12">
                <div class="mr-table">
                    @include('alerts.admin.form-success')
                    
                    <div class="table-responsive">
                        <table class="table table-hover dt-responsive" cellspacing="0" width="100%">
                            <thead>
                                <tr>
                                    <th>{{ __('Image') }}</th>
                                    <th>{{ __('Name') }}</th>
                                    <th>{{ __('Type') }}</th>
                                    <th>{{ __('Style') }}</th>
                                    <th>{{ __('Color') }}</th>
                                    <th>{{ __('Status') }}</th>
                                    <th>{{ __('Actions') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($templates as $template)
                                <tr>
                                    <td>
                                        <img src="{{ $template->image_url }}" alt="{{ $template->name }}" 
                                             style="width: 60px; height: 60px; object-fit: contain; background: #f5f5f5; border-radius: 4px;">
                                    </td>
                                    <td>{{ $template->name }}</td>
                                    <td>{{ $template->product_type_name }}</td>
                                    <td>{{ $template->style_name }}</td>
                                    <td>
                                        <span style="display:inline-block; width:20px; height:20px; background:{{ $template->color }}; border-radius:50%; border:1px solid #ddd;"></span>
                                        {{ $template->color_name }}
                                    </td>
                                    <td>
                                        <a href="{{ route('admin-mockup-status', $template->id) }}" class="btn btn-sm {{ $template->status == 1 ? 'btn-success' : 'btn-danger' }}">
                                            {{ $template->status == 1 ? __('Active') : __('Inactive') }}
                                        </a>
                                    </td>
                                    <td>
                                        <div class="action-list">
                                            <a href="{{ route('admin-mockup-edit', $template->id) }}" class="btn btn-sm btn-primary">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <a href="javascript:;" data-href="{{ route('admin-mockup-delete', $template->id) }}" 
                                               data-toggle="modal" data-target="#confirm-delete" class="btn btn-sm btn-danger delete">
                                                <i class="fas fa-trash-alt"></i>
                                            </a>
                                        </div>
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

{{-- DELETE MODAL --}}
<div class="modal fade" id="confirm-delete" tabindex="-1" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header d-block text-center">
                <h4 class="modal-title">{{ __('Confirm Delete') }}</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <p class="text-center">{{ __('Are you sure you want to delete this mockup template?') }}</p>
            </div>
            <div class="modal-footer justify-content-center">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('Cancel') }}</button>
                <a href="" class="btn btn-danger btn-ok">{{ __('Delete') }}</a>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
$(document).ready(function() {
    $('#confirm-delete').on('show.bs.modal', function(e) {
        $(this).find('.btn-ok').attr('href', $(e.relatedTarget).data('href'));
    });
});
</script>
@endsection
