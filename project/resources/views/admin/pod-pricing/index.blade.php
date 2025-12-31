@extends('layouts.admin')

@section('content')
<div class="content-area">
    <div class="mr-breadcrumb">
        <div class="row">
            <div class="col-lg-12">
                <h4 class="heading">{{ __('POD Pricing Options') }}
                    <a class="add-btn" href="{{ route('admin-pod-pricing-create') }}">
                        <i class="fas fa-plus"></i> {{ __('Add New') }}
                    </a>
                </h4>
                <ul class="links">
                    <li><a href="{{ route('admin.dashboard') }}">{{ __('Dashboard') }}</a></li>
                    <li><a href="javascript:;">{{ __('POD Settings') }}</a></li>
                    <li><a href="{{ route('admin-pod-pricing-index') }}">{{ __('Pricing Options') }}</a></li>
                </ul>
            </div>
        </div>
    </div>

    <div class="add-product-content">
        <div class="row">
            <div class="col-lg-12">
                <div class="product-description">
                    <div class="body-area">
                        @include('alerts.admin.form-both')
                        
                        @foreach($categories as $catKey => $catName)
                        <div class="card mb-4" style="border-left: 4px solid #007bff;">
                            <div class="card-header" style="background: #f8f9fa;">
                                <h5 style="margin: 0;"><i class="fas fa-tag"></i> {{ $catName }}</h5>
                            </div>
                            <div class="card-body">
                                @if(isset($options[$catKey]) && count($options[$catKey]) > 0)
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>{{ __('Name') }}</th>
                                            <th>{{ __('Value') }}</th>
                                            <th>{{ __('Price') }}</th>
                                            <th>{{ __('Order') }}</th>
                                            <th>{{ __('Status') }}</th>
                                            <th class="text-right">{{ __('Actions') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($options[$catKey] as $option)
                                        <tr>
                                            <td>{{ $option->name }}</td>
                                            <td><code>{{ $option->value }}</code></td>
                                            <td><strong>${{ number_format($option->price, 2) }}</strong></td>
                                            <td>{{ $option->sort_order }}</td>
                                            <td>
                                                <a href="{{ route('admin-pod-pricing-status', $option->id) }}" 
                                                   class="badge badge-{{ $option->is_active ? 'success' : 'danger' }}">
                                                    {{ $option->is_active ? 'Active' : 'Inactive' }}
                                                </a>
                                            </td>
                                            <td class="text-right">
                                                <a href="{{ route('admin-pod-pricing-edit', $option->id) }}" 
                                                   class="btn btn-sm btn-primary">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <a href="{{ route('admin-pod-pricing-delete', $option->id) }}" 
                                                   class="btn btn-sm btn-danger"
                                                   onclick="return confirm('Delete this option?')">
                                                    <i class="fas fa-trash"></i>
                                                </a>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                                @else
                                <div class="alert alert-info">
                                    No options in this category yet. 
                                    <a href="{{ route('admin-pod-pricing-create') }}">Add one now</a>
                                </div>
                                @endif
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
