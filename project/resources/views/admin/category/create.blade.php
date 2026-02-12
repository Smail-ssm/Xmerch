@extends('layouts.load')

@section('content')

            <div class="content-area">

              <div class="add-product-content1">
                <div class="row">
                  <div class="col-lg-12">
                    <div class="product-description">
                      <div class="body-area">
                        @include('alerts.admin.form-error')  
                        <form id="geniusformdata" action="{{route('admin-cat-create')}}" method="POST" enctype="multipart/form-data">
                          {{csrf_field()}}

                          <div class="row">
                            <div class="col-lg-4">
                              <div class="left-area">
                                <h4 class="heading">{{ __('Select Language') }}*</h4>
                              </div>
                            </div>
                            <div class="col-lg-7">
                              <select name="language_id" required="">
                                  @foreach(DB::table('languages')->get() as $ldata)
                                  <option value="{{ $ldata->id }}">{{ $ldata->language }}</option>
                                  @endforeach
                                </select>
                            </div>
                            </div>


                          <div class="row">
                            <div class="col-lg-4">
                              <div class="left-area">
                                  <h4 class="heading">{{ __('Name') }} *</h4>
                                  <p class="sub-heading">{{ __('(In Any Language)') }}</p>
                              </div>
                            </div>
                            <div class="col-lg-7">
                              <input type="text" class="input-field" name="name" placeholder="{{ __('Enter Name') }}" required="" value="">
                            </div>
                          </div>

                          <div class="row">
                            <div class="col-lg-4">
                              <div class="left-area">
                                  <h4 class="heading">{{ __('Slug') }} *</h4>
                                  <p class="sub-heading">{{ __('In English') }}</p>
                              </div>
                            </div>
                            <div class="col-lg-7">
                              <input type="text" class="input-field" name="slug" placeholder="{{ __('Enter Slug') }}" required="" value="">
                            </div>
                          </div>

                          <div class="row">
                            <div class="col-lg-4">
                              <div class="left-area">
                                  <h4 class="heading">{{ __('Set Icon') }} *</h4>
                              </div>
                            </div>
                            <div class="col-lg-7">
                              <div class="img-upload">
                                  <div id="image-preview" class="img-preview" style="background: url({{ asset('assets/admin/images/upload.png') }});">
                                      <label for="image-upload" class="img-label" id="image-label"><i class="icofont-upload-alt"></i>{{ __('Upload Icon') }}</label>
                                      <input type="file" name="photo" class="img-upload" id="image-upload">
                                    </div>
                              </div>

                            </div>
                          </div>


                            <div class="row">
                              <div class="col-lg-4">
                                <div class="left-area">
                                  <h4 class="heading">{{ __('Set Banner') }} *</h4>
                                </div>
                              </div>
                              <div class="col-lg-7">
                                <div class="img-upload full-width-img">
                                  <div id="image-preview" class="img-preview" style="background: url({{ asset('assets/admin/images/upload.png') }});">
                                    <label for="image-upload" class="img-label"><i class="icofont-upload-alt"></i>{{ __('Upload Banner') }}</label>
                                    <input type="file" name="image" class="img-upload">
                                  </div>
                                  <p class="text">{{__('Prefered Size: (1230x267) or Square Sized Image')}}</p>
                                </div>
                              </div>
                            </div>

                        <hr>
                        <h4 class="text-center">{{ __('Category Theme (Niche Branding)') }}</h4>
                        <hr>

                        <div class="row">
                          <div class="col-lg-4">
                            <div class="left-area">
                                <h4 class="heading">{{ __('Primary Color') }}</h4>
                            </div>
                          </div>
                          <div class="col-lg-7">
                            <input type="color" name="theme_config[primary_color]" class="input-field" style="width: 100px; height: 40px; padding: 5px;" value="#000000">
                          </div>
                        </div>

                        <div class="row">
                          <div class="col-lg-4">
                            <div class="left-area">
                                <h4 class="heading">{{ __('Background Color') }}</h4>
                            </div>
                          </div>
                          <div class="col-lg-7">
                            <input type="color" name="theme_config[bg_color]" class="input-field" style="width: 100px; height: 40px; padding: 5px;" value="#ffffff">
                          </div>
                        </div>

                        <div class="row">
                          <div class="col-lg-4">
                            <div class="left-area">
                                <h4 class="heading">{{ __('Font Family') }}</h4>
                                <p class="sub-heading">{{ __('(e.g. "Bangers", cursive)') }}</p>
                            </div>
                          </div>
                          <div class="col-lg-7">
                            <input type="text" name="theme_config[font_family]" class="input-field" placeholder="e.g. 'Bangers', cursive" value="">
                          </div>
                        </div>

                        <div class="row">
                          <div class="col-lg-4">
                            <div class="left-area">
                                <h4 class="heading">{{ __('Font Value') }}</h4>
                                <p class="sub-heading">{{ __('(Google Font Name)') }}</p>
                            </div>
                          </div>
                          <div class="col-lg-7">
                            <input type="text" name="theme_config[font_value]" class="input-field" placeholder="e.g. Bangers" value="">
                          </div>
                        </div>

                        <div class="row">
                          <div class="col-lg-4">
                            <div class="left-area">
                                <h4 class="heading">{{ __('Global Styles') }}</h4>
                                <p class="sub-heading">{{ __('(Body, Backgrounds, Global Fonts)') }}</p>
                            </div>
                          </div>
                          <div class="col-lg-7">
                            <textarea name="theme_config[global_css]" class="input-field" placeholder="e.g. body { background: #f0f0f0; }"></textarea>
                          </div>
                        </div>

                        <div class="row">
                          <div class="col-lg-4">
                            <div class="left-area">
                                <h4 class="heading">{{ __('Header CSS') }}</h4>
                                <p class="sub-heading">{{ __('(Navbar, Logo, Top Bar)') }}</p>
                            </div>
                          </div>
                          <div class="col-lg-7">
                            <textarea name="theme_config[header_css]" class="input-field" placeholder="e.g. .navbar { padding: 20px; }"></textarea>
                          </div>
                        </div>

                        <div class="row">
                          <div class="col-lg-4">
                            <div class="left-area">
                                <h4 class="heading">{{ __('Product Card CSS') }}</h4>
                                <p class="sub-heading">{{ __('(Items Grid, Image Borders, Price tags)') }}</p>
                            </div>
                          </div>
                          <div class="col-lg-7">
                            <textarea name="theme_config[product_card_css]" class="input-field" placeholder="e.g. .product-item { box-shadow: 10px 10px 0px #000; }"></textarea>
                          </div>
                        </div>

                        <div class="row">
                          <div class="col-lg-4">
                            <div class="left-area">
                                <h4 class="heading">{{ __('Banner CSS') }}</h4>
                                <p class="sub-heading">{{ __('(Main Category/Shop Banner overlay)') }}</p>
                            </div>
                          </div>
                          <div class="col-lg-7">
                            <textarea name="theme_config[banner_css]" class="input-field" placeholder="e.g. .banner-title { font-size: 50px; }"></textarea>
                          </div>
                        </div>

                        <div class="row">
                          <div class="col-lg-4">
                            <div class="left-area">
                                <h4 class="heading">{{ __('Footer CSS') }}</h4>
                            </div>
                          </div>
                          <div class="col-lg-7">
                            <textarea name="theme_config[footer_css]" class="input-field" placeholder="e.g. .footer { border-top: 5px solid red; }"></textarea>
                          </div>
                        </div>



                          <br>
                          <div class="row">
                            <div class="col-lg-4">
                              <div class="left-area">
                                
                              </div>
                            </div>
                            <div class="col-lg-7">
                              <button class="addProductSubmit-btn" type="submit">{{ __('Create Category') }}</button>
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