@extends('layouts.master')
@section('title',($isEdit) ? "Edit Course" : 'Add Course'.' | '.config('app.name'))
<style>
.pdf span.remove-pdf {
    background: black;
    padding: 0px;
    border-radius: 40px;
    color: white;
    padding: 2px;
    font-weight: bold;
    position: relative;
    left: 74px;
    bottom: 30px;
    cursor: pointer;
}
.pdf img {
    cursor: pointer;
}
</style>
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
                            <h2 class="content-header-title float-start mb-0"> {{ ($isEdit) ? "Edit Course" : 'Add Course' }}</h2>
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
                                    <form class="form form-vertical" method="POST" action="{{ ($isEdit) ?  route('courses-update', $id) : route('courses-store') }}" enctype="multipart/form-data" >
                                        @csrf
                                        <div class="row">
                                            <div class="col-md-4 col-12">
                                                <div class="mb-1">
                                                    <label class="form-label" for="first-title-vertical" >Title</label>
                                                    <input type="text" id="first-title-vertical" class="form-control" value="{{ ($isEdit) ? $course?->title : old('title')  }}" name="title" placeholder="title" />
                                                    @error('title')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                                                </div>
                                            </div>

                                            <div class="col-md-4  col-12">
                                                <div class="mb-1">
                                                    <label class="form-label" for="status">Course Status</label>
                                                    <select class="form-select" id="status" name="status" required>
                                                        <option value="1" {{ ($isEdit && $course?->status) ? 'selected' : '' }}>active</option>
                                                        <option value="0" {{ ($isEdit && !$course?->status) ? 'selected': '' }}>Inactive</option>
                                                    </select>
                                                    @error('status')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                                                </div>
                                            </div>

                                            <div class="col-md-4  col-12">
                                                <div class="mb-1">
                                                    @if (isset($course->pdf_url) && $course->pdf_url)
                                                        <div class="pdf">
                                                            <input type="hidden" name="nocpdf" value="1">
                                                            <span class="remove-pdf"><i data-feather='x'></i></span>
                                                            <a href="{{ asset($course?->pdf_url) }}"  download>
                                                                <img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAEAAAABACAYAAACqaXHeAAAACXBIWXMAAAsTAAALEwEAmpwYAAAFcElEQVR4nOWb/U8TZwDHLybbH7Nf/b2tMJHYK62USlAQoYpDlJdMxbkYp1HQbewlccvMfphMluFiZIb5El82p2zTxbBNQSZTodfSUnlpS3uF0va+y1Okx9GjcqXytNcn+Sakd/c83+/nueflSB6GUVj8VssVu0EHjtVmtGx6jZ/TawZsrLZjWK8tebB27RtKs8qGR6MV2QAgAQirHbQZdEZmpeGRpQBeQhA4vaYVR5g1isLz1s3d8+GzGUBcek1rSj0PtQAgb8NyhgO/qOfVBIBjtc/6LG+9qajnVQYAZHVQ1PNqA2BjNefkwi/Z82oDwOk1A4rDR+ur6BtPn3yKwhMFqi20TadVjJLw4bptcBjz1QWAX85rv3c7/FUlcBjzqBtOO4B7pvWQU3+R+sIuG0B/joTn5ADkUnhuMYBcC88tBJCL4bl5ALkaniMA+gy5G54jAGgbyBoArj3V4O/+siwFbl6F7/t2uPfXrcjcyPYSTH7RhuAfdzHdc1v2nheHmhAa6IMwOxtTmBuGq96afgCe44eQSpn5pxeOraaUAPCXu+L1RPv+TrjuPlgPRCIJbRKvaQfgPf4+Ui1h2xDsxesVAwhd/ykpgPDjR9KGolGA5+E52rwKAG5dBbovJOrna8Cjv+bMLCi+s2fSC8CgA0IhsYGHvQg1VIPfUQpXccHrBzC5txqj5oIEjW3eCE+5EZFjzYDPKwZwcGkF4NhqlPjhWw+nNMyYVAG436lIer+9SIfIpR/EBwQB9pLCJe8ngUYbdsbGtWt3Jeymt5MCcFaWSPxMHKjLLAAcq4W/7YTkmdGaLdLQZQZ4v/sGYdeIdHgRXkEewuSELICw04HIC7fk/ujEOCKjToSHn8Nuys8MAL7PWqXP7CyLXxttrEF0fAzLLQsBLJ5fJOB6/wTH6jIDQPDH8+IDkQiclg2x30cqzYh6PVLnAT/wb39sMsPQUyA8uzQA2xAwYpc+P+oEuGFMf34yM4aAq7YSAh8QHxh6KoK51r0gWRTo6kRw11Z4t5liE6ivshihxh1zMGQAkOuBBqvET/BYc+x3JSsAtxIA/oud8J49k6Cpi50I3usBZqU9OHvu67m6jHnAdFB8ZW9cjq0ecm3OXr8sCyAGuNIsqX9i/25FwVcMQFHhhvFiCxurx920S3LJt792yTaTrQLZA+DZIPjGHWI9pz4QrwkCnCUbshTA8/+AJ/3yIjvBOzchfPUpfNvNknqmTn8k1sEHks7YGQ0gsK8W3gqTrDzlRRgr3QhH0bqEevxftomVBINJ28xoAO5lLINymvqkRTIEyKSYUwA87zVI69knv4UlX4+RwQH1AXBaCoEgH69n5s6thHtGqjYjNCD91FUNAI7VInpDXN9JCf52G2MthzF+6ij8Vy5BWPiZOw9gcEA9ADxWy9zW9VXF7Yr/KXg96gHAke1w006g/6F88OkghPPtmD52UNrmgT30AIxbSwGyhL2Uq1S/IgCxN6HChOjHR4ErXcD9HuD3X4ELHZh5twYucwGcm/KB0x/G2/Q0iBsqp7lA4sddbny9AOwGHRxFeXEp+eR8Vb1uSyEmyliMl7FwbZL+79BhXBdv025YuK9Y7Ce19pl0hMhmMbQN0BZD2wBtMbQN0BZD2wBtMbQN0BZD2wBtMbQN0BZD2wBtMbQN0BZj02unaJugJ42XsbHaJ/SNUNNjAqAjA4zQUjtDjo1kgBEqsrM6M0NOVJJDhbTNrLbI0H8wf5qUHCGLHSrMnfCCjdUYJMdlOFZ7kraxVdSJhMNSOMKsIScq1d7zHKtpSXp01jY3HAbVOOYTXntmiUImB7I6kHN1ZK2MHUXPgBCKAs9t8B5zes23ZLZf6vj8/xwVN8WnxAj0AAAAAElFTkSuQmCC">
                                                            </a>
                                                        </div>
                                                    @endif
                                                    <div class="add-pdf {{ (isset($course->pdf_url) && $course->pdf_url) ? 'd-none' : ''  }}">
                                                        <label class="form-label" for="Pdf" >PDF document</label>
                                                        <input type="file" id="Pdf" class="form-control" accept="application/pdf," name="pdf_document" />
                                                        @error('pdf_document')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                                                    </div>
                                                </div>
                                            </div>

                                             <div class="col-md-4 col-12">
                                                <div class="mb-1">
                                                    <label class="form-label" for="courses_code">Courses Code</label>
                                                    <input type="text" id="courses_code" class="form-control" value="{{ ($isEdit) ? $course?->courses_code : old('courses_code')  }}" name="courses_code" placeholder="Courses Code" />
                                                    @error('courses_code')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                                                </div>
                                            </div>

                                            <div class="col-md-6  col-12">
                                                <div class="mb-1">
                                                    <label class="form-label" for="description">Description</label>
                                                    <textarea class="form-control" name="description" id="" rows="8">{{ ($isEdit) ? $course?->description : old('description')  }}</textarea>
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
                                                            <div id="imagePreview" style="background-image: url({{ asset( (isset($course?->image_id)) ? $course?->image_id : 'assets/images/no-preview.png' ) }});">
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
    $('.remove-pdf').click( function(params) {
        $(this).parent('.pdf').remove();
        $('.add-pdf').removeClass('d-none');
    })
</script>

@endsection

