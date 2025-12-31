@extends('layouts.admin')

@section('content')
<div class="content-area">
    <div class="mr-breadcrumb">
        <div class="row">
            <div class="col-lg-12">
                <h4 class="heading">{{ __('Add Pricing Option') }}
                    <a class="add-btn" href="{{ route('admin-pod-pricing-index') }}">
                        <i class="fas fa-arrow-left"></i> {{ __('Back') }}
                    </a>
                </h4>
            </div>
        </div>
    </div>

    <div class="add-product-content">
        <div class="row">
            <div class="col-lg-8">
                <div class="product-description">
                    <div class="body-area">
                        @include('alerts.admin.form-both')
                        
                        <form action="{{ route('admin-pod-pricing-store') }}" method="POST">
                            @csrf
                            
                            <div class="row mb-3">
                                <div class="col-lg-6">
                                    <label class="form-label">{{ __('Category') }} *</label>
                                    <select name="category" class="form-control" required>
                                        @foreach($categories as $key => $name)
                                        <option value="{{ $key }}">{{ $name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-lg-6">
                                    <label class="form-label">{{ __('Sort Order') }}</label>
                                    <input type="number" name="sort_order" class="form-control" value="0">
                                </div>
                            </div>
                            
                            <div class="row mb-3">
                                <div class="col-lg-6">
                                    <label class="form-label">{{ __('Display Name') }} *</label>
                                    <input type="text" name="name" class="form-control" required
                                           placeholder="e.g. Premium DTG Print">
                                </div>
                                <div class="col-lg-6">
                                    <label class="form-label">{{ __('Value/Slug') }} *</label>
                                    <input type="text" name="value" class="form-control" required
                                           placeholder="e.g. premium_dtg">
                                </div>
                            </div>
                            
                            <div class="row mb-3">
                                <div class="col-lg-6">
                                    <label class="form-label">{{ __('Price ($)') }} *</label>
                                    <input type="number" name="price" class="form-control" required
                                           step="0.01" min="0" placeholder="0.00">
                                </div>
                                <div class="col-lg-6">
                                    <label class="form-label">{{ __('Status') }}</label>
                                    <select name="is_active" class="form-control">
                                        <option value="1">Active</option>
                                        <option value="0">Inactive</option>
                                    </select>
                                </div>
                            </div>
                            
                            <div class="row mb-3">
                                <div class="col-lg-12">
                                    <label class="form-label">{{ __('Description') }}</label>
                                    <textarea name="description" class="form-control" rows="2"
                                              placeholder="Brief description for designers"></textarea>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-lg-12">
                                    <button type="submit" class="mybtn1">
                                        <i class="fas fa-save"></i> {{ __('Save Option') }}
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
