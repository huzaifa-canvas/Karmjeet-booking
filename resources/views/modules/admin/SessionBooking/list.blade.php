
@extends('layouts.master')
@section('title','Session List | '.config('app.name'))

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
                            <h2 class="content-header-title float-start mb-0">Booked Session</h2>
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
                                            <th>Student Name</th>
                                            <th>Student Phone & email</th>
                                            <th>Start & End time</th>
                                            <th>Session Date</th>
                                            <th>Course & Subject</th>
                                            <th>Teacher</th>
                                            {{-- <th>Year</th>
                                            <th>Price</th> --}}
                                            <th>Booked Date</th>
                                            <th>Attendance</th>
                                            {{-- <th>Payment Status</th> --}}
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($sessions as $session)

                                        <tr>
                                            <td>{{ $session?->id }}</td>
                                            <td>
                                                <div class="d-flex justify-content-left align-items-center">
                                                    {{-- <div class="avatar  me-1"><img src="{{ asset( ($session?->image_id) ? $session?->image_id : 'assets/images/no-preview.png' ) }}" alt="Avatar" width="32" height="32"></div> --}}
                                                    <div class="d-flex flex-column">
                                                        <span class="emp_name text-truncate fw-bold">{{ $session?->full_name }}</span>
                                                        <small class="emp_post text-truncate text-muted">{{ $session?->getUser->name }}</small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="d-flex justify-content-left align-items-center">
                                                    {{-- <div class="me-1"><span class="emp_post text-truncate"></span></div> --}}
                                                    <div class="d-flex flex-column">
                                                        <span class="emp_name text-truncate fw-bold">{{ $session?->phone }}</span>
                                                        <span class="emp_name text-truncate text-muted">{{ $session?->email }}</span>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="d-flex justify-content-left align-items-center">
                                                    {{-- <div class="me-1"><span class="emp_post text-truncate">{{ $session?->duration }} min</span></div> --}}
                                                    <div class="d-flex flex-column">
                                                        <span class="emp_name text-truncate fw-bold">{{ $session?->start_time }}</span>
                                                        <span class="emp_name text-truncate fw-bold">{{ $session?->end_time }}</span>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>{{ $session->date->format('d-M-Y') }}</td>
                                            <td>
                                                <div class="d-flex justify-content-left align-items-center">
                                                    {{-- <div class="avatar  me-1"><img src="{{ asset( ($session?->image_id) ? $session?->image_id : 'assets/images/no-preview.png' ) }}" alt="Avatar" width="32" height="32"></div> --}}
                                                    <div class="d-flex flex-column">
                                                        @if ($session->session_type == 'regular')
                                                            <span class="emp_name text-truncate fw-bold">{{ $session?->getSession?->title }}</span>
                                                            <small class="emp_post text-truncate text-muted">{{ $session?->getSession?->getSubject->name }}</small>
                                                        @else
                                                            <span class="emp_name text-truncate fw-bold">{{ $session?->getOnDemandSession?->title }}</span>
                                                            {{-- <small class="emp_post text-truncate text-muted">{{ $session?->getSession?->getSubject->name }}</small> --}}
                                                        @endif
                                                    </div>
                                                </div>
                                            </td>
                                            <td>{{ $session?->coach_name }}</td>
                                            {{-- <td>{{ $session->date->format('F') }}</td> --}}
                                            {{-- <td>{{ $session->date->format('Y') }}</td> --}}

                                            {{-- <td>${{ $session->price_per_session }}</td> --}}
                                            <td>{{ $session->created_at->format('d-M-Y') }}</td>

                                            <td style="text-transform: capitalize;">
                                                @if ($session->session_status != "pending")
                                                    {{ $session->session_status }}
                                                @else
                                                    @if (auth()->user()->user_role == "admin" || auth()->user()->user_role == "coach")

                                                        <select name="update_status" class="update_status" id="{{ $session->id }}">
                                                            <option {{ ($session->session_status == 'pending') ? 'selected':'' }} value="pending">Pending</option>
                                                            <option {{ ($session->session_status == 'attended') ? 'selected':'' }} value="attended">Attended </option>
                                                            <option {{ ($session->session_status == 'no-show') ? 'selected':'' }} value="not-attended">Not Attended</option>
                                                        </select>
                                                    @else
                                                        {{ $session->session_status }}
                                                        {{-- <select name="update_status" class="update_status" id="{{ $session->id }}" data-bs-toggle="tooltip" data-bs-placement="top" title="" data-bs-original-title="Please note you are able to cancel and reschedule up to 24 hours in advance without incurring a late cancellation fee ($75) or no show fee ($100).">
                                                            <option {{ ($session->session_status == 'pending') ? 'selected':'' }} value="pending">Pending</option>
                                                            <option {{ ($session->session_status == 'canceled') ? 'selected':'' }} value="canceled">Canceled</option>
                                                        </select> --}}
                                                    @endif

                                                @endif
                                            </td>
                                            {{-- <td><span class="badge badge-light-{{ ($session->payment_status=='success' || $session->payment_status=='free' ? "success":($session->payment_status=='pending' ? "warning":"danger") ) }}">{{ $session->payment_status }}</span></td> --}}
                                            <td>
                                                {{-- <a href="{{ route('app-session-detail', $session->id) }}" class="">
                                                    <x-detail-icon/>
                                                </a> --}}

                                                <a href="javascript:;" class="" data-bs-toggle="tooltip" data-bs-placement="top" title="" data-bs-original-title="{{  $session?->objective }}">
                                                    <i data-feather='bookmark'></i>
                                                </a>

                                                @if ($session->session_status == 'pending' && $session->session_type == 'regular')
                                                    <a title="Reschedule" href="{{ route('session-edit', $session->id) }}" class="item-edit">
                                                        <x-edit-icon/>
                                                    </a>
                                                @endif

                                                @if (auth()->user()->user_role == "admin")
                                                    <a href="{{ route('user-edit', $session->user_id) }}" class="delete-record">
                                                        <i data-feather='user'></i>
                                                    </a>
                                                @endif

                                                @if ($session->session_type == 'regular')
                                                    <a href="{{ $session?->getSession?->metting_link }}" target="_blank" class="delete-record">
                                                        <i data-feather='link'></i>
                                                    </a>
                                                @else
                                                    <a href="{{ $session?->getTeacher?->metting_link }}" target="_blank" class="delete-record">
                                                        <i data-feather='link'></i>
                                                    </a>
                                                @endif

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
    {{-- <script src="{{ asset('') }}app-assets/vendors/js/pickers/flatpickr/flatpickr.min.js"></script> --}}

    {{-- <script src="{{ asset('') }}app-assets/js/scripts/tables/table-datatables-advanced.js"></script> --}}

    {{-- <script src="{{ asset('') }}app-assets/vendors/js/tables/datatable/datatables.checkboxes.min.js"></script> --}}
    <script src="{{ asset('') }}app-assets/vendors/js/tables/datatable/datatables.buttons.min.js"></script>
    <script src="{{ asset('') }}app-assets/vendors/js/tables/datatable/jszip.min.js"></script>
    <script src="{{ asset('') }}app-assets/vendors/js/tables/datatable/pdfmake.min.js"></script>
    <script src="{{ asset('') }}app-assets/vendors/js/tables/datatable/vfs_fonts.js"></script>
    <script src="{{ asset('') }}app-assets/vendors/js/tables/datatable/buttons.html5.min.js"></script>

    <script>

        // $('.datatables-basic thead tr')
        // .clone(true)
        // .addClass('filters')
        // .appendTo('.datatables-basic thead');

        var table = $('.datatables-basic').DataTable({
                //scrollX: true,
                order: [[0, 'desc']],
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
                    // {
                    //     text: 'Add New session',
                    //     className: 'add-new btn btn-primary',
                    //     attr: {
                    //         // 'data-bs-toggle': 'modal',
                    //         // 'data-bs-target': '#addservicemodal'
                    //     },
                    //     init: function (api, node, config) {
                    //         $(node).removeClass('btn-secondary');
                    //     }
                    // }
                ],

                orderCellsTop: true,
                fixedHeader: true,
                // initComplete: function () {
                //     var api = this.api();

                //     // For each column
                //     api
                //         .columns()
                //         .eq(0)
                //         .each(function (colIdx) {
                //             // Set the header cell to contain the input element
                //             var cell = $('.filters th').eq(
                //                 $(api.column(colIdx).header()).index()
                //             );
                //             var title = $(cell).text();
                //             $(cell).html('<input type="text" placeholder="' + title + '" />');

                //             // On every keypress in this input
                //             $(
                //                 'input',
                //                 $('.filters th').eq($(api.column(colIdx).header()).index())
                //             )
                //                 .off('keyup change')
                //                 .on('change', function (e) {
                //                     // Get the search value
                //                     $(this).attr('title', $(this).val());
                //                     var regexr = '({search})'; //$(this).parents('th').find('select').val();

                //                     var cursorPosition = this.selectionStart;
                //                     // Search the column for that value
                //                     api
                //                         .column(colIdx)
                //                         .search(
                //                             this.value != ''
                //                                 ? regexr.replace('{search}', '(((' + this.value + ')))')
                //                                 : '',
                //                             this.value != '',
                //                             this.value == ''
                //                         )
                //                         .draw();
                //                 })
                //                 .on('keyup', function (e) {
                //                     e.stopPropagation();

                //                     $(this).trigger('change');
                //                     $(this)
                //                         .focus()[0]
                //                         .setSelectionRange(cursorPosition, cursorPosition);
                //                 });
                //         });
                // },

            });

            $(document).on("click",".add-new",function() {
                $(location).prop('href', "{{ route('session-create') }}");
            });

            table.on('draw', function () {
            feather.replace({
                width: 14,
                height: 14
            });
            });
    </script>

    <script>

        $(document).on( 'change', '.update_status', function(e){

            var id = $(this).attr('id');
            var $this = $(this);
            var status = $this.val();

            $.ajaxSetup({headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}});
            url = "{{ route('charge-payment') }}"

            $.ajax({
                url: url,
                type: "POST",
                data: {id : id, status:status},
            }).done(function (data) {
                if (data.status) {
                    toastr.success(data.message);
                    if (status == "done") {
                        $this.parents('tr').find('.badge-light-warning').text('Success');
                        $this.parents('tr').find('.badge-light-warning').removeClass('badge-light-warning').addClass('badge-light-success');
                    }
                    $this.parent().html(status);

                } else{
                    toastr.error(data.message);
                }
                console.log(data);
            });

        });
    </script>
@endsection
