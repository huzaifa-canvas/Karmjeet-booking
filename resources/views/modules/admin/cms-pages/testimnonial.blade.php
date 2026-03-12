@extends('layouts.master')
@section('title',($isEdit) ? "Edit Advertisement" : 'Add Advertisement'.' | '.config('app.name'))

@section('content')

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
                                    <form class="form form-vertical" method="POST" action="{{ ($isEdit) ?  route('testimnonial-update', $id) : route('testimnonial-store') }}" enctype="multipart/form-data" >
                                        @csrf
                                        <div class="row">
                                            <div class="col-md-6  col-12">
                                                <div class="mb-1">
                                                    <label class="form-label" for="name" >Name</label>
                                                    <input type="text" id="name" class="form-control" value="{{ ($isEdit) ? $testimnonial?->name : old('name')  }}" name="name" placeholder="Name" />
                                                    @error('Name')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                                                </div>
                                            </div>
                                            <div class="col-md-6  col-12">
                                                <div class="mb-1">
                                                    <label class="form-label" for="designation" >Designation</label>
                                                    <input type="text" id="designation" class="form-control" value="{{ ($isEdit) ? $testimnonial?->designation : old('designation')  }}" name="designation" placeholder="designation" />
                                                    @error('designation')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                                                </div>
                                            </div>

                                            <div class="col-md-10  col-12">
                                                <div class="mb-1">
                                                    <label class="form-label" for="description">Description</label>
                                                    <textarea class="form-control" name="description" id="" rows="7">{{ ($isEdit) ? $testimnonial?->description : old('description')  }}</textarea>
                                                    @error('description')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                                                </div>
                                            </div>

                                            <div class="col-md-2">
                                                <div class="mb-1">
                                                    <label class="form-label" for="website-vertical">image</label>
                                                    <div class="avatar-upload">
                                                        <div class="avatar-edit">
                                                            <input type='file' id="imageUpload" name="user_image" accept="image/png, image/jpeg" />
                                                            <label for="imageUpload">
                                                                <i data-feather='edit' style="width: 33px; height: 29px;"></i>
                                                            </label>
                                                        </div>
                                                        <div class="avatar-preview">
                                                            <div id="imagePreview" style="background-image: url({{ asset( (isset($testimnonial?->image)) ? $testimnonial?->image : 'assets/images/no-preview.png' ) }});">
                                                            </div>
                                                        </div>
                                                        @error('image')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror

                                                    </div>

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
            reader.onload = function(e) {
                $('#imagePreview').css('background-image', 'url('+e.target.result +')');
                $('#imagePreview').hide();
                $('#imagePreview').fadeIn(650);
            }
            reader.readAsDataURL(input.files[0]);
        }
    }

</script>

@endsection

