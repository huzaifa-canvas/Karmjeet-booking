
@extends('layouts.master')
@section('title','Access Token List | '.config('app.name'))
@section('style')
     <!-- BEGIN: Page CSS-->
     <link rel="stylesheet" type="text/css" href="{{ asset('') }}app-assets/css/core/menu/menu-types/vertical-menu.css">
     <link rel="stylesheet" type="text/css" href="{{ asset('') }}app-assets/css/plugins/forms/form-validation.css">
     <!-- END: Page CSS-->
@endsection

@section('content')

    <!-- END: Main Menu-->

    <!-- BEGIN: Content-->
    <div class="app-content content ">
        <div class="content-overlay"></div>
        <div class="header-navbar-shadow"></div>
        <div class="content-wrapper container-xxl p-0">
            <div class="content-header row">
                <div class="content-header-left col-md-9 col-12 mb-2">
                    <div class="row breadcrumbs-top">
                        <div class="col-12">
                            <h2 class="content-header-title float-start mb-0">API Key</h2>
                        </div>
                    </div>
                </div>
            </div>
            <div class="content-body">
                <section id="ApiKeyPage">
                    <!-- create API key -->
                    <div class="card">
                        <div class="card-header pb-0">
                            <h4 class="card-title">Create an API Key</h4>
                        </div>
                        <div class="row">
                            <div class="col-md-5 order-md-0 order-1">
                                <div class="card-body">
                                    <!-- form -->
                                    <form id="createApiForm" method="POST" action="{{ route('access-token-store') }}">
                                        @csrf
                                        {{-- <div class="mb-2">
                                            <label for="ApiKeyType" class="form-label">Choose the Api key type you want to create</label>
                                            <select class="select2 form-select" id="ApiKeyType">
                                                <option value="">Choose Key Type</option>
                                                <option value="full">Full Control</option>
                                                <option value="modify">Modify</option>
                                                <option value="read-execute">Read &amp; Execute</option>
                                                <option value="folders">List Folder Contents</option>
                                                <option value="read">Read Only</option>
                                                <option value="read-write">Read &amp; Write</option>
                                            </select>
                                        </div> --}}

                                        <div class="mb-2">
                                            <label for="nameApiKey" class="form-label">Name the API key</label>
                                            <input class="form-control" type="text" name="apiKeyName" placeholder="Server Key 1" id="nameApiKey" data-msg="Please enter API key name" />
                                        </div>

                                        <button type="submit" class="btn btn-primary w-100">Create Key</button>
                                    </form>
                                    @if (session('token'))
                                        <div class="bg-light-secondary position-relative rounded p-2 mt-2">
                                            <h6 class="d-flex align-items-center fw-bolder token-parent">
                                                <input readonly id="token" class="me-50 form-control" type="text" value="{{ session('token') }}">
                                                <span class="copy_token"><i data-feather="copy" class="font-medium-4 cursor-pointer"></i></span>
                                            </h6>
                                            <small>Bearer token : One time show </small>
                                        </div>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-7 order-md-1 order-0">
                                <div class="text-center">
                                    <img class="img-fluid text-center" src="{{ asset('') }}app-assets/images/illustration/pricing-Illustration.svg" alt="illustration" width="310" />
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- api key list -->
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">API Key List & Access</h4>
                        </div>
                        <div class="card-body">
                            <p class="card-text">
                                An API key is a simple encrypted string that identifies an application without any principal. They are useful
                                for accessing public data anonymously, and are used to associate API requests with your project for quota and
                                billing.
                            </p>

                            <div class="row gy-2">
                                @forelse ($tokens as $token)
                                    <div class="col-12">
                                        <div class="bg-light-secondary position-relative rounded p-2">
                                            <div class="dropdown dropstart btn-pinned">
                                                <a class="btn btn-icon rounded-circle hide-arrow dropdown-toggle p-0" href="javascript:void(0)" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
                                                    <i data-feather="more-vertical" class="font-medium-4"></i>
                                                </a>
                                                <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                                                    {{-- <li>
                                                        <a class="dropdown-item d-flex align-items-center" href="#">
                                                            <i data-feather="edit-2" class="me-50"></i><span>Edit</span>
                                                        </a>
                                                    </li> --}}
                                                    <li>
                                                        <a class="dropdown-item d-flex align-items-center" href="{{ route('access-token-delete', $token->id) }}">
                                                            <i data-feather="trash-2" class="me-50"></i><span>Delete</span>
                                                        </a>
                                                    </li>
                                                </ul>
                                            </div>
                                            <div class="d-flex align-items-center flex-wrap">
                                                <h4 class="mb-1 me-1">{{ $token->name }}</h4>
                                                <span class="badge badge-light-primary mb-1">Full Access</span>
                                            </div>
                                            <h6 class="d-flex align-items-center fw-bolder">
                                                <span>Created on : {{ $token->created_at->format('d M Y, H:i:s') }}</span>
                                                {{-- <span class="me-50">23eaf7f0-f4f7-495e-8b86-fad3261282ac</span> --}}
                                                {{-- <span><i data-feather="copy" class="font-medium-4 cursor-pointer"></i></span> --}}
                                            </h6>
                                            <span>Last used : {{ isset($token->last_used_at) ? $token->last_used_at->format('d M Y, H:i:s') : "Not used" }}</span>
                                        </div>
                                    </div>
                                @empty
                                <div class="col-12">
                                    <div class="bg-light-secondary position-relative rounded p-2">
                                        <div class="d-flex align-items-center flex-wrap">
                                            <h4 class="mb-1 me-1">No Key Found</h4>
                                        </div>
                                    </div>
                                </div>
                                @endforelse

                            </div>
                        </div>
                    </div>
                </section>

            </div>
        </div>
    </div>
    <!-- END: Content-->

    <div class="sidenav-overlay"></div>
    <div class="drag-target"></div>

@endsection

@section('scripts')

    <!-- BEGIN: Page JS-->
    <script src="{{ asset('') }}app-assets/js/scripts/pages/page-api-key.js"></script>
    <!-- END: Page JS-->

    <script>
           $(document).ready(function() {
                $('.copy_token').click(function() {
                    var copyText = $('#token');
                    copyText.select();
                    document.execCommand('copy');
                    toastr.success("Bearer Token Copied");

                });
            });
    </script>

    <script>
        $(window).on('load', function() {
            if (feather) {
                feather.replace({
                    width: 14,
                    height: 14
                });
            }
        })
    </script>
@endsection
