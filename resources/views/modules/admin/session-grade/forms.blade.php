@extends('layouts.master')
@section('title',($isEdit) ? "Edit Grade" : 'Add Grade'.' | '.config('app.name'))

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
                            <h2 class="content-header-title float-start mb-0"> {{ ($isEdit) ? "Edit Grade" : 'Add Grade' }}</h2>
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
                                    <form class="form form-vertical" method="POST" action="{{ ($isEdit) ?  route('grade-update', $id) : route('grade-store') }}" enctype="multipart/form-data" >
                                        @csrf
                                        <div class="row">
                                            <div class="col-md-6  col-12">
                                                <div class="mb-1">
                                                    <label class="form-label" for="first-name-vertical" >Name</label>
                                                    <input type="text" id="first-name-vertical" class="form-control" value="{{ ($isEdit) ? $blog?->name : old('name')  }}" name="name" placeholder="name" />
                                                    @error('name')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                                                </div>
                                            </div>

                                            <div class="col-md-6  col-12">
                                                <div class="mb-1">
                                                    <label class="form-label" for="status">Session Status</label>
                                                    <select class="form-select" id="status" name="status" required>
                                                        <option value="1" {{ ($isEdit && $blog?->status) ? 'selected' : '' }}>active</option>
                                                        <option value="0" {{ ($isEdit && !$blog?->status) ? 'selected': '' }}>Inactive</option>
                                                    </select>
                                                    @error('status')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                                                </div>
                                            </div>

                                            {{-- <div class="col-md-10  col-12">
                                                <div class="mb-1">
                                                    <label class="form-label" for="description">Description</label>
                                                    <textarea class="form-control" name="description" id="" rows="7">{{ ($isEdit) ? $blog?->description : old('description')  }}</textarea>
                                                    @error('description')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                                                </div>
                                            </div>

                                            <div class="col-md-2">
                                                <div class="mb-1">
                                                    <label class="form-label" for="website-vertical">Banner image</label>
                                                    <div class="avatar-upload">
                                                        <div class="avatar-edit">
                                                            <input type='file' id="imageUpload" name="image" accept="image/png, image/jpeg" />
                                                            <label for="imageUpload">
                                                                <i data-feather='edit' style="width: 33px; height: 29px;"></i>
                                                            </label>
                                                        </div>
                                                        <div class="avatar-preview">
                                                            <div id="imagePreview" style="background-image: url({{ asset( (isset($blog?->image_url)) ? $blog?->image_url : 'assets/images/no-preview.png' ) }});">
                                                            </div>
                                                        </div>
                                                        @error('image')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                                                    </div>

                                                </div>
                                            </div> --}}

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

@endsection

