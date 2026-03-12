@extends('layouts.master')
@section('title',($isEdit) ? "Edit Session" : 'Add Session'.' | '.config('app.name'))
@section('style')
<link href='https://fonts.googleapis.com/css?family=Lato' rel='stylesheet' type='text/css'>
    {{-- <link rel="stylesheet" href="{{ asset('/front') }}/css/font-awesome.min.css"> --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css" integrity="sha512-MV7K8+y+gLIBoVD59lQIYicR65iaqukzvf/nwasF0nqhPay5w/9lJmVM2hMDcnK1OnMGCdVK+iQrJ7lzPJQd1w==" crossorigin="anonymous" referrerpolicy="no-referrer" />


    <style>
        #calendar{
            margin-left: auto;
            margin-right: auto;
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
        #calendar_content{
            -webkit-border-radius: 0px 0px 12px 12px;
            -moz-border-radius: 0px 0px 12px 12px;
            border-radius: 0px 0px 12px 12px;
        }
        #calendar_content div{
            float: left;
        }
        #calendar_content div:hover{
            background-color: #F8F8F8;
        }
        #calendar_content div.blank{
            background-color: #E8E8E8;
        }
        #calendar_header, #calendar_content div.active{
            zoom: 1;
            filter: alpha(opacity=70);
            /* opacity: 0.7; */
        }
        #calendar_content div.active{
            color: #FFFFFF;
            background-color: #118CFF;

        }
        #calendar_header{
            width: 100%;
            height: 37px;
            text-align: center;
            background-color: #118CFF;
            padding: 18px 0;
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
            border: 1.5px solid #118cff;
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
            margin-right: 20px;
            width: fit-content;
            padding: 4px 8px;
            color: #118cff;
            border-radius: 4px;
            cursor: pointer;
            /* background: #118cff; */
            border: 2px solid #118cff;
            font-size: 18px;
        }
        div#session{
            /* display: contents;
            margin: 40px */
            margin-top: 25px;
            padding-top: 13px;
            /* border-top: 1px solid #d2d0d0; */
        }

        div#session .slots.active {
            background: #118cff;
            color: white;
        }

        div#session .booked {
            margin: 6px;
            margin-right: 20px;
            width: fit-content;
            padding: 4px 8px;
            color: #8a8a8a;
            border-radius: 4px;
            cursor: not-allowed;
            background: #e8e8e8;
            border: 2px solid #a8a8b2;
            font-size: 18px;
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
                            <h2 class="content-header-title float-start mb-0"> {{ ($isEdit) ? "Edit Session" : 'Add Session' }}</h2>
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
                                    {{-- @if ($remainLessThan24h)
                                        <p class="text-danger">Please note you are able to cancel and reschedule up to 24 hours in advance without incurring a late cancellation fee ($75) or no show fee ($100).</p>
                                    @endif --}}
                                    <form class="form form-vertical" method="POST" action="{{ ($isEdit) ?  route('session-update', $id) : route('session-store') }}" enctype="multipart/form-data">
                                        @csrf
                                        <input type="hidden" value="{{ $session?->date }}" name="date" />
                                        <div class="row">
                                            <div class="col-md-4  col-12">
                                                <div id="calendar">
                                                    <div id="calendar_header">
                                                        <i class="icon-chevron-left fa fa-angle-left"></i>
                                                        <h1></h1>
                                                        <i class="icon-chevron-right fa fa-angle-right"></i>
                                                    </div>
                                                    <div id="calendar_weekdays"></div>
                                                    <div id="calendar_content"></div>
                                                </div>
                                            </div>

                                            <div class="col-8" >
                                                <h3> Selected Date : <span id="displayDate">{{ $session?->date->format('Y/m/d') }}</span></h3>
                                                <div class="row" id="session">
                                                    @foreach ($slots as $slot)

                                                        @if (in_array($slot, $booked) && $session->start_end_time != $slot)
                                                            <label class="booked col-2" for="{{ $slot }}">{{ $slot }}</label>
                                                            @continue
                                                        @endif

                                                        <label class="slots col-2 {{ ($session->start_end_time == $slot) ? 'active' : '' }}" for="{{ $slot }}">{{ $slot }}
                                                            <input id="{{ $slot }}" type="radio" {{ ($session->start_end_time == $slot) ? 'checked' : '' }} style="display:none;" name="slot" value="{{ $slot }}">
                                                        </label>
                                                    @endforeach
                                                </div>
                                                @error('date')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror<br/>
                                                @error('slot')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                                            </div>

                                            <div class="col-12 mt-5">
                                                <button type="submit" class="btn btn-primary me-1">Submit</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>

                    </div>
                </section>

                {{-- <section id="basic-vertical-layouts">
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-body">
                                    <form class="form form-vertical" method="POST" action="{{ ($isEdit) ?  route('session-update', $id) : route('session-store') }}" enctype="multipart/form-data">
                                        @csrf
                                        <div class="row">
                                            <div class="col-md-6  col-12">
                                                <div class="mb-1">
                                                    <label class="form-label" for="first-name-vertical" >Name</label>
                                                    <input type="text" id="first-name-vertical" class="form-control" value="{{ ($isEdit) ? $session?->name : old('name')  }}" name="name" placeholder="First Name" />
                                                    @error('name')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                                                </div>
                                            </div>

                                            <div class="col-md-6  col-12">
                                                <div class="mb-1">
                                                    <label class="form-label" for="limit">Enrolled Limit</label>
                                                    <input type="number" id="limit" class="form-control" value="{{ ($isEdit) ? $session?->enrolled_limit : old('enrolled_limit')  }}" name="enrolled_limit" placeholder="Enrolled Limit" />
                                                    @error('enrolled_limit')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                                                </div>
                                            </div>

                                            <div class="col-md-12  col-12">
                                                <div class="mb-1">
                                                    <label class="form-label" for="subject">Subject</label>
                                                    <input type="text" id="subject" class="form-control" value="{{ ($isEdit) ? $session?->subject : old('subject')  }}" name="subject" placeholder="Subject" />
                                                    @error('subject')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                                                </div>
                                            </div>

                                            <div class="col-md-6  col-12">
                                                <div class="mb-1">
                                                    <label class="form-label" for="description">Description</label>
                                                    <textarea class="form-control" name="description" id="" rows="4">{{ ($isEdit) ? $session?->description : old('description')  }}</textarea>
                                                    @error('description')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="mb-1">
                                                    <label class="form-label" for="website-vertical">Banner image</label>
                                                    <div class="avatar-upload">
                                                        <div class="avatar-edit">
                                                            <input type='file' id="imageUpload" name="image" accept=".png, .jpg, .jpeg" />
                                                            <label for="imageUpload">
                                                                <i data-feather='edit' style="width: 33px; height: 29px;"></i>
                                                            </label>
                                                        </div>
                                                        <div class="avatar-preview">
                                                            <div id="imagePreview" style="background-image: url({{ asset( (isset($session?->image_id)) ? $session?->image_id : 'assets/images/no-preview.png' ) }});">
                                                            </div>
                                                        </div>
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
                </section> --}}

                <!-- Basic Vertical form layout section end -->

            </div>
        </div>
    </div>

    <!-- END: Content-->
@endsection

@section('scripts')
    <script>

    var days = {!! $session?->getSession->getslots->pluck('days')->toJson() !!};
    var blackOutDate = {!! json_encode(explode(",",str_replace(" ", "",$session?->getSession?->blackout_dates))) !!};
    var sectionLimit = {{ $session?->getSession?->session_limit }};
    var monthLimit = {{ $session?->getSession?->month_limit }}*30;
    var sessionId   =   "{{ $session?->getSession->id }}"
    var date = "{{ $session?->date->format('Y/m/d') }}";


        $(document).on( 'click', '.slots', function(e){
            var $this = $(this);
            var time = $this.text();
            $('.slots').removeClass('active');
            $this.addClass('active');
            $('input[name=date]').val(date);
            $('#displayDate').text(date);
        })

        $(document).on( 'click', '.select-date', function(e){
            $('#calendar').find('.active').removeClass('active');
            $(this).addClass('active');

                date        =   $(this).attr('date');
            var weekday     =   $(this).attr('weekday');

            $.ajaxSetup({headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}});
            url = "{{ route('course-slots') }}"

            $.ajax({
                url: url,
                type: "POST",
                dataType : 'json',
                data: {date : date, id : sessionId, weekday : weekday, session_type: 'regular',},
            }).done(function (data) {

                var html = '';
                $.each(data.slots, function(key,val){
                    if(jQuery.inArray(val, data.booked) != -1) {
                        html += `<label class="booked col-2" for="`+val+`">`+val+`</label>`;
                    } else {
                        html += `<label class="slots col-2" for="`+val+`">`+val+`
                                    <input id="`+val+`" type="radio" style="display:none;" name="slot" value="`+val+`">
                                </label>`;
                    }
                });
                console.log(data);
                $('#session').html(html);
            });
        })

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
                // console.log(jQuery.inArray( e[c].weekday, days) !== -1);
                var blockDate = jQuery.inArray( date1, blackOutDate) == -1;
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
            // l.find(".active").css("background-color", '#118CFF');
            d();
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
            var n = $("#calendar").css("width", e + "px");
            n.find((t = "#calendar_weekdays, #calendar_content"))
            .css("width", e + "px")
            .find("div")
            .css({
                width: e / 7 + "px",
                height: e / 7 + "px",
                "line-height": e / 7 + "px"
            });
            n.find("#calendar_header")
            .css({ height: e * (1 / 5) + "px" })
            .find('i[class^="icon-chevron"]')
            .css("line-height", e * (1 / 7) + "px");
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
            "#118CFF",
            "#006d5b",
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

        // var date = "{{ $session?->date->format('Y-m-d') }}";
        // alert(date);
        // $('#calendar_content').find('div[date="'+date+'"]').click()
    </script>
@endsection
