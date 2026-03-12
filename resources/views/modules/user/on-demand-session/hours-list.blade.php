
@extends('layouts.master')
@section('title','User List | '.config('app.name'))
@section('style')

     <link rel="stylesheet" type="text/css" href="{{ asset('') }}app-assets/css/pages/app-ecommerce.css">
    <link rel="stylesheet" type="text/css" href="../../../app-assets/css/pages/page-faq.css">


     <!-- END: Page CSS-->

    <style>
        .item-img1.text-center1 img {
            height: 181px;
        }
        footer.footer.footer-static.footer-light {
            display: none;
        }

        .faq-search .card-body {
            padding: 4rem !important;
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
        .ecommerce-application .content-body{
            padding: 30px;
        }


        .card.faq-search:before {
            content: '';
            background-image: url("{{asset('/')}}assets/images/on-demand-session/Vector-top.png");
            width: 82px;
            height: 76px;
            position: absolute;
            background-repeat: no-repeat;
            right: 0;
        }

        body .card.faq-search:after {
            content: '';
            background-image: url("{{asset('/')}}assets/images/on-demand-session/Vector-bottom.png");
            width: 81px;
            height: 91px;
            position: absolute;
            background-repeat: no-repeat;
            top: 340px;
            left: 0px;
        }

        .main-menu{
            overflow: unset;
        }

        body #faq-search-filter .faq-search {
            background-color: #107fe2 !important;
            background-size: unset;
            background-image: url("{{asset('/')}}assets/images/on-demand-session/subject_layer.png");
        }

        ::-webkit-scrollbar-thumb {
            background: #b860f9;
            border-radius: 20px;
        }

        @media (min-width: 1200px){
            nav.header-navbar {
                display: none;

            }
            .faq-search .card-body {
                padding-top: 4rem !important;
            }
            body .card.faq-search:after {
                content: '';
                background-image: url("{{asset('/')}}assets/images/on-demand-session/Vector-bottom.png");
                width: 81px;
                height: 91px;
                position: absolute;
                background-repeat: no-repeat;
                top: 298px;
                left: 80px;
            }
        }
        .card.ecommerce-card {
            padding: 10px;
        }
        .ecommerce-application .grid-view .ecommerce-card .item-description {
            white-space: normal;
            max-height: 89px;
            min-height: 62px;
        }

        p.teacher-bio{
            font-size: 0.875rem;
            line-height: 1.5rem;
        }

        .btn-dark {
            background-color: #020202 !important;
        }
        .btn-dark:hover:not(.disabled):not(:disabled) {
            background-color: #118CFF !important;
            border-color: #118CFF !important;
        }

        .ecommerce-application .grid-view .ecommerce-card .card-body {
            padding-top: 0.3rem;
        }

        .card.ecommerce-card img {
            border-radius: 5px;
        }
        .fw-bolder {
            margin-bottom: 0.3px;
        }
        p.teacher-bio {
            line-height: 1.2rem;
        }
        .item-options.text-center {
            margin: 3px 14px;
        }
    </style>
@endsection

@section('content')


    <!-- BEGIN: Content-->

    <section id="faq-search-filter">
        <div class="card faq-search">
            <div class="card-body text-center">
                <!-- main title -->
                <img class="mb-3" src="{{ asset('')}}assets/images/on-demand-session/Vector.png" alt="">
                <h2 class="text-light">Let's answer some questions</h2>

                <!-- subtitle -->
                <p class="card-text text-light mb-2">or choose a teacher to quickly find the help you need</p>

                <!-- search input -->
                <form1 class="faq-search-input">
                    <div class="input-group input-group-merge">
                        <div class="input-group-text">
                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-search"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
                        </div>
                        <input type="text" id="search-session" class="form-control" placeholder="Search faq...">
                    </div>
                </form1>
            </div>
        </div>
    </section>
    <div class="app-content content ecommerce-application">
        <div class="content-overlay"></div>
        <div class="header-navbar-shadow"></div>
        <div class="content-wrapper container-xxl p-0 card">

            <div class="content-body">

                {{-- <h2><b>{{ $subject?->name }}</b></h2> --}}
                {{-- <p>Select your desired teacher from the sections below</p> --}}
                <!-- Wishlist Starts -->
                <section class="row match-height hours-list">

                    @forelse ($session as $course)
                        <div class="col-lg-4 col-md-6 col-sm-12 list">
                            <a class="card card-congratulations" href="{{ route('hours-details', $course->id) }}" style="background: {{ $course->unique_color }}">
                                <div class="card-body text-center">
                                    <img src="../../../app-assets/images/elements/decore-left.png" class="congratulations-img-left" alt="card-img-left">
                                    <img src="../../../app-assets/images/elements/decore-right.png" class="congratulations-img-right" alt="card-img-right">
                                    <div class="avatar avatar-xl bg-primary shadow">
                                        <div class="avatar-content" style="background: #b860f9;">
                                            <i data-feather='clock'></i>
                                        </div>
                                    </div>
                                    <div class="text-center">
                                        <h1 class="mb-1 text-white">{{ $course->title }}</h1>
                                        <p class="card-text text-white m-auto w-75">{{ $course->description }}</p>
                                    </div>
                                </div>
                            </a>
                        </div>
                    @empty
                        <h2>No Session available </h2>
                    @endforelse
                    {{-- <div class="card ecommerce-card">
                        <div class="item-img text-center">
                            <a href="app-ecommerce-details.html">
                                <img src="../../../app-assets/images/pages/eCommerce/1.png" class="img-fluid" alt="img-placeholder" />
                            </a>
                        </div>
                        <div class="card-body">
                            <div class="item-wrapper">
                                <div class="item-rating">
                                    <ul class="unstyled-list list-inline">
                                        <li class="ratings-list-item"><i data-feather="star" class="filled-star"></i></li>
                                        <li class="ratings-list-item"><i data-feather="star" class="filled-star"></i></li>
                                        <li class="ratings-list-item"><i data-feather="star" class="filled-star"></i></li>
                                        <li class="ratings-list-item"><i data-feather="star" class="filled-star"></i></li>
                                        <li class="ratings-list-item"><i data-feather="star" class="unfilled-star"></i></li>
                                    </ul>
                                </div>
                                <div class="item-cost">
                                    <h6 class="item-price">$19.99</h6>
                                </div>
                            </div>
                            <div class="item-name">
                                <a href="app-ecommerce-details.html">Apple Watch Series 5</a>
                            </div>
                            <p class="card-text item-description">
                                On Retina display that never sleeps, so it’s easy to see the time and other important information, without
                                raising or tapping the display. New location features, from a built-in compass to current elevation, help users
                                better navigate their day, while international emergency calling1 allows customers to call emergency services
                                directly from Apple Watch in over 150 countries, even without iPhone nearby. Apple Watch Series 5 is available
                                in a wider range of materials, including aluminium, stainless steel, ceramic and an all-new titanium.
                            </p>
                        </div>
                        <div class="item-options text-center">
                            <button type="button" class="btn btn-primary btn-cart">
                                <span class="add-to-cart">View Details</span>
                            </button>
                        </div>
                    </div> --}}


                </section>
                <!-- Wishlist Ends -->

            </div>
        </div>
    </div>
    <!-- END: Content-->

@endsection

@section('scripts')
  <!-- BEGIN: Page JS-->
  <script src="{{ asset('') }}app-assets/js/scripts/pages/app-ecommerce-wishlist.js"></script>
  <!-- END: Page JS-->

  <script>
    $(document).ready(function(){
    $("#search-session").on("keyup", function() {
        var value = $(this).val().toLowerCase();
        $(".hours-list .list").filter(function() {
        $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
        });
    });
    });
  </script>
@endsection
