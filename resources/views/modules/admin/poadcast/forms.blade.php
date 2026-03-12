@extends('layouts.master')
@section('title',($isEdit) ? "Edit Podcast" : 'Add Podcast'.' | '.config('app.name'))

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
                            <h2 class="content-header-title float-start mb-0"> {{ ($isEdit) ? "Edit Podcast" : 'Add Podcast' }}</h2>
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
                                    <form class="form form-vertical" method="POST" action="{{ ($isEdit) ?  route('poadcast-update', $id) : route('poadcast-store') }}" enctype="multipart/form-data" >
                                        @csrf
                                        <div class="row">
                                            <div class="col-md-4  col-12">
                                                <div class="mb-1">
                                                    <label class="form-label" for="first-title-vertical" >Title</label>
                                                    <input type="text" id="first-title-vertical" class="form-control" value="{{ ($isEdit) ? $poadcast?->title : old('title')  }}" name="title" placeholder="Title" />
                                                    @error('title')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                                                </div>
                                            </div>

                                            <div class="col-md-4  col-12">
                                                <div class="mb-1">
                                                    <label class="form-label" for="status">Session Status</label>
                                                    <select class="form-select" id="status" name="status" required>
                                                        <option value="1" {{ ($isEdit && $poadcast?->status) ? 'selected' : '' }}>active</option>
                                                        <option value="0" {{ ($isEdit && !$poadcast?->status) ? 'selected': '' }}>Inactive</option>
                                                    </select>
                                                    @error('status')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                                                </div>
                                            </div>

                                            <div class="col-md-4  col-12">
                                                <div class="mb-1">
                                                    <label class="form-label" for="audio" >Audio</label>
                                                    <input type="file" id="audio" class="form-control" accept="audio/*" name="audio" />
                                                    @error('audio')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                                                </div>
                                            </div>

                                            <div class="col-md-10  col-12">
                                                <div class="mb-1">
                                                    <label class="form-label" for="description">Description</label>
                                                    <textarea class="form-control" name="description" id="" rows="7">{{ ($isEdit) ? $poadcast?->description : old('description')  }}</textarea>
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
                                                            <div id="imagePreview" style="background-image: url({{ asset( (isset($poadcast?->image_url)) ? $poadcast?->image_url : 'assets/images/no-preview.png' ) }});">
                                                            </div>
                                                        </div>
                                                        @error('image')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror

                                                    </div>

                                                </div>
                                            </div>

                                            <div class="col-md-4  col-12">
                                                <div class="mb-1">
                                                    <label class="form-label" for="itunes" >iTunes link</label>
                                                    <input type="url" id="itunes" class="form-control" value="{{ ($isEdit) ? $poadcast?->links?->itunes?->link : old('itunes')  }}" name="links[itunes][link]" placeholder="itunes Link" />
                                                    <input type="hidden" value="iTunes" name="links[itunes][name]">
                                                    @error('itunes')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                                                </div>
                                            </div>
                                            <div class="col-md-4  col-12">
                                                <div class="mb-1">
                                                    <label class="form-label" for="stitcher" >Stitcher link</label>
                                                    <input type="url" id="stitcher" class="form-control" value="{{ ($isEdit) ? $poadcast?->links?->stitcher?->link : old('stitcher')  }}" name="links[stitcher][link]" placeholder="stitcher Link" />
                                                    <input type="hidden" value="Stitcher" name="links[stitcher][name]">

                                                    @error('stitcher')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                                                </div>
                                            </div>
                                            <div class="col-md-4  col-12">
                                                <div class="mb-1">
                                                    <label class="form-label" for="soundcloud" >Soundcloud link</label>
                                                    <input type="url" id="soundcloud" class="form-control" value="{{ ($isEdit) ? $poadcast?->links?->soundcloud?->link : old('soundcloud')  }}" name="links[soundcloud][link]" placeholder="soundcloud Link" />
                                                    <input type="hidden" value="Sound Cloud" name="links[soundcloud][name]">
                                                    @error('soundcloud')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                                                </div>
                                            </div>
                                            <div class="col-md-4  col-12">
                                                <div class="mb-1">
                                                    <label class="form-label" for="android" >Android</label>
                                                    <input type="url" id="android" class="form-control" value="{{ ($isEdit) ? $poadcast?->links?->android?->link : old('android')  }}" name="links[android][link]" placeholder="android Link" />
                                                    <input type="hidden" value="Android" name="links[android][name]">
                                                    @error('android')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                                                </div>
                                            </div>
                                            <div class="col-md-4  col-12">
                                                <div class="mb-1">
                                                    <label class="form-label" for="google" >Google</label>
                                                    <input type="url" id="google" class="form-control" value="{{ ($isEdit) ? $poadcast?->links?->google?->link : old('google')  }}" name="links[google][link]" placeholder="google Link" />
                                                    <input type="hidden" value="Google" name="links[google][name]">
                                                    @error('google')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                                                </div>
                                            </div>
                                            <div class="col-md-4  col-12">
                                                <div class="mb-1">
                                                    <label class="form-label" for="apple" >Apple</label>
                                                    <input type="url" id="apple" class="form-control" value="{{ ($isEdit) ? $poadcast?->links?->apple?->link : old('apple')  }}" name="links[apple][link]" placeholder="apple Link" />
                                                    <input type="hidden" value="Apple" name="links[apple][name]">
                                                    @error('apple')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
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

@endsection

