@extends('layouts.master')
@section('title',($isEdit) ? "Edit Page" : 'Add Page'.' | '.config('app.name'))

    @section('style')
        <link rel="stylesheet" type="text/css" href="{{ asset('') }}app-assets/vendors/css/editors/quill/katex.min.css">
        <link rel="stylesheet" type="text/css" href="{{ asset('') }}app-assets/vendors/css/editors/quill/monokai-sublime.min.css">
        <link rel="stylesheet" type="text/css" href="{{ asset('') }}app-assets/vendors/css/editors/quill/quill.snow.css">
        <link rel="stylesheet" type="text/css" href="{{ asset('') }}app-assets/vendors/css/editors/quill/quill.bubble.css">
        <style>
            .ql-editor {min-height: 200px;}
            .color-picker input{ height: 35px; }
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
                            <h2 class="content-header-title float-start mb-0"> {{ ($isEdit) ? "Edit Page" : 'Pages Colour Setting' }}</h2>
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
                                    <form class="form form-vertical" method="POST" action="{{ ($isEdit) ?  route('setting-update') : route('setting-update') }}" enctype="multipart/form-data" >
                                        @csrf
                                        <div class="row">

                                            @foreach ($pages as $key => $page)
                                            @php
                                               $data = (isset($settingData[$page])) ? $settingData[$page]->pluck('value','key')->toArray() : [];
                                            @endphp
                                                {{-- <input type="hidden" name="page[]" readonly value="{{ $page }}"> --}}
                                                <div class="col-md-3  col-12 color-picker">
                                                    <div class="mb-1 row">
                                                        <h3 class="col-12">{{ $key }}</h3>
                                                        <div class="col-6">
                                                            <label class="form-label" for="first-title-vertical" >Primary Color</label>
                                                            <input type="color" required id="first-title-vertical" class="form-control" value="{{ $data['primary_colour'] ?? "#006D5B" }}" name="{{ $page }}[primary_colour]" placeholder="title" />
                                                        </div>
                                                        <div class="col-6">
                                                            <label class="form-label" for="first-title-vertical" >Secondary color</label>
                                                            <input type="color" required id="first-title-vertical" class="form-control" value="{{ $data['secondary_colour'] ?? "#191970" }}" name="{{ $page }}[secondary_colour]" placeholder="title" />
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach



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

        // var container = document.getElementById('editor');
        // console.log(container)
        var container = $('.editor');
        $.each( container, function( key, value ) {
            var editor = new Quill(value);
            var quill = new Quill(value, {
                modules: {
                    imageResize: { displaySize: true }, // default false
                    toolbar: toolbarOptions,
                },
                theme: 'snow'
            });
        });

        function runQuill(id) {
            // alert(id)
            var container = document.getElementById(id);
            var editor = new Quill(container);
            var quill = new Quill(container, {
                modules: {
                    imageResize: { displaySize: true }, // default false
                    toolbar: toolbarOptions,
                },
                theme: 'snow'
            });
        }



        $("form.form").submit(function(eventObj){
        // $(".test").click(function(eventObj){
            var html = $('.ql-editor');
            $.each( html, function( key, value ) {
                $("<input />").attr("type", "hidden").attr("name", "content["+key+"]").attr("value", value.innerHTML).appendTo("form.form");
            });

            return true;
        });


        $(document).on("click",".section_remove", function (){
            $(this).parents('.section').remove();
        })

        $("#add_Section").click(function(e){
            var length = $('.section_parent').find(".section").length;
            count = length+1;
            console.log(length);

            var html = $('.section_parent').append(`
                <div class="section col-12 col-md-12 row">
                    <div class="col-md-7  col-12">
                        <div class="mb-1 mt-2">
                            <label class="form-label" for="first-title-vertical" >Order priority</label>
                            <input type="number" id="first-title-vertical" class="form-control" value="`+count+`" name="section_order[]" placeholder="eg:1" />
                        </div>
                    </div>

                    <div class="col-md-3  col-12">
                        <div class="mb-1 mt-2">
                            <label class="form-label" for="status">Section Status</label>
                            <select class="form-select" id="status" name="section_status[]" required>
                                <option value="1" {{ ($isEdit && $page?->status) ? 'selected' : '' }}>active</option>
                                <option value="0" {{ ($isEdit && !$page?->status) ? 'selected': '' }}>Inactive</option>
                            </select>
                            @error('status')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                        </div>
                    </div>

                    <div class="col-md-1  col-12">
                        <div class="mb-1 mt-2">
                            <label class="form-label" for="first-title-vertical" >Section Colour</label>
                            <input style="height:40px" type="color" id="first-title-vertical" class="form-control" value="#ffffff" name="section_colour[]"  />
                            @error('title')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                        </div>
                    </div>
                    <div class="col-md-1  col-1">
                        <button type="button" class="btn section_remove btn-icon btn-outline-danger waves-effect mt-4">
                            X
                        </button>
                    </div>
                    <div class="col-md-12  col-12">
                        <div class="mb-1">
                            {{-- <h3 class="" for="content">Sections</h3> --}}
                            <div id="full-container">
                                <div class="editor" id="editor_`+count+`">{!! ($isEdit) ? $page?->content : old('content')  !!}</div>
                            </div>
                        </div>
                    </div>
                </div>`);

                runQuill("editor_"+count);

        });

        // var returnHtml = `{{ ($isEdit) ? $page?->content : old('content')  }}`;
        // $('.ql-editor').html(returnHtml);

        // <p>We offer solutions that enhance your life!</p>
    </script>


@endsection

