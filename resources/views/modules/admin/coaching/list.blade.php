
@extends('layouts.master')
@section('title','coaching | '.config('app.name'))
@section('style')
    <link rel="stylesheet" type="text/css" href="{{ asset('') }}app-assets/vendors/css/vendors.min.css">
    <link rel="stylesheet" type="text/css" href="{{ asset('') }}app-assets/vendors/css/tables/datatable/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" type="text/css" href="{{ asset('') }}app-assets/vendors/css/tables/datatable/responsive.bootstrap5.min.css">
    <link rel="stylesheet" type="text/css" href="{{ asset('') }}app-assets/vendors/css/tables/datatable/buttons.bootstrap5.min.css">
    {{-- <link rel="stylesheet" type="text/css" href="{{ asset('') }}app-assets/vendors/css/tables/datatable/rowGroup.bootstrap5.min.css"> --}}
    {{-- <link rel="stylesheet" type="text/css" href="{{ asset('') }}app-assets/vendors/css/pickers/flatpickr/flatpickr.min.css"> --}}
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
                            <h2 class="content-header-title float-start mb-0">Sessions List</h2>
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
                                            <th>id</th>
                                            <th>Name</th>
                                            <th>Class</th>
                                            {{-- <th>Start</th> --}}
                                            {{-- <th>End</th> --}}
                                            <th>Price</th>
                                            {{-- <th>Limit</th> --}}
                                            <th>Teacher</th>
                                            <th>Status</th>
                                            <th>Created Date</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($coachings as $coaching)

                                        <tr>
                                            <td>{{ $coaching->id }}</td>
                                            <td>
                                                <div class="d-flex justify-content-left align-items-center">
                                                    <div class="avatar  me-1"><img src="{{ asset( ($coaching?->image_id) ? $coaching?->image_id : 'assets/images/no-preview.png' ) }}" alt="Avatar" width="32" height="32"></div>
                                                    <div class="d-flex flex-column">
                                                        <span class="emp_name text-truncate fw-bold">{{ $coaching->title }}</span>
                                                        <small class="emp_post text-truncate text-muted">Subject : {{ $coaching?->getSubject?->name }}</small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>{{ $coaching?->getclass?->name }}</td>
                                            {{-- <td>{{ $coaching->start_time }}</td> --}}
                                            {{-- <td>{{ $coaching->end_time }}</td> --}}
                                            {{-- <td>{{ $coaching->getsubject->name }}</td> --}}
                                            <td>${{ $coaching->price_per_session }}</td>
                                            {{-- <td>{{ $coaching->session_limit }}</td> --}}
                                            <td>{{ $coaching->coach_name }}</td>
                                            <td>{{ ($coaching->status) ? 'active' : 'Inactive' }}</td>
                                            <td>{{ $coaching->created_at->format('d-M-Y') }}</td>
                                            <td>
                                                {{-- <a href="{{ route('app-coaching-detail', $coaching->id) }}" class="">
                                                    <x-detail-icon/>
                                                </a> --}}

                                                <a href="{{ route('coaching-edit', $coaching->id) }}" class="item-edit">
                                                    <x-edit-icon/>
                                                </a>

                                                <a href="{{ route('coaching-delete', $coaching->id) }}" class="delete-record" onclick="return confirm('are you sure you want to delete this?')">
                                                    <x-trash-icon/>
                                                </a>
                                                <a href="{{ route('ondemain-order-list', '?session='.$coaching->id) }}" class="delete-record">
                                                    <i data-feather='book'></i>
                                                </a>
                                                <a href="{{ $coaching?->metting_link }}" target="_blank" class="delete-record">
                                                    <i data-feather='link'></i>
                                                </a>
                                            </td>
                                        </tr>

                                        @endforeach
                                    </tbody>
                                </table>
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
    <script src="{{ asset('') }}app-assets/vendors/js/tables/datatable/jquery.dataTables.min.js"></script>
    <script src="{{ asset('') }}app-assets/vendors/js/tables/datatable/dataTables.bootstrap5.min.js"></script>
    <script src="{{ asset('') }}app-assets/vendors/js/tables/datatable/dataTables.responsive.min.js"></script>
    <script src="{{ asset('') }}app-assets/vendors/js/tables/datatable/responsive.bootstrap5.min.js"></script>
    {{-- <script src="{{ asset('') }}app-assets/vendors/js/tables/datatable/datatables.checkboxes.min.js"></script> --}}
    <script src="{{ asset('') }}app-assets/vendors/js/tables/datatable/datatables.buttons.min.js"></script>
    {{-- <script src="{{ asset('') }}app-assets/vendors/js/tables/datatable/jszip.min.js"></script> --}}
    {{-- <script src="{{ asset('') }}app-assets/vendors/js/tables/datatable/pdfmake.min.js"></script> --}}
    {{-- <script src="{{ asset('') }}app-assets/vendors/js/tables/datatable/vfs_fonts.js"></script> --}}
    {{-- <script src="{{ asset('') }}app-assets/vendors/js/tables/datatable/buttons.html5.min.js"></script> --}}
    {{-- <script src="{{ asset('') }}app-assets/vendors/js/tables/datatable/buttons.print.min.js"></script> --}}
    {{-- <script src="{{ asset('') }}app-assets/vendors/js/tables/datatable/dataTables.rowGroup.min.js"></script> --}}
    {{-- <script src="{{ asset('') }}app-assets/vendors/js/pickers/flatpickr/flatpickr.min.js"></script> --}}
    {{-- <script src="{{ asset('') }}app-assets/js/scripts/tables/table-datatables-basic.js"></script> --}}

    <script>
        var table = $('.datatables-basic').DataTable({

           // order: [[1, 'desc']],
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

                {
                    text: 'Add New Session',
                    className: 'add-new btn btn-primary',
                    attr: {
                        // 'data-bs-toggle': 'modal',
                        // 'data-bs-target': '#addservicemodal'
                    },
                    init: function (api, node, config) {
                        $(node).removeClass('btn-secondary');
                    }
                }
            ],
            });

            $(document).on("click",".add-new",function() {
                $(location).prop('href', "{{ route('coaching-create') }}");
            });

            table.on('draw', function () {
            feather.replace({
                width: 14,
                height: 14
            });
            });
    </script>
@endsection
