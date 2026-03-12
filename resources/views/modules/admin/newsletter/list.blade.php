
@extends('layouts.master')
@section('title','News Letter List | '.config('app.name'))

@section('style')
    <link rel="stylesheet" type="text/css" href="{{ asset('') }}app-assets/vendors/css/vendors.min.css">
    <link rel="stylesheet" type="text/css" href="{{ asset('') }}app-assets/vendors/css/tables/datatable/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" type="text/css" href="{{ asset('') }}app-assets/vendors/css/tables/datatable/responsive.bootstrap5.min.css">
    <link rel="stylesheet" type="text/css" href="{{ asset('') }}app-assets/vendors/css/tables/datatable/buttons.bootstrap5.min.css">
    {{-- <link rel="stylesheet" type="text/css" href="{{ asset('') }}app-assets/vendors/css/tables/datatable/rowGroup.bootstrap5.min.css"> --}}
    {{-- <link rel="stylesheet" type="text/css" href="{{ asset('') }}app-assets/vendors/css/pickers/flatpickr/flatpickr.min.css"> --}}
    <style>
        .update_status{
            padding: 4px;
            border-color: #dfdde4;
            border-radius: 6px;
            color: #605e5e;
        }

        .dt-buttons button {
            border: 1px solid #82868b !important;
            background-color: transparent;
            color: #82868b;
            padding: 0.386rem 1.2rem;
            font-weight: 500;
            font-size: 1rem;
            border-radius: 0.358rem;
        }
        .dt-buttons button:hover {
            color: #fff;
            background-color: #7367f0;
            border-color: #7367f0;
        }
        button.dt-button.add-new.btn.btn-primary {
            padding: 10px;
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
                            <h2 class="content-header-title float-start mb-0">News Letter List</h2>
                        </div>
                    </div>
                </div>
            </div>

            <div class="content-body">

                <!-- Basic table -->
                <section id="basic-datatable">

                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <table class="datatables-basic table">
                                    <thead>
                                        <tr>
                                            <th></th>
                                            <th>id</th>
                                            <th>Email</th>
                                            <th>Query Date</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($newsletters as $newsletter)

                                        <tr>
                                            <td id="{{ $newsletter?->email }}"></td>
                                            <td>{{ $newsletter->id }}</td>
                                            <td>
                                                <div class="d-flex justify-content-left align-items-center">
                                                    {{-- <div class="avatar  me-1"><img src="{{ asset( ($newsletter?->image_id) ? $newsletter?->image_id : 'assets/images/no-preview.png' ) }}" alt="Avatar" width="32" height="32"></div> --}}
                                                    <div class="d-flex flex-column">
                                                        <span class="emp_name text-truncate fw-bold">{{ $newsletter?->email }}</span>
                                                        {{-- <small class="emp_post text-truncate text-muted">{{ $newsletter?->getnewsletter?->email }}</small> --}}
                                                    </div>
                                                </div>
                                            </td>
                                            <td>{{ $newsletter->created_at->format('d-M-Y') }}</td>
                                        </tr>

                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <div class="modal fade" id="editUser" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog modal-lg modal-dialog-centered modal-edit-user">
                            <div class="modal-content">
                                <div class="modal-header bg-transparent">
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body pb-5 px-sm-5 pt-50">
                                    <div class="text-center mb-2">
                                        <h1 class="mb-1">Fill Email Content</h1>
                                        {{-- <p>Updating user details will receive a privacy audit.</p> --}}
                                    </div>
                                    <form id="postEmail" class="row gy-1 pt-75">
                                        <div class="col-12 col-md-12">
                                            <label class="form-label" for="subject">Subject</label>
                                            <input required type="text" id="subject" name="subject" class="form-control" placeholder="Enter Subjent" value="" />
                                        </div>

                                        <div class="col-12 col-md-12">
                                            <label class="form-label" for="modalEditUserPhone">Content</label>
                                            <textarea required name="content" class="form-control" id="" cols="30" rows="10"></textarea>
                                        </div>

                                        <div class="col-12 text-center mt-2 pt-50">
                                            <button type="submit" class="btn btn-primary me-1">Submit</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>

                </section>
            </div>
        </div>
    </div>


    <!-- END: Content-->

@endsection

@section('scripts')
<script src="{{ asset('') }}app-assets/vendors/js/vendors.min.js"></script>

    <script src="{{ asset('') }}app-assets/vendors/js/tables/datatable/jquery.dataTables.min.js"></script>
    <script src="{{ asset('') }}app-assets/vendors/js/tables/datatable/dataTables.bootstrap5.min.js"></script>
    <script src="{{ asset('') }}app-assets/vendors/js/tables/datatable/dataTables.responsive.min.js"></script>
    <script src="{{ asset('') }}app-assets/vendors/js/tables/datatable/responsive.bootstrap5.min.js"></script>
    <script src="{{ asset('') }}app-assets/vendors/js/tables/datatable/datatables.checkboxes.min.js"></script>
    <script src="{{ asset('') }}app-assets/vendors/js/tables/datatable/datatables.buttons.min.js"></script>

    <script src="{{ asset('') }}app-assets/vendors/js/tables/datatable/jszip.min.js"></script>
    <script src="{{ asset('') }}app-assets/vendors/js/tables/datatable/pdfmake.min.js"></script>
    <script src="{{ asset('') }}app-assets/vendors/js/tables/datatable/vfs_fonts.js"></script>
    <script src="{{ asset('') }}app-assets/vendors/js/tables/datatable/buttons.html5.min.js"></script>

    <script src="{{ asset('') }}app-assets/vendors/js/tables/datatable/buttons.print.min.js"></script>
    <script src="{{ asset('') }}app-assets/vendors/js/tables/datatable/dataTables.rowGroup.min.js"></script>
    <script src="{{ asset('') }}app-assets/vendors/js/pickers/flatpickr/flatpickr.min.js"></script>


    <script>
        var table = $('.datatables-basic').DataTable({

            // dom: 'Bfrtip',
            order: [[1, 'desc']],
            dom:
                '<"d-flex justify-content-between align-items-center header-actions mx-2 row mt-75"' +
                '<"col-sm-12 col-lg-4 d-flex justify-content-center justify-content-lg-start" l>' +
                '<"col-sm-12 col-lg-8 ps-xl-75 ps-0"<"dt-action-buttons d-flex align-items-center justify-content-center justify-content-lg-end flex-lg-nowrap flex-wrap"<"me-1"f>B>>' +
                '>t' +
                '<"d-flex justify-content-between mx-2 row mb-1"' +
                '<"col-sm-12 col-md-6"i>' +
                '<"col-sm-12 col-md-6"p>' +
                '>',
            // language: {
            //     sLengthMenu: 'Show _MENU_',
            //     search: 'Search',
            //     searchPlaceholder: 'Search..'
            // },
            // Buttons with Dropdown
            buttons: [

            'copyHtml5',
            'excelHtml5',
            'csvHtml5',
            'pdfHtml5',
                {
                    text: 'Send Email',
                    className: 'add-new send-email btn btn-primary',
                    attr: {
                        'data-bs-toggle': 'modal',
                        'data-bs-target': '#editUser'
                    },
                    // init: function (api, node, config) {
                    //     $(node).removeClass('btn-secondary');
                    // }
                }
            ],

            columnDefs: [

                    {
                    // For Checkboxes
                    targets: 0,
                    orderable: false,
                    responsivePriority: 3,
                    render: function (data, type, full, meta) {
                        return (
                        '<div class="form-check"> <input class="form-check-input dt-checkboxes" type="checkbox" value="" id="checkbox' +
                        data +
                        '" /><label class="form-check-label" for="checkbox' +
                        data +
                        '"></label></div>'
                        );
                    },
                    checkboxes: {
                        selectAllRender:
                        '<div class="form-check"> <input class="form-check-input" type="checkbox" value="" id="checkboxSelectAll" /><label class="form-check-label" for="checkboxSelectAll"></label></div>'
                    }
                    },
                ]
        });

        // $(document).on("click",".send-email",function() {
        //     var emailArr = [];
        //     $('table').find('.dt-checkboxes:checked').each(function( index ){
        //         emailArr.push($(this).parents('td').attr('id'));
        //     });

        //     console.log(emailArr);
        // });

        $(document).on('submit', '#postEmail', function (e) {
            e.preventDefault();

            var formdata = new FormData(this);
            $('table').find('.dt-checkboxes:checked').each(function( index ){
                formdata.append('emails[]',  $(this).parents('td').attr('id'));
            });


            $.ajaxSetup({headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}});
            $.ajax({
                type: "POST",
                xhrFields: {withCredentials: true},
                url: "{{ route('post-email') }}",
                data: formdata,
                contentType: false,
                processData: false,
                success: function (data) {
                    if(data.status){
                        toastr.success("Email Send Successfully");
                    } else{
                        toastr.error(data.msg);
                    }
                },
                error: function (error) {
                    toastr.success(error);
                }
            });
        })

    </script>
@endsection
