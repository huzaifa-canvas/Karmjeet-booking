@extends('layouts.master')
@section('title',($isEdit) ? "Edit Advertisement" : 'Add Advertisement'.' | '.config('app.name'))

@section('style')
    <link rel="stylesheet" type="text/css" href="{{ asset('') }}app-assets/vendors/css/editors/quill/katex.min.css">
    <link rel="stylesheet" type="text/css" href="{{ asset('') }}app-assets/vendors/css/editors/quill/monokai-sublime.min.css">
    <link rel="stylesheet" type="text/css" href="{{ asset('') }}app-assets/vendors/css/editors/quill/quill.snow.css">
    <link rel="stylesheet" type="text/css" href="{{ asset('') }}app-assets/vendors/css/editors/quill/quill.bubble.css">
    <style>
        .ql-editor {min-height: 200px;}

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
@endsection

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
                            <h2 class="content-header-title float-start mb-0"> {{ ($isEdit) ? "About Content" : 'About Content' }}</h2>
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

                                            <div class="col-md-2 col-12">
                                                <div class="mb-1 image_box">
                                                    <label class="form-label m-2 " for="about_image" >Image</label>
                                                    <label class="form-label icon" for="about_image" ><i data-feather='edit'></i></label>
                                                        <img src="{{ asset('').getPageContent('about', 'about_image') }}" class="image">
                                                    <input class="hide_file" id="about_image" type="file" name="about_image">
                                                </div>
                                            </div>

                                            <div class="col-md-4  col-12">
                                                <div class="mb-1">
                                                    <label class="form-label" for="heading" >Heading</label>
                                                    <textarea class="form-control" name="heading" id="" cols="30" rows="8">{{ getPageContent('about','heading')  }}</textarea>
                                                    @error('heading')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                                                </div>
                                            </div>

                                            <div class="col-md-6  col-12">
                                                <div class="mb-1">
                                                    <label class="form-label" for="text" >Text</label>
                                                    <textarea class="form-control" name="text" id="" cols="30" rows="8">{{ getPageContent('about','text')  }}</textarea>
                                                    @error('text')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                                                </div>
                                            </div>

                                            <div class="col-md-12  col-12">
                                                <div class="mb-1">
                                                    <h3 class="" for="content">Our history</h3>
                                                    <div id="full-container">
                                                        <div id="editor">{!! getPageContent('about','our_history') !!}</div>
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

 <!-- BEGIN: Page Vendor JS-->
 <script src="{{ asset('') }}app-assets/vendors/js/editors/quill/katex.min.js"></script>
 <script src="{{ asset('') }}app-assets/vendors/js/editors/quill/highlight.min.js"></script>
 <script src="{{ asset('') }}app-assets/vendors/js/editors/quill/quill.min.js"></script>
 <script src="{{ asset('') }}app-assets/vendors/js/editors/quill/image-resize.min.js"></script>
 <!-- END: Page Vendor JS-->

 <!-- BEGIN: Page JS-->
 {{-- <script src="{{ asset('') }}app-assets/js/scripts/forms/form-quill-editor.js"></script> --}}
 <!-- END: Page JS-->

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

<script>


    var container = document.getElementById('editor');
    var editor = new Quill(container);

    var toolbarOptions = [
        ['bold', 'italic', 'underline', 'strike'],        // toggled buttons
        ['blockquote', 'code-block'],

        [{ 'header': 1 }, { 'header': 2 }],               // custom button values
        [{ 'list': 'ordered'}, { 'list': 'bullet' }],
        [{ 'script': 'sub'}, { 'script': 'super' }],      // superscript/subscript
        [{ 'indent': '-1'}, { 'indent': '+1' }],          // outdent/indent
        [{ 'direction': 'rtl' }],                         // text direction

        [{ 'size': ['small', false, 'large', 'huge'] }],  // custom dropdown
        [{ 'header': [1, 2, 3, 4, 5, 6, false] }],

        [{ 'color': [] }, { 'background': [] }],          // dropdown with defaults from theme
        [{ 'font': [] }],
        [{ 'align': [] }],

        ['clean'],                                         // remove formatting button
        ['link', 'image'],

    ];

    var quill = new Quill('#editor', {
        modules: {
            imageResize: { displaySize: true }, // default false
            toolbar: toolbarOptions,
        },
        theme: 'snow'
    });

    $("form.form").submit(function(eventObj){
        var html = $('.ql-editor').html();
        $("<input />").attr("type", "hidden").attr("name", "our_history").attr("value", html).appendTo("form.form");
        return true;
    });

    // var returnHtml = `{{ ($isEdit) ? $page?->content : old('content')  }}`;
    // $('.ql-editor').html(returnHtml);

    // <p>We offer solutions that enhance your life!</p>
</script>

@endsection

