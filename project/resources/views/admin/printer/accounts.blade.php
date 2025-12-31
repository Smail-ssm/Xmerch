@extends('layouts.admin')

@section('styles')
<style>
.account-card {
    background: #fff;
    border-radius: 12px;
    padding: 20px;
    margin-bottom: 20px;
    box-shadow: 0 4px 15px rgba(0,0,0,0.05);
    display: flex;
    align-items: center;
    gap: 20px;
    transition: transform 0.2s;
}
.account-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 25px rgba(0,0,0,0.1);
}
.account-avatar {
    width: 80px;
    height: 80px;
    border-radius: 50%;
    object-fit: cover;
    border: 3px solid #f8f9fa;
}
.account-info {
    flex: 1;
}
.account-info h5 {
    margin: 0 0 5px;
    font-size: 18px;
    color: #333;
}
.account-info p {
    margin: 0;
    font-size: 14px;
    color: #666;
}
.role-badge {
    padding: 4px 12px;
    border-radius: 15px;
    font-size: 12px;
    font-weight: 600;
    background: #eef2ff;
    color: #4f46e5;
    display: inline-block;
    margin-top: 5px;
}
.account-actions {
    display: flex;
    gap: 10px;
}
.action-icon {
    width: 40px;
    height: 40px;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #fff;
    text-decoration: none;
    transition: opacity 0.2s;
}
.action-icon.edit { background: #4facfe; }
.action-icon.delete { background: #f5576c; }
.action-icon:hover { opacity: 0.8; color: #fff; }

.create-section {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border-radius: 16px;
    padding: 30px;
    color: #fff;
    margin-bottom: 30px;
    display: flex;
    justify-content: space-between;
    align-items: center;
}
.btn-create-account {
    background: #fff;
    color: #764ba2;
    padding: 12px 25px;
    border-radius: 8px;
    font-weight: 700;
    text-decoration: none;
    transition: all 0.2s;
}
.btn-create-account:hover {
    transform: scale(1.05);
    box-shadow: 0 5px 15px rgba(0,0,0,0.2);
}
</style>
@endsection

@section('content')
<div class="content-area">
    <div class="mr-breadcrumb">
        <div class="row">
            <div class="col-lg-12">
                <h4 class="heading">{{ __('Manufacturing Accounts') }}</h4>
                <ul class="links">
                    <li><a href="{{ route('admin.dashboard') }}">{{ __('Dashboard') }}</a></li>
                    <li><a href="{{ route('admin-printer-dashboard') }}">{{ __('Printer') }}</a></li>
                    <li><a href="#">{{ __('Accounts') }}</a></li>
                </ul>
            </div>
        </div>
    </div>

    <div class="create-section">
        <div>
            <h2>Manage Production Team</h2>
            <p>Create and manage accounts for Printers, Manufacturers, and Production Staff.</p>
        </div>
        <a href="javascript:;" data-href="{{ route('admin-staff-create') }}" id="add-data" class="btn-create-account" data-toggle="modal" data-target="#modal1">
            <i class="fas fa-plus"></i> Create New Account
        </a>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="row" id="staff-list">
                @foreach($staffs as $staff)
                <div class="col-lg-6">
                    <div class="account-card">
                        <img src="{{ $staff->photo ? asset('assets/images/admins/'.$staff->photo) : asset('assets/images/noimage.png') }}" class="account-avatar" alt="">
                        <div class="account-info">
                            <h5>{{ $staff->name }}</h5>
                            <p>{{ $staff->email }}</p>
                            <span class="role-badge">{{ $staff->role ? $staff->role->name : 'No Role' }}</span>
                        </div>
                        <div class="account-actions">
                            <a data-href="{{ route('admin-staff-edit', $staff->id) }}" class="action-icon edit" data-toggle="modal" data-target="#modal1">
                                <i class="fas fa-edit"></i>
                            </a>
                            <a href="javascript:;" data-href="{{ route('admin-staff-delete', $staff->id) }}" data-toggle="modal" data-target="#confirm-delete" class="action-icon delete">
                                <i class="fas fa-trash-alt"></i>
                            </a>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            
            @if($staffs->count() == 0)
            <div class="text-center py-5">
                <i class="fas fa-users-slash" style="font-size:64px;color:#ddd;"></i>
                <h4 class="mt-3 text-muted">No manufacturing accounts found</h4>
                <p>Add a new staff member and assign them a role with 'Print Production' or 'Manufacturing' permissions.</p>
            </div>
            @endif
        </div>
    </div>

    <hr class="my-5">

    <div class="row mt-4">
        <div class="col-lg-12">
            <div class="product-description">
                <div class="body-area">
                    <h5 class="mb-4"><i class="fas fa-user-shield"></i> {{ __('Quick Role Assignment') }}</h5>
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>{{ __('Role Name') }}</th>
                                    <th>{{ __('Active Permissions') }}</th>
                                    <th>{{ __('Actions') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($roles as $role)
                                @if(strpos($role->section, 'print_production') !== false || strpos($role->section, 'manufacturing') !== false)
                                <tr>
                                    <td><strong>{{ $role->name }}</strong></td>
                                    <td>
                                        @foreach(explode(' , ', $role->section) as $sec)
                                            <span class="badge badge-secondary">{{ ucwords(str_replace('_', ' ', $sec)) }}</span>
                                        @endforeach
                                    </td>
                                    <td>
                                        <a href="{{ route('admin-role-edit', $role->id) }}" class="btn btn-sm btn-primary">
                                            <i class="fas fa-cog"></i> {{ __('Edit Permissions') }}
                                        </a>
                                    </td>
                                </tr>
                                @endif
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-3">
                        <a href="{{ route('admin-role-create') }}" class="btn btn-outline-primary">
                            <i class="fas fa-plus"></i> {{ __('Create Specialized Production Role') }}
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- MODAL SECTION --}}
<div class="modal fade" id="modal1" tabindex="-1" role="dialog" aria-labelledby="modal1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="submit-loader">
                <img src="{{ asset('assets/images/'.$gs->admin_loader) }}" alt="">
            </div>
            <div class="modal-header">
                <h5 class="modal-title"></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('Close') }}</button>
            </div>
        </div>
    </div>
</div>

{{-- DELETE MODAL --}}
<div class="modal fade" id="confirm-delete" tabindex="-1" role="dialog" aria-labelledby="modal1" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header d-block text-center">
            <h4 class="modal-title d-inline-block">{{ __('Confirm Delete') }}</h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body">
            <p class="text-center">{{ __('You are about to delete this Staff.') }}</p>
            <p class="text-center">{{ __('Do you want to proceed?') }}</p>
        </div>
        <div class="modal-footer justify-content-center">
            <button type="button" class="btn btn-default" data-dismiss="modal">{{ __('Cancel') }}</button>
            <a class="btn btn-danger btn-ok">{{ __('Delete') }}</a>
        </div>
    </div>
  </div>
</div>
@endsection

@section('scripts')
<script type="text/javascript">
    (function($) {
        "use strict";

        $('#confirm-delete').on('show.bs.modal', function(e) {
            $(this).find('.btn-ok').attr('href', $(e.relatedTarget).data('href'));
        });

    })(jQuery);
</script>
@endsection
