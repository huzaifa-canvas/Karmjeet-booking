
@extends('layouts.master')
@section('title','User List | '.config('app.name'))
@section('style')

    <link rel="stylesheet" type="text/css" href="../../../app-assets/css/pages/page-blog.css">
    <link rel="stylesheet" type="text/css" href="../../../app-assets/css/pages/page-faq.css">
    <link href='https://fonts.googleapis.com/css?family=Lato' rel='stylesheet' type='text/css'>
    {{-- <link rel="stylesheet" href="{{ asset('/front') }}/css/font-awesome.min.css"> --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css" integrity="sha512-MV7K8+y+gLIBoVD59lQIYicR65iaqukzvf/nwasF0nqhPay5w/9lJmVM2hMDcnK1OnMGCdVK+iQrJ7lzPJQd1w==" crossorigin="anonymous" referrerpolicy="no-referrer" />



     <!-- END: Page CSS-->

    <style>

        footer.footer.footer-static.footer-light {
            display: none;
        }

        .faq-search .card-body {
            padding: 0rem !important;
            padding-top: 7rem !important;
        }

        .header-navbar-shadow {
            display: none !important;
        }
        html .content.app-content {
            padding: calc(2rem) 2rem 0;
        }

        .card.ecommerce-card {
            border: 1px solid #cdcccc;
        }
        .content-body{
            margin: unset!important;
        }


        /* .card.faq-search:before {
            content: '';
            background-image: url("{{asset('/')}}assets/images/on-demand-session/Vector-top.png");
            width: 82px;
            height: 76px;
            position: absolute;
            background-repeat: no-repeat;
            right: 0;
        } */

        /* body .card.faq-search:after {
            content: '';
            background-image: url("{{asset('/')}}assets/images/on-demand-session/Vector-bottom.png");
            width: 81px;
            height: 91px;
            position: absolute;
            background-repeat: no-repeat;
            top: 340px;
            left: 0px;
        } */

        .main-menu{
            overflow: unset;
        }

        body #faq-search-filter .faq-search {
            background-color: #118cff !important;
            background-size: unset;
            background-image: url("{{asset('/')}}assets/images/on-demand-session/subject_layer.png");
        }

        ::-webkit-scrollbar-thumb {
            background: #118cff;
            border-radius: 20px;
        }

        @media (min-width: 1200px){
            nav.header-navbar {
                display: none;

            }
            .faq-search .card-body {
                padding-top: 4rem !important;
            }
            /* body .card.faq-search:after {
                content: '';
                background-image: url("{{asset('/')}}assets/images/on-demand-session/Vector-bottom.png");
                width: 81px;
                height: 91px;
                position: absolute;
                background-repeat: no-repeat;
                top: 298px;
                left: 80px;
            } */
        }
    </style>

    <style>

        div#card-element {
            width: 100%;
            padding: 20px;
            background-color: whitesmoke;
            margin-bottom: 20px;
        }

        #calendar{
            /* margin-left: auto;
            margin-right: auto; */
            margin-right: 2%;
            width: 320px;
            font-family: 'Lato', sans-serif;
        }
        #calendar_weekdays div{
            display:inline-block;
            vertical-align:top;
        }
        #calendar_content, #calendar_weekdays, #calendar_header{
            position: relative;
            width: 320px;
            overflow: hidden;
            float: left;
            z-index: 10;
        }
        #calendar_weekdays div, #calendar_content div{
            width:40px;
            height: 40px;
            overflow: hidden;
            text-align: center;
            background-color: #FFFFFF;
            color: #787878;
        }
        .dark-layout #calendar_weekdays div, .dark-layout #calendar_content div{
            background-color: #242b3d;
            color: #FFFFFF;
        }
        #calendar_content{
            -webkit-border-radius: 0px 0px 12px 12px;
            -moz-border-radius: 0px 0px 12px 12px;
            border-radius: 0px 0px 12px 12px;
        }
        #calendar_content div{
            float: left;
        }
        #calendar_content div:hover{
            background-color: #118cff;
        }
        #calendar_content div.blank{
            background-color: #E8E8E8;
        }
        .dark-layout #calendar_content div.blank{
            background-color: #242b3d;
        }
        #calendar_header, #calendar_content div.active{
            zoom: 1;
            filter: alpha(opacity=70);
            /* opacity: 0.7; */
        }
        #calendar_content div.active{
            color: #FFFFFF;
            background-color: #118cff;

        }
        #calendar_header{
            width: 100%;
            /* height: 37px; */
            text-align: center;
            background-color: #118cff;
            /* background-color: rgb(184 96 249); */
            padding: 14px 0px 5px 0px;
            -webkit-border-radius: 12px 12px 0px 0px;
            -moz-border-radius: 12px 12px 0px 0px;
            border-radius: 12px 12px 0px 0px;
        }
        #calendar_header h1{
            font-size: 1.5em;
            color: #FFFFFF;
            float:left;
            width:70%;
        }

        .dark-layout .col-md-3.time-slot-list {
            background-color: #242b3d;
        }

        .dark-layout div#session .slots {
            color: #fff;
        }

        i[class^=icon-chevron]{
            color: #FFFFFF;
            float: left;
            width:15%;
            border-radius: 50%;
        }
        .fa-angle-left:before ,.fa-angle-right:before {
            font-family: 'FontAwesome';
            cursor: pointer;

        }
        .select-date {
            cursor: pointer;
            font-weight: bold;
        }

        #calendar .disabled {
            cursor: not-allowed;
            opacity: 0.65;
        }

        .sessions_select{
            border: 1.5px solid midnightblue;
            border-radius: 10px;
            height: 150%;

        }

        .setup_table{
            position: relative;
        }


        .setup_table {
            position: relative;
            /* left: 2.7rem; */
            margin: 0!important;
            padding: 0!important;
        }

        div#session .slots {
            margin: 6px;
            text-align: center;
            /* margin-right: 20px; */
            /* width: fit-content; */
            padding: 4px 8px;
            color: midnightblue;
            border-radius: 4px;
            cursor: pointer;
            /* background: midnightblue; */
            border: 2px solid #118cff;
            font-size: 18px;
        }
        div#session{
            margin: unset;
            width: 100%;
            padding-top: 13px;
            justify-content: space-evenly;
            max-height: 340px;
            overflow: scroll;
        }

        div#session .slots.active {
            background: #118cff;
            color: white;
        }

        div#session .booked {
            margin: 6px;
            /* margin-right: 20px; */
            /* width: fit-content; */
            text-align: center;
            padding: 4px 8px;
            color: #8a8a8a;
            border-radius: 4px;
            cursor: not-allowed;
            background: #e8e8e8;
            border: 2px solid #a8a8b2;
            font-size: 18px;
        }
        .col-md-3.time-slot-list {
            background-color: #ffffff;
            padding: 0;
            margin-right: 2%;
            border-radius: 12px;
        }

        h1.time-slot-h {
            background: #118cff;
            color: white;
            margin: unset;
            padding: 14px;
            text-align: center;
            border-radius: 12px 12px 0px 0px;
            font-size: 1.5em;
        }

        .table thead th {
            background: #118cff !important;
            /* border-radius: 12px 12px 0px 0px; */
            color: white;
            padding: 18px;
        }
        .table thead .first {
            border-radius: 12px 0px 0px 0px;
        }
        .table thead .last {
            border-radius: 0px 12px 0px 0px;
        }

        pre.card-text.mb-2 {
            white-space: pre-line;
            background: unset;
            font-size: 14px;
        }

        .img-fluid {
            max-height: 380px;
            object-fit: cover;
        }

        .avatar img {
            border-radius: 50%;
            width: 35px;
            height: 35px;
            object-fit: cover;
        }

    </style>
@endsection

@section('content')


    <!-- BEGIN: Content-->

    <section id="faq-search-filter">
        <div class="card faq-search" style="background: {{ $session->unique_color }}!important;">
            <div class="card-body text-center">
                <!-- main title -->
                <img class="mb-3" src="{{ asset('')}}assets/images/on-demand-session/Vector.png" alt="">
                <h2 class="text-light">{{ $session?->title }} <br> <small class="text-light">{{ $session->created_at->format('M d, Y') }}</small> </h2>

                <!-- subtitle -->
                <p class="card-text text-light mb-2">{{ $session?->description }}</p>

                <!-- search input -->
                <form class="faq-search-input">
                    <div class="input-group input-group-merge">
                        <div class="input-group-text invisible">
                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-search"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
                        </div>
                        <input type="text" class="form-control invisible" placeholder="Search faq...">
                    </div>
                </form>
            </div>
        </div>
    </section>

     <!-- BEGIN: Content-->
     <div class="app-content content ">
        <div class="content-overlay"></div>
        <div class="header-navbar-shadow"></div>
        <div class="content-wrapper container-xxl p-0">
            {{-- <div class="content-header row">
                <div class="content-header-left col-md-9 col-12 mb-2">
                    <div class="row breadcrumbs-top">
                        <div class="col-12">
                            <h2 class="content-header-title float-start mb-0">{{ $session?->title }}</h2>
                        </div>
                    </div>
                </div>

            </div> --}}
            <div class="content-detached content-left">
                {{-- <div class="content-body">
                    <!-- Blog Detail -->
                    <div class="blog-detail-wrapper">
                        <div class="row">
                            <!-- Blog -->
                            <div class="col-12">
                                <div class="card">
                                    <img src="{{ asset($session?->image_id) }}" class="img-fluid card-img-top" alt="Blog Detail Pic" />
                                    <div class="card-body">
                                        <h3 class="card-title">{{ $session?->title }}</h3>
                                        <div class="d-flex">
                                            <div class="avatar me-50">
                                                <img src="{{ asset( ($session?->getSubject?->image_url) ? $session?->getSubject?->image_url : 'assets/images/no-preview.png' ) }}" alt="Avatar" width="24" height="24" />
                                            </div>
                                            <div class="author-info">
                                                <small class="text-muted me-25">Subject : </small>
                                                <small><a href="#" class="text-body">{{ $session?->getSubject?->name }}</a></small>
                                                <span class="text-muted ms-50 me-25">|</span>
                                                <small class="text-muted">{{ $session->created_at->format('M d, Y') }}</small>
                                            </div>
                                        </div>
                                        <div class="my-1 py-25">
                                            <a href="#">
                                                <span class="badge rounded-pill badge-light-danger me-50">Gaming</span>
                                            </a>
                                            <a href="#">
                                                <span class="badge rounded-pill badge-light-warning">Video</span>
                                            </a>
                                        </div>
                                        <pre class="card-text mb-2">
                                            {{ $session?->description }}
                                        </pre>
                                        <h4 class="mb-75">Unprecedented Challenge</h4>
                                        <ul class="p-0 mb-2">
                                            <li class="d-block">
                                                <span class="me-25">-</span>
                                                <span>Preliminary thinking systems</span>
                                            </li>
                                            <li class="d-block">
                                                <span class="me-25">-</span>
                                                <span>Bandwidth efficient</span>
                                            </li>
                                            <li class="d-block">
                                                <span class="me-25">-</span>
                                                <span>Green space</span>
                                            </li>
                                            <li class="d-block">
                                                <span class="me-25">-</span>
                                                <span>Social impact</span>
                                            </li>
                                            <li class="d-block">
                                                <span class="me-25">-</span>
                                                <span>Thought partnership</span>
                                            </li>
                                            <li class="d-block">
                                                <span class="me-25">-</span>
                                                <span>Fully ethical life</span>
                                            </li>
                                        </ul>
                                        <hr class="my-2" />
                                        <div class="d-flex align-items-start">
                                            <div class="avatar me-2">
                                                <img src="{{ asset('assets/images/avatar.png') }}" width="60" height="60" alt="Avatar" />
                                            </div>
                                            <div class="author-info">
                                                <h6 class="fw-bolder">{{ $session?->coach_name ? $session?->coach_name : $session?->getUser?->name }}</h6>
                                                <p class="card-text mb-0">
                                                    {{ $session?->coach_bio}}
                                                </p>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>
                            <!--/ Blog -->

                        </div>
                    </div>
                    <!--/ Blog Detail -->
                </div> --}}

                <!-- Leave a Blog Comment -->
                <form id="msform" method="POST" action="{{ route('course-booking', $session->id) }}" class="form" >
                    @csrf
                    <div class="row justify-content-center card card-body" style="flex-direction: unset;">

                        <div class="col-md-9 col-12">
                            <div class="mb-1">
                                <div class="remain float-end"><h4>Remaining Hours: <span>{{ $session?->plan_hours; }}</span></h4></div>
                                <div class="avail"><h4>Available Hours: <span>{{ $session?->plan_hours; }}</span></h4></div>
                            </div>
                        </div>

                        <div class="col-md-9 col-12">
                            <div class="mb-1">
                                <label class="form-label" for="teachers">Select Teacher</label>
                                <select class="form-select" id="teachers" name="teachers" required>
                                    @foreach ($session->teachers as $teacher)
                                        <option value="{{ $teacher }}" >{{ $session?->getUser($teacher)?->name }}</option>
                                    @endforeach
                                </select>
                                @error('teachers')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                            </div>
                        </div>

                        <div class="col-md-6" id="calendar">
                            <div id="calendar_header">
                                <i class="icon-chevron-left fa fa-angle-left"></i>
                                <h1></h1>
                                <i class="icon-chevron-right fa fa-angle-right"></i>
                            </div>
                            <div id="calendar_weekdays"></div>
                            <div id="calendar_content"></div>
                        </div>

                        <div class="col-md-6 time-slot-list">
                            <h1 class="time-slot-h">Time slot</h1>
                            <div class="row" id="session">
                                <div class="booked col-10">No Date selected</div>
                            </div>
                        </div>

                    </div>

                    <div class="row justify-content-between card card-body" style="flex-direction: unset;">

                        <div class="col-md-12 select_list">
                            <table class="table setup_table  table-striped main__table">
                                <thead>
                                    <tr>
                                        <th scope="col" class="first">Schedule</th>
                                        <th scope="col">Teacher</th>
                                        <th scope="col">Date</th>
                                        <th scope="col">Time</th>
                                        <th scope="col" class="last"></th>
                                    </tr>
                                </thead>
                                <tbody class="schedule-table">
                                    <tr class="demo"><td></td><td></td><td>No session selected</td><td></td><td></td></tr>
                                    {{-- <tr>
                                        <th>1</th>
                                        <td>Mark</td>
                                        <td>Otto</td>
                                        <td><span class="cross" >&#10060;</span></td>
                                    </tr> --}}
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="row justify-content-center">
                        <div class="col-12 mt-1">
                            <h6 class="section-label mt-25">User & Payment Information</h6>
                            <div class="card">
                                <div class="card-body">
                                        <div class="row">
                                            <div class="col-sm-4 col-12">
                                                <div class="mb-2">
                                                    <input type="text" class="form-control" value="{{ Auth::user()?->name }}" name="name" placeholder="Name" />
                                                </div>
                                            </div>
                                            <div class="col-sm-4 col-12">
                                                <div class="mb-2">
                                                    <input type="email" class="form-control" value="{{ Auth::user()?->email }}" name="email"  placeholder="Email" />
                                                </div>
                                            </div>
                                            <div class="col-sm-4 col-12">
                                                <div class="mb-2">
                                                    <input type="tel" class="form-control" value="{{ Auth::user()?->phone }}" name="phone"  placeholder="Phone" />
                                                </div>
                                            </div>
                                            <div class="col-12">
                                                <textarea class="form-control mb-2" rows="4" placeholder="Notes" name="note"></textarea>
                                            </div>
                                            <div class="col-12">
                                                <div id="card-element"></div>
                                            </div>
                                            <div class="col-12">
                                                <button type="submit" class="btn btn-primary">Subimt</button>
                                            </div>
                                        </div>

                                </div>
                            </div>
                        </div>
                    </div>
                </form>

                <!--/ Leave a Blog Comment -->


            </div>


            {{-- <div class="sidebar-detached sidebar-right">
                <div class="sidebar">
                    <div class="blog-sidebar my-2 my-lg-0">

                        <!-- Recent Posts -->
                        <div class="blog-recent-posts mt-3">
                            <h6 class="section-label">Related Session</h6>
                            <div class="mt-75">

                                @forelse ($randomCourses as $randomCourse)
                                    <div class="d-flex mb-2">
                                        <a href="{{ route('course-detail', $randomCourse?->slug) }}" class="me-2">
                                            <img class="rounded" src="{{ asset($randomCourse?->image_id) }}" width="100" height="70" alt="Recent Post Pic" />
                                        </a>
                                        <div class="blog-info">
                                            <h6 class="blog-recent-post-title">
                                                <a href="{{ route('course-detail', $randomCourse?->slug) }}" class="text-body-heading">{{ $randomCourse?->title }}</a>
                                            </h6>
                                            <div class="text-muted mb-0">{{ $randomCourse?->created_at->format('M d, Y') }}</div>
                                        </div>
                                    </div>
                                @empty
                                    <div class="d-flex mb-2">
                                        <div class="blog-info">
                                            <h6 class="blog-recent-post-title">
                                                <a href="javascript:;" class="text-body-heading">No Related Session Found</a>
                                            </h6>
                                        </div>
                                    </div>
                                @endforelse
                            </div>
                        </div>
                        <!--/ Recent Posts -->

                        <!-- subject -->
                        <div class="blog-categories mt-3">
                            <h6 class="section-label">Subjects</h6>
                            <div class="mt-1">
                                @forelse ($subjects as $subject)
                                    <div class="d-flex justify-content-start align-items-center mb-75">
                                        <a href="{{ route('courses', $subject?->slug) }}" class="me-75">
                                            <div class="avatar bg-light-primary rounded">
                                                <div class="avatar-content">
                                                    <img class="img-fluid card-img-top" src="{{ asset( ($subject?->image_url) ? $subject?->image_url : 'assets/images/no-preview.png' ) }}" alt="img-placeholder" />
                                                    <i data-feather="watch" class="avatar-icon font-medium-1"></i>
                                                </div>
                                            </div>
                                        </a>
                                        <a href="{{ route('courses', $subject?->slug) }}">
                                            <div class="blog-category-title text-body">{{ $subject?->name }}</div>
                                        </a>
                                    </div>
                                @empty

                                @endforelse
                            </div>
                        </div>
                        <!--/ subject -->
                    </div>

                </div>
            </div> --}}

        </div>
    </div>
    <!-- END: Content-->

@endsection

@section('scripts')
  <!-- BEGIN: Page JS-->
  <script src="{{ asset('') }}app-assets/js/scripts/pages/app-ecommerce-wishlist.js"></script>
  <script src="https://js.stripe.com/v3/"></script>
  <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
  <!-- END: Page JS-->

    @php
        $sectionLimit = $session?->plan_hours;
        if ($session?->duration == 30) {
            $sectionLimit = $sectionLimit * 2;
        } elseif ($session?->duration == 120) {
            $sectionLimit = $sectionLimit /2;
        }
    @endphp
  <script>

        var sectionLimit = {{ $sectionLimit }}
        var days = {!! json_encode($session?->days) !!};
        // var days = ["Sunday","Monday","Tuesday","Wednesday","Thursday","Friday","Saturday"];
        var blackOutDate = {!! json_encode(explode(",",str_replace(" ", "",$session?->blackout_dates))) !!};
        var monthLimit = {{ $session?->month_limit }}*30;
        var sessionId   =   "{{ $session?->id }}"
        var slug   =   "{{ $session?->id }}";
        var duration   =   {{ $session?->duration }};
        var remain   =   {{ $session?->plan_hours; }};
        var date = "";

        $(document).on( 'change', '#teachers', function(e){
            jQuery('#session').html('<div class="booked col-10">No Date selected</div>');
        })

        $(document).on( 'click', '.slots', function(e){
            var $this = $(this);
            var time = $this.text();
            var teacherId   =   $('#teachers').val();
            var teacherName = $('#teachers option:selected').text();

            if($this.hasClass('active')){
                alert('This Date Time select already');
                // $this.removeClass('active');

            }else{
                $('.schedule-table .demo').hide();
                var length = $('.schedule-row').length;
                var count = length+1;
                var dateTime = date+`=`+time;
                var ifSetAlrady = jQuery(".sl_dt[value='"+dateTime+"']").length;
                var ifTeacherSet = jQuery(".sl_teacher[value='"+teacherId+"']").length;

                if (ifSetAlrady && ifTeacherSet) {
                    alert('This Date & Time selected already');
                    $this.addClass('active');

                } else if (ifSetAlrady) {
                alert('This Date & Time selected for another teacher');
                // $this.addClass('active');

                } else if (sectionLimit <= length) {
                    alert('Allow only '+sectionLimit+' session selected on this package')

                } else {

                    if(duration == 30){
                        remain = remain - .5;
                    } else if(duration == 60){
                        remain = remain - 1;
                    } else{
                        remain = remain - 2;
                    }

                    jQuery('.remain span').text(remain);

                    $this.addClass('active');
                    $('.schedule-table').append(`<tr class="schedule-row">
                        <input class="sl_dt" type="hidden" value="`+date+`=`+time+`" name="booking_date_time[]" />
                        <input class="sl_teacher" type="hidden" value="`+teacherId+`" name="teacher[]" />
                        <th class="length">`+count+`</th>
                        <th class="teacherName">`+teacherName+`</th>
                        <td>`+date+`</td> <td>`+time+`</td>
                        <td><span class="cross remove-slots" >&#10060;</span></td> </tr>`);
                }
            }
        })

        $(document).on( 'click', '.remove-slots', function(e){
            $(this).parents('.schedule-row').remove();
            $('.schedule-table').children('.schedule-row').each(function(i, obj) {
                $(this).find('.length').text(i+1)
            });

            if(duration == 30){
                remain = remain + .5;
            } else if(duration == 60){
                remain = remain + 1;
            } else{
                remain = remain + 2;
            }

            jQuery('.remain span').text(remain);

            var length = $('.schedule-row').length;

            if (!length) {
                $('.schedule-table .demo').show();
            }
        })

        $(document).on( 'click', '.select-date', function(e){
            $('#calendar').find('.active').removeClass('active');
            $(this).addClass('active');

                date        =   $(this).attr('date');
            var teacherId   =   $('#teachers').val();
            var weekday     =   $(this).attr('weekday');

            $.ajaxSetup({headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}});
            url = "{{ route('course-slots') }}"

            $.ajax({
                url: url,
                type: "POST",
                data: {date : date, id : sessionId, weekday : weekday, session_type: 'on-demand', teacher:teacherId},
            }).done(function (data) {
                console.log(data);
                var html = '';
                $.each(data.slots, function(key,val){
                    if(jQuery.inArray(val, data.booked) != -1) {
                        html += '<div class="booked col-5">'+val+'</div>';
                    } else {
                        html += '<div class="slots col-5">'+val+'</div>';
                    }
                });
                $('#session').html(html);
            });

        })

        // stripe
        var stripe = Stripe('pk_test_51MAZe4HnBjRuAp0ig9aBTPdENBP8OcVtiSIOHXv9EYDFVg2fuyq8nYs15fHWsQ3TWTnJ9sYvdHp65n8m6ZzvckIK00LDa3LnBE');
		var elements = stripe.elements();
		// var cardElement = elements.create('card');
        var cardElement = elements.create('card', {
            hidePostalCode: true,
        });
		cardElement.mount('#card-element');


        $('#msform').submit(function(e){
            e.preventDefault();
            var length = $('.schedule-row').length;
            var count = length+1;
            if (sectionLimit > length) {
                alert('Please avail all remaining hours before booking');
                return false;
            }
            var stripeForm = new FormData(this);
            stripe.createToken(cardElement).then(function(result) {
            if (result.error) {
                swal({
                    title: "Error!",
                    text: result.error.message,
                    icon: "warning",
                    button: "Close",
                });
                console.error(result.error);
            } else {
                // Attach the token or source to the form data
                stripeForm.append('stripeToken', result.token.id);
                stripeForm.append('session_type', 'on-demand');
                // let stripeForm = new FormData($('form.msform')[0]);
                let url = $('#msform').attr('action');
                $('#msform').find('button[type="submit"]').append('<i class="fa fa-spinner fa-spin" style="font-size:24px"></i>');
                $('#msform').find('button[type="submit"]').prop('disabled',true);

                $.ajax({
                    type: 'post',
                    url: url,
                    data: stripeForm,
                    dataType : 'json',
                    cache:false,
                    contentType: false,
                    processData: false,
                    success: function (response) {
                        $('#msform').find('button[type="submit"]').prop('disabled',false);
                        $(".fa-spinner").remove();
                        console.log(response);

                        if(!response.status){

                            swal({
                                title: "Error!",
                                text: response.message,
                                icon: "warning",
                                button: "Close",
                            });
                        }
                        else{
                            if (response.auto_redirect) {window.location.href = response.redirect_url;}
                            else{
                                swal({
                                    //title: "Good job!",
                                    text: response.message,
                                    icon: "success",
                                    button: "Close",
                                }).then((willDelete) => {
                                    window.location.href = "{{ route('ondemain-order-list') }}";
                                });
                            }
                        }
                    },
                    error : function(errorThrown){
                        console.log(errorThrown);
                        swal({
                            title: "Error!",
                            text: errorThrown,
                            icon: "warning",
                            button: "Close",
                        });
                    }
                });
            }
        });
        });

        $(function () {
            function c() {
                p();
                var e = h();
                var r = 0;
                var u = false;
                l.empty();
                while (!u) {
                if (s[r] == e[0].weekday) {
                    u = true;
                } else {
                    l.append('<div class="blank"></div>');
                    r++;
                }
                }
                for (var c = 0; c < 42 - r; c++) {
                if (c >= e.length) {
                    l.append('<div class="blank"></div>');
                } else {
                    var v = e[c].day;
                    var  date1 = t+"/"+n+"/"+v;
                    var limit3month = twoDateDiffr(new Date(t, n - 1, v));
                    var attr = 'weekday="'+e[c].weekday+'" day="'+v+'" month="'+ n +'" year="'+t+'" date="'+date1+'"';
                    var blockDate = ifBlockDate(date1);
                    var allowDate = jQuery.inArray( e[c].weekday, days) !== -1;
                    var ifActive = (gNew(new Date(t, n - 1, v)) && allowDate && limit3month && blockDate) ? "select-date" : "disabled";
                    var m = g(new Date(t, n - 1, v)) ? '<div '+attr+' class="active '+ifActive+'">' : '<div '+attr+' class="'+ifActive+'">';
                    l.append(m + "" + v + "</div>");
                }
                }
                var y = o[n - 1];
                a.css("background-color", y)
                .find("h1")
                .text(i[n - 1] + " " + t);
                f.find("div").css("color", y);
                // l.find(".active").css("background-color", y);
                // l.find(".active").css("background-color", '#191970');
                d();
            }

            function ifBlockDate(date) {
                let parts = date.split('/'); // Split the date string by '/'
                let year = parts[0]; // Extract year
                let month = parts[1].padStart(2, '0'); // Extract month and pad with zero if needed
                let day = parts[2].padStart(2, '0'); // Extract day and pad with zero if needed

                let newDate = `${year}/${month}/${day}`;
                return jQuery.inArray( newDate, blackOutDate) == -1;
            }

            function twoDateDiffr(start){
                var end= new Date();
                tempDays = (start - end) / (1000 * 60 * 60 * 24);
                return (monthLimit) ? Math.round(tempDays) < monthLimit : true;
                // if (sectionLimit == 1) {
                //     return Math.round(tempDays) < 31;
                // } else if (sectionLimit == 4) {
                //     return Math.round(tempDays) < 121;
                // } else if (sectionLimit == 12) {
                //     return Math.round(tempDays) < 181;
                // } else if (sectionLimit == 24) {
                //     return Math.round(tempDays) < 365;
                // } else{
                //     return Math.round(tempDays) < 91;
                // }
            }
            function h() {
                var e = [];
                for (var r = 1; r < v(t, n) + 1; r++) {
                e.push({ day: r, weekday: s[m(t, n, r)] });
                }
                return e;
            }
            function p() {
                f.empty();
                for (var e = 0; e < 7; e++) {
                f.append("<div>" + s[e].substring(0, 3) + "</div>");
                }
            }
            function d() {
                var t;
                var n = $("#calendar").css("width", e+28 + "px");
                n.find((t = "#calendar_weekdays, #calendar_content"))
                .css("width", e + "px")
                .find("div")
                .css({
                    width: e / 7 + "px",
                    height: e / 7 + "px",
                    "line-height": e / 7 + "px"
                });
                n.find("#calendar_header")
                // .css({ height: e * (1 / 5) + "px" })
                // .find('i[class^="icon-chevron"]')
                // .css("line-height", e * (1 / 7) + "px");
            }
            function v(e, t) {
                return new Date(e, t, 0).getDate();
            }
            function m(e, t, n) {
                return new Date(e, t - 1, n).getDay();
            }
            function g(e) {
                return y(new Date()) == y(e);
            }
            function gNew(e) {
                // console.log(y(new Date()), y(e))
                // console.log(    new Date(y(new Date())).getTime() , e.getTime() )
                return new Date(y(new Date())).getTime() < e.getTime();
                // return y(new Date()) > y(e);
            }
            function y(e) {
                return e.getFullYear() + "/" + (e.getMonth() + 1) + "/" + e.getDate();
            }
            function b() {
                var e = new Date();
                t = e.getFullYear();
                n = e.getMonth() + 1;
            }
            var e = 480;
            var e = 350;
            var t = 2013;
            var n = 9;
            var r = [];
            var i = [
                "JANUARY",
                "FEBRUARY",
                "MARCH",
                "APRIL",
                "MAY",
                "JUNE",
                "JULY",
                "AUGUST",
                "SEPTEMBER",
                "OCTOBER",
                "NOVEMBER",
                "DECEMBER"
            ];
            var s = [
                "Sunday",
                "Monday",
                "Tuesday",
                "Wednesday",
                "Thursday",
                "Friday",
                "Saturday"
            ];
            var o = [
                "#118cff",
                "#118cff",
                "#118cff",
                "#118cff",
                // "#16a085",
                // "#1abc9c",
                // "#c0392b",
                // "#27ae60",
                // "#FF6860",
                // "#f39c12",
                // "#f1c40f",
                // "#e67e22",
                // "#2ecc71",
                // "#e74c3c",
                // "#d35400",
                // "#2c3e50"
            ];
            var u = $("#calendar");
            var a = u.find("#calendar_header");
            var f = u.find("#calendar_weekdays");
            var l = u.find("#calendar_content");
            b();
            c();
            a.find('i[class^="icon-chevron"]').on("click", function () {
                var e = $(this);
                var r = function (e) {
                n = e == "next" ? n + 1 : n - 1;
                if (n < 1) {
                    n = 12;
                    t--;
                } else if (n > 12) {
                    n = 1;
                    t++;
                }
                c();
                };
                if (e.attr("class").indexOf("left") != -1) {
                r("previous");
                } else {
                    r("next");
                }
            });
        });
  </script>
@endsection
