@extends('layouts.vendor')

@section('content')

<div class="content-area">
            <div class="mr-breadcrumb">
              <div class="row">
                <div class="col-lg-12">
                    <h4 class="heading">{{ __("Add Product") }}</h4>
                    <ul class="links">
                      <li>
                        <a href="{{ route('vendor.dashboard') }}">{{ __("Dashboard") }}</a>
                      </li>
                      <li>
                        <a href="javascript:;">{{ __("Products") }} </a>
                      </li>
                      <li>
                        <a href="{{ route('vendor-prod-index') }}">{{ __("All Products") }}</a>
                      </li>
                      <li>
                        <a href="{{ route('vendor-prod-types') }}">{{ __("Add Product") }}</a>
                      </li>
                    </ul>
                </div>
              </div>
            </div>
            <div class="add-product-content">
              <div class="row">
                <div class="col-lg-12">
                  <div class="product-description">
                    <div class="heading-area">
                      <h2 class="title">
                          {{ __("Product Types") }}
                      </h2>
                    </div>
                  </div>
                </div>
              </div>
              <div class="ap-product-categories">
                <div class="row">
                  @if($gs->physical == 1)
                  <div class="col-lg-3">
                    <a href="{{ route('vendor-prod-create', ['slug' => 'physical', 'mode' => 'pod']) }}">
                    <div class="cat-box box1">
                      <div class="icon">
                        <i class="fas fa-tshirt"></i>
                      </div>
                      <h5 class="title">{{ __("Physical (Standard)") }} </h5>
                    </div>
                    </a>
                  </div>

                  <div class="col-lg-3">
                    <a href="{{ route('vendor-prod-create', ['slug' => 'physical', 'mode' => 'pod']) }}">
                    <div class="cat-box box4" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                      <div class="icon">
                        <i class="fas fa-paint-brush" style="color: white;"></i>
                      </div>
                      <h5 class="title" style="color: white;">{{ __("Print on Demand") }} </h5>
                    </div>
                    </a>
                  </div>
                  @endif
                  @if($gs->digital == 1)
                  <div class="col-lg-4">
                    <a href="{{ route('vendor-prod-create','digital') }}">
                    <div class="cat-box box2">
                      <div class="icon">
                        <i class="fas fa-camera-retro"></i>
                      </div>
                      <h5 class="title">{{ __("Digital") }} </h5>
                    </div>
                    </a>
                  </div>
                  @endif
                  @if($gs->license == 1)
                  <div class="col-lg-4">
                    <a href="{{ route('vendor-prod-create','license') }}">
                    <div class="cat-box box3">
                      <div class="icon">
                        <i class="fas fa-award"></i>
                      </div>
                      <h5 class="title">{{ __("license") }} </h5>
                    </div>
                    </a>
                  </div>
                  @endif
                </div>
              </div>
            </div>
          </div>

@endsection