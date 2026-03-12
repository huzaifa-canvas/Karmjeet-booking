@extends('layouts.master')
@section('title',($isEdit) ? "Edit Advertisement" : 'Add Advertisement'.' | '.config('app.name'))

@section('content')
    <style>
        img.image {
            max-width: 200px;
            max-height: 160px;
            border-radius: 10px;
            border: 1px solid #dbdbdb;
        }
        .hide_file {
           display: none;
        }

        .image_box label.icon {
            cursor: pointer;
            position: absolute;
            background: white;
            color: black;
            top: 7px;
            right: 7px;
            padding: 4px;
            border-radius: 10px;
            line-height: 0px;
        }

        .image_box {
            display: inline-block;
            position: relative;
        }
    </style>
    <!-- BEGIN: Content-->

    <div class="app-content content ">
        <div class="content-overlay"></div>
        <div class="header-navbar-shadow"></div>
        <div class="content-wrapper container-xxl p-0">
            <div class="content-header row">
                <div class="content-header-left col-md-9 col-12 mb-2">
                    <div class="row breadcrumbs-top">
                        <div class="col-12">
                            <h2 class="content-header-title float-start mb-0"> {{ ($isEdit) ? "Site information" : 'Site information' }}</h2>
                        </div>
                    </div>
                </div>
            </div>

            <div class="content-body">

                <!-- Basic Vertical form layout section start -->

                <section id="basic-vertical-layouts">
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-body">
                                    <form class="form form-vertical" method="POST" action="{{ ($isEdit) ?  route('page-update', $type) : route('page-update',$type) }}" enctype="multipart/form-data">
                                        @csrf
                                        <div class="row">

                                            <div class="col-md-6 col-12">
                                                <div class="mb-1 image_box">
                                                    <label class="form-label m-2 " for="header_logo" >Header Logo</label>
                                                    <label class="form-label icon" for="header_logo" ><i data-feather='edit'></i></label>
                                                    <img style="max-width: 100%" src="{{ asset('').getPageContent('site', 'header_logo') }}" class="image">
                                                    <input class="hide_file" id="header_logo" type="file" name="header_logo">
                                                </div>
                                            </div>

                                            <div class="col-md-6 col-12">
                                                <div class="mb-1 image_box">
                                                    <label class="form-label m-2 " for="footer_logo" >Footer Logo</label>
                                                    <label class="form-label icon" for="footer_logo" ><i data-feather='edit'></i></label>
                                                    <img style="max-width: 100%" src="{{ asset('').getPageContent('site', 'footer_logo') }}" class="image">
                                                    <input class="hide_file" id="footer_logo" type="file" name="footer_logo">
                                                </div>
                                            </div>


                                            <div class="col-md-6  col-12">
                                                <div class="mb-1">
                                                    <label class="form-label" for="email" >Email</label>
                                                    <input type="email"  class="form-control" name="email" value="{{ getPageContent('site','email') }}">
                                                    @error('email')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                                                </div>
                                            </div>

                                            <div class="col-md-6  col-12">
                                                <div class="mb-1">
                                                    <label class="form-label" for="phone" >phone</label>
                                                    <input type="tel"  class="form-control" name="phone" value="{{ getPageContent('site','phone') }}">
                                                    @error('phone')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                                                </div>
                                            </div>


                                            <div class="col-md-12  col-12">
                                                <div class="mb-1">
                                                    <label class="form-label" for="footer_text" >Footer Text</label>
                                                    <textarea class="form-control" name="footer_text" id="" cols="30" rows="2">{{ getPageContent('site','footer_text')  }}</textarea>
                                                    @error('footer_text')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                                                </div>
                                            </div>

                                            <div class="col-12">
                                                <h2 class="text-center m-3">Social link</h2>
                                            </div>

                                            <div class="col-md-4  col-12">
                                                <div class="mb-1">
                                                    <label class="form-label" for="facebook" >Facebook</label>
                                                    <input type="url"  class="form-control" name="facebook" value="{{ getPageContent('site','facebook') }}">
                                                    @error('facebook')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                                                </div>
                                            </div>

                                            <div class="col-md-4  col-12">
                                                <div class="mb-1">
                                                    <label class="form-label" for="linkedin" >Linkedin</label>
                                                    <input type="url"  class="form-control" name="linkedin" value="{{ getPageContent('site','linkedin') }}">
                                                    @error('linkedin')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                                                </div>
                                            </div>

                                            <div class="col-md-4  col-12">
                                                <div class="mb-1">
                                                    <label class="form-label" for="instagram" >Instagram</label>
                                                    <input type="url"  class="form-control" name="instagram" value="{{ getPageContent('site','instagram') }}">
                                                    @error('instagram')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                                                </div>
                                            </div>
                                            <div class="col-md-4  col-12">
                                                <div class="mb-1">
                                                    <label class="form-label" for="tiktok" >Tiktok</label>
                                                    <input type="url"  class="form-control" name="tiktok" value="{{ getPageContent('site','tiktok') }}">
                                                    @error('tiktok')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                                                </div>
                                            </div>
                                            <div class="col-md-4  col-12">
                                                <div class="mb-1">
                                                    <label class="form-label" for="youtube" >YouTube</label>
                                                    <input type="url"  class="form-control" name="youtube" value="{{ getPageContent('site','youtube') }}">
                                                    @error('youtube')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                                                </div>
                                            </div>

                                            <div class="col-12">
                                                <button type="submit" class="btn btn-primary me-1">Submit</button>
                                                <button type="reset" class="btn btn-outline-secondary">Reset</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>

                    </div>
                </section>

                <!-- Basic Vertical form layout section end -->

            </div>
        </div>
    </div>

    <!-- END: Content-->

@endsection

@section('scripts')

<script>

        $(document).on('change', 'input[type=file]', function (e) {
            e.preventDefault();
            readURL(this);
        })

        function readURL(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();

                reader.onload = function (e) {
                    $(input).siblings('img').attr('src', e.target.result);
                }
                reader.readAsDataURL(input.files[0]);
            }
        }

</script>

@endsection

