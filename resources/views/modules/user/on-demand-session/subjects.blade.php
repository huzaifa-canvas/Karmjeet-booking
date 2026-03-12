
@extends('layouts.master')
@section('title','Subjects List | '.config('app.name'))
@section('style')
    {{-- <link rel="stylesheet" type="text/css" href="{{ asset('') }}app-assets/vendors/css/vendors.min.css"> --}}
     <!-- BEGIN: Page CSS-->
     {{-- <link rel="stylesheet" type="text/css" href="{{ asset('') }}app-assets/css/core/menu/menu-types/vertical-menu.css"> --}}
     {{-- <link rel="stylesheet" type="text/css" href="{{ asset('') }}app-assets/css/plugins/extensions/ext-component-sliders.css"> --}}
     <link rel="stylesheet" type="text/css" href="{{ asset('') }}app-assets/css/pages/app-ecommerce.css">
     {{-- <link rel="stylesheet" type="text/css" href="{{ asset('') }}app-assets/css/plugins/extensions/ext-component-toastr.css"> --}}
     <!-- END: Page CSS-->

    <style>

        footer.footer.footer-static.footer-light {
            /* display: none; */
            display: inline-flex;
            visibility: hidden;
        }

        @media (min-width: 1200px){
            nav.header-navbar {
                display: none;
            }
            html .content.app-content {
                padding: calc(1rem + 1.45rem + 1.3rem) 2rem 0;
            }
        }

        .header-navbar-shadow {
            display: none !important;
        }

        .ecommerce-application .grid-view {
            display: grid;
            /* display: flex; */
            /* flex-wrap: wrap; */
            /* column-count: 2; */
            grid-template-columns: 1fr 1fr 1fr 1fr;
            /* column-gap: 3rem; */
            margin-right: 5rem;
            margin-left: 5rem;
            justify-content: center;
            align-items: center;
        }

        html body{
            background: #2F63DA;
        }

        span.input-group-text.search {
            background-color: #118CFF;
            border-radius: 6px !important;
        }

        input#shop-search {
            margin-right: 11px;
            border-radius: 6px;
        }

        ::-webkit-scrollbar-thumb {
            background: #b860f9;
            border-radius: 20px;
        }

        body:before {
            content: '';
            background-image: url("{{asset('/')}}assets/images/on-demand-session/Vector-top.png");
            width: 82px;
            height: 76px;
            position: absolute;
            background-repeat: no-repeat;
            right: 0;
        }

        body .main-menu:after {
            content: '';
            background-image: url("{{asset('/')}}assets/images/on-demand-session/Vector-bottom.png");
            width: 81px;
            height: 91px;
            position: absolute;
            background-repeat: no-repeat;
            bottom: 0;
            right: -80px;
        }

        .main-menu{
            overflow: unset;
        }

        img.img-fluid.card-img-top {
            border: 1px solid rgb(255, 255, 255);
            padding: 8px;
            border-radius: 0.428rem;
            max-height: 170px;
            object-fit: cover;
            object-position: top;
        }

        @media (min-width: 992px){
            body .content-detached.content-right .content-body {
                margin-left: calc(0px + 0rem);
            }
        }

        @media (max-width: 991.98px){
            .ecommerce-application .grid-view {
                grid-template-columns: 1fr 1fr;
            }
        }

        @media (max-width: 575.98px){
            .ecommerce-application .grid-view, .ecommerce-application .grid-view.wishlist-items {
                grid-template-columns: 1fr;
            }
        }

    </style>
@endsection

@section('content')


    <!-- BEGIN: Content-->
    <div class="app-content content ecommerce-application">
        <div class="content-overlay"></div>
        <div class="header-navbar-shadow"></div>
        <div class="content-wrapper container-xxl p-0">

            <div class="content-detached content-right">
                <div class="content-body">

                    <!-- background Overlay when sidebar is shown  starts-->
                    <div class="body-content-overlay"></div>
                    <!-- background Overlay when sidebar is shown  ends-->

                    <!-- E-commerce Search Bar Starts -->
                    <section id="ecommerce-searchbar" class="ecommerce-searchbar">
                        <div class="row mt-1 justify-content-center">
                            <div class="col-sm-6 d-flex flex-column align-items-center">
                                <img class="mb-3" src="{{ asset('')}}assets/images/on-demand-session/Vector.png" alt="">
                                <h1 class="text-light">Regular Sessions</h1>
                                <p class="text-light">Select a subject & book a session with your desired teacher.</p>
                                <div class="input-group input-group-merge">
                                    <input type="text" class="form-control search-product" id="subject-search" placeholder="Search Product" aria-label="Search..." aria-describedby="shop-search" />
                                    <span class="input-group-text search"><i data-feather="search" class="text-light"></i></span>
                                </div>
                            </div>
                        </div>
                    </section>
                    <!-- E-commerce Search Bar Ends -->

                    <!-- E-commerce Products Starts -->
                    <section id="ecommerce-products" class="grid-view">

                        @forelse ($subjects as $subject)
                            <div class="card1 ecommerce-card1 mb-2">
                                <div class="item-img text-center">
                                    <a href="{{ route('courses', $subject->slug) }}">
                                        <img class="img-fluid card-img-top" src="{{ asset( ($subject?->image_url) ? $subject?->image_url : 'assets/images/no-preview.png' ) }}" alt="img-placeholder" /></a>
                                </div>
                                <div class="item-options text-center mt-1">

                                    <a href="{{ route('courses', $subject->slug) }}" class="text-light">
                                        <span class="add-to-cart">{{ $subject->name }}</span>
                                    </a>
                                </div>
                            </div>
                        @empty
                            <div class="card1 ecommerce-card1 mb-2">
                                <div class="item-options text-center mt-1">
                                    <a href="javascript:;" class="text-light">
                                        <span class="add-to-cart">No Subject added</span>
                                    </a>
                                </div>
                            </div>
                        @endforelse
                        {{-- <div class="card1 ecommerce-card1 mb-2">
                            <div class="item-img text-center">
                                <a href="app-ecommerce-details.html">
                                    <img class="img-fluid card-img-top" src="{{ asset('')}}assets/images/on-demand-session/math.png" alt="img-placeholder" /></a>
                            </div>
                            <div class="item-options text-center mt-1">

                                <a href="#" class="text-light">
                                    <span class="add-to-cart">Maths</span>
                                </a>
                            </div>
                        </div>

                        <div class="card1 ecommerce-card1 mb-2">
                            <div class="item-img text-center">
                                <a href="app-ecommerce-details.html">
                                    <img class="img-fluid card-img-top" src="{{ asset('')}}assets/images/on-demand-session/english.png" alt="img-placeholder" />
                                </a>
                            </div>
                            <div class="item-options text-center mt-1">

                                <a href="#" class="text-light">

                                    <span class="add-to-cart">English and Literature</span>
                                </a>
                            </div>
                        </div>

                        <div class="card1 ecommerce-card1 mb-2">
                            <div class="item-img text-center">
                                <a href="app-ecommerce-details.html"><img class="img-fluid card-img-top" src="{{ asset('')}}assets/images/on-demand-session/science.png" alt="img-placeholder" /></a>
                            </div>

                            <div class="item-options text-center mt-1">

                                <a href="#" class="text-light">

                                    <span class="add-to-cart">Science</span>
                                </a>
                            </div>
                        </div>

                        <div class="card1 ecommerce-card1 mb-2">
                            <div class="item-img text-center">
                                <a href="app-ecommerce-details.html">
                                    <img class="img-fluid card-img-top" src="{{ asset('')}}assets/images/on-demand-session/social.png" alt="img-placeholder" /></a>
                            </div>

                            <div class="item-options text-center mt-1">

                                <a href="#" class="text-light">

                                    <span class="add-to-cart">Social Studies</span>
                                </a>
                            </div>
                        </div>
                        <div class="card1 ecommerce-card1 mb-2">
                            <div class="item-img text-center">
                                <a href="app-ecommerce-details.html">
                                    <img class="img-fluid card-img-top" src="{{ asset('')}}assets/images/on-demand-session/worldlanguage.png" alt="img-placeholder" /></a>
                            </div>

                            <div class="item-options text-center mt-1">

                                <a href="#" class="text-light">

                                    <span class="add-to-cart">World Language</span>
                                </a>
                            </div>
                        </div>
                        <div class="card1 ecommerce-card1 mb-2">
                            <div class="item-img text-center">
                                <a href="app-ecommerce-details.html">
                                    <img class="img-fluid card-img-top" src="{{ asset('')}}assets/images/on-demand-session/examination.png" alt="img-placeholder" /></a>
                            </div>

                            <div class="item-options text-center mt-1">

                                <a href="#" class="text-light">

                                    <span class="add-to-cart">Examination Preparation</span>
                                </a>
                            </div>
                        </div> --}}
                    </section>
                    <!-- E-commerce Products Ends -->

                    <!-- E-commerce Pagination Starts -->
                    {{-- <section id="ecommerce-pagination">
                        <div class="row">
                            <div class="col-sm-12">
                                <nav aria-label="Page navigation example">
                                    <ul class="pagination justify-content-center mt-2">
                                        <li class="page-item prev-item"><a class="page-link" href="#"></a></li>
                                        <li class="page-item active"><a class="page-link" href="#">1</a></li>
                                        <li class="page-item"><a class="page-link" href="#">2</a></li>
                                        <li class="page-item"><a class="page-link" href="#">3</a></li>
                                        <li class="page-item" aria-current="page"><a class="page-link" href="#">4</a></li>
                                        <li class="page-item"><a class="page-link" href="#">5</a></li>
                                        <li class="page-item"><a class="page-link" href="#">6</a></li>
                                        <li class="page-item"><a class="page-link" href="#">7</a></li>
                                        <li class="page-item next-item"><a class="page-link" href="#"></a></li>
                                    </ul>
                                </nav>
                            </div>
                        </div>
                    </section> --}}
                    <!-- E-commerce Pagination Ends -->

                </div>
            </div>
        </div>
    </div>
    <!-- END: Content-->

@endsection

@section('scripts')
  <!-- BEGIN: Page Vendor JS-->
  {{-- <script src="{{ asset('') }}app-assets/vendors/js/extensions/wNumb.min.js"></script> --}}
  {{-- <script src="{{ asset('') }}app-assets/vendors/js/extensions/nouislider.min.js"></script> --}}
  {{-- <script src="{{ asset('') }}app-assets/vendors/js/extensions/toastr.min.js"></script> --}}
  <!-- END: Page Vendor JS-->

  <!-- BEGIN: Page JS-->
  <script src="{{ asset('') }}app-assets/js/scripts/pages/app-ecommerce.js"></script>
  <!-- END: Page JS-->

  <script>

    $(document).ready(function(){
    $("#subject-search").on("keyup", function() {
        var value = $(this).val().toLowerCase();
        $("#ecommerce-products .card1").filter(function() {
        $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
        });
    });
    });

  </script>
@endsection
