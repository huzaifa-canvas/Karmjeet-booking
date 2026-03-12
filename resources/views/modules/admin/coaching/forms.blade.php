@extends('layouts.master')
@section('Title',($isEdit) ? "Edit Session" : 'Add Session'.' | '.config('app.name'))

@section('style')
    <link rel="stylesheet" type="text/css" href="{{ asset('/') }}app-assets/vendors/css/forms/select/select2.min.css">
    {{-- <link rel="stylesheet" type="text/css" href="{{ asset('/') }}app-assets/vendors/css/pickers/pickadate/pickadate.css"> --}}
    <link rel="stylesheet" type="text/css" href="{{ asset('/') }}app-assets/vendors/css/pickers/flatpickr/flatpickr.min.css">
    <link rel="stylesheet" type="text/css" href="{{ asset('/') }}app-assets/css/plugins/forms/pickers/form-flat-pickr.css">
    {{-- <link rel="stylesheet" type="text/css" href="{{ asset('/') }}app-assets/css/plugins/forms/pickers/form-pickadate.css"> --}}
    <style>
        .form-control:disabled {
            background-color: #efefef;
        }
        .select2-container--default.select2-container--disabled li.select2-selection__choice {
            background: #efefef !important;
            border-color: #cbcbcb !important;
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
                                    <form class="form form-vertical" method="POST" action="{{ ($isEdit) ?  route('coaching-update', $id) : route('coaching-store') }}" enctype="multipart/form-data">
                                        @csrf
                                        <div class="row">
                                            <div class="col-md-3  col-12">
                                                <div class="mb-1">
                                                    <label class="form-label" for="title" >Title</label>
                                                    <input type="text" id="title" class="form-control" value="{{ ($isEdit) ? $coaching?->title : old('title')  }}" name="title" placeholder="title" />
                                                    @error('title')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                                                </div>
                                            </div>

                                            <div class="col-md-3 col-12">
                                                <div class="mb-1">
                                                    <label class="form-label" for="status">Session Status</label>
                                                    <select class="form-select" id="status" name="status" required>
                                                        <option value="1" {{ ($isEdit && $coaching?->status) ? 'selected' : '' }}>active</option>
                                                        <option value="0" {{ ($isEdit && !$coaching?->status) ? 'selected': '' }}>Inactive</option>
                                                    </select>
                                                    @error('status')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                                                </div>
                                            </div>

                                            <div class="col-md-3 col-12">
                                                <div class="mb-1">
                                                    <label class="form-label" for="subject_id">Select Class</label>
                                                    <select class="form-select" id="class_id" name="class_id" required>
                                                        @foreach ($classes as $class)
                                                            <option value="{{ $class?->id }}" {{ ($isEdit && $class?->id == $coaching?->class_id) ? 'selected' : '' }}>{{ $class?->name }}</option>
                                                        @endforeach
                                                    </select>
                                                    @error('class_id')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                                                </div>
                                            </div>

                                            <div class="col-md-3 col-12">
                                                <div class="mb-1">
                                                    <label class="form-label" for="subject_id">Select Subject</label>
                                                    <select class="form-select" id="subject_id" name="subject_id" required>
                                                        @foreach ($subjects as $subject)
                                                            <option value="{{ $subject?->id }}" {{ ($isEdit && $subject?->id == $coaching?->subject_id) ? 'selected' : '' }}>{{ $subject?->name }}</option>
                                                        @endforeach
                                                    </select>
                                                    @error('subject_id')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                                                </div>
                                            </div>

                                            {{-- <div class="col-md-6 mb-1">
                                                <label class="form-label" for="select-day">Select Days</label>
                                                <select class="form-select" id="select-day" name="days[]" multiple>

                                                    @php $days = ["Monday","Tuesday","Wednesday","Thursday","Friday","Saturday","Sunday"];
                                                        $returnDays = ($isEdit) ? json_decode($coaching?->days) : $days;
                                                    @endphp
                                                    @foreach ($days as $day)
                                                        <option value="{{ $day }}" {{ (in_array($day, $returnDays)) ? "selected" : '' }} >{{ $day }}</option>
                                                    @endforeach

                                                </select>
                                                @error('days')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                                            </div> --}}

                                            {{-- <div class="col-md-3 mb-1">
                                                <label class="form-label" for="start_time">Start Time</label>
                                                <input type="text" id="start_time" value="{{ ($isEdit) ? $coaching?->start_time : (old('start_time') ? old('start_time') : "10:00")  }}"  name="start_time" class="form-control flatpickr-time text-start" placeholder="HH:MM" />
                                                @error('start_time')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                                            </div> --}}

                                            {{-- <div class="col-md-3 mb-1">
                                                <label class="form-label" for="end_time">End Time</label>
                                                <input type="text" id="end_time" value="{{ ($isEdit) ? $coaching?->end_time : (old('end_time') ? old('end_time') : "12:00") }}" name="end_time" class="form-control flatpickr-time text-start" placeholder="HH:MM" />
                                                @error('end_time')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                                            </div> --}}

                                            {{-- <div class="col-md-3 mb-1">
                                                <label class="form-label" for="duration">Select duration</label>
                                                <select class="form-select" id="duration" name="duration" required>
                                                    @php $durations = [15, 20, 25, 30, 40, 45, 60, 90, 120] @endphp
                                                    @foreach ($durations as $duration)
                                                        <option value="{{ $duration }}" {{ ($isEdit && $coaching?->duration == $duration) ? "selected" : '' }} >{{ $duration }} min</option>
                                                    @endforeach

                                                </select>
                                                @error('duration')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                                            </div> --}}

                                            <div class="col-md-3  col-12">
                                                <div class="mb-1">
                                                    <label class="form-label" for="price_per_session">Price Per Session $</label>
                                                    <input type="number" id="price_per_session" class="form-control" value="{{ ($isEdit) ? $coaching?->price_per_session : (old('price_per_session') ? old('price_per_session') : "0")  }}" name="price_per_session" placeholder="Price Per Session" />
                                                    @error('price_per_session')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                                                </div>
                                            </div>

                                            <div class="col-md-3 mb-1">
                                                <div class="mb-1">
                                                    <label class="form-label" for="fp-multiple">Blackout dates</label>
                                                    <input type="text" id="fp-multiple" class="form-control flatpickr-multiple" value="{{ ($isEdit) ? $coaching?->blackout_dates : old('blackout_dates') }}" name="blackout_dates" placeholder="YYYY-MM-DD"  />
                                                    @error('blackout_dates')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                                                </div>
                                            </div>

                                            <div class="col-md-3  col-12">
                                                <div class="mb-1">
                                                    <label class="form-label" for="session_limit">Session limit</label>
                                                    <input type="number" id="session_limit" class="form-control" value="{{ ($isEdit) ? $coaching?->session_limit : old('session_limit')  }}" name="session_limit" placeholder="Session limit eg. 4, 5, 6" />
                                                    @error('session_limit')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                                                </div>
                                            </div>

                                            <div class="col-md-3  col-12">
                                                <div class="mb-1">
                                                    <label class="form-label" for="month_limit">Month limit</label>
                                                    <input type="number" id="month_limit" class="form-control" value="{{ ($isEdit) ? $coaching?->month_limit : old('month_limit')  }}" name="month_limit" placeholder="month limit eg. 1, 3, 6, 12" />
                                                    @error('month_limit')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                                                </div>
                                            </div>

                                            {{-- <div class="col-md-3  col-12">
                                                <div class="mb-1">
                                                    <label class="form-label" for="start_time">Start Time</label>
                                                    <input type="text" id="start_time"  class="form-control flatpickr-time text-start" placeholder="HH:MM" value="{{ ($isEdit) ? $coaching?->start_time : old('start_time')  }}" name="start_time"/>
                                                    @error('start_time')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                                                </div>
                                            </div>

                                            <div class="col-md-3  col-12">
                                                <div class="mb-1">
                                                    <label class="form-label" for="end_time">End Time</label>
                                                    <input type="text" id="end_time"  class="form-control flatpickr-time text-start" placeholder="HH:MM" value="{{ ($isEdit) ? $coaching?->end_time : old('end_time')  }}" name="end_time" />
                                                    @error('end_time')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                                                </div>
                                            </div> --}}

                                            @if (auth()->user()->user_role == "admin")
                                                <div class="col-md-3 col-12">
                                                    <div class="mb-1">
                                                        <label class="form-label" for="coach_name">Select Teacher</label>
                                                        <select class="form-select" id="coach_name" name="coach_name" required>
                                                            <optgroup label="Teacher">
                                                                @foreach ($teachers as $teacher)
                                                                    <option value="{{ $teacher?->id }}" {{ ($isEdit && $teacher?->id == $coaching?->user_id) ? 'selected' : '' }}>{{ $teacher?->name }}</option>
                                                                    @endforeach
                                                                </optgroup>
                                                                <optgroup label="Admin">
                                                                <option value="{{ auth()->user()->id }}" {{ ($isEdit && auth()->user()->id == $coaching?->user_id) ? 'selected' : '' }}>{{ auth()->user()->name }}</option>
                                                            </optgroup>
                                                        </select>
                                                        @error('coach_name')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                                                    </div>
                                                </div>
                                            @else
                                                <div class="col-md-3  col-12">
                                                    <div class="mb-1">
                                                        <label class="form-label" for="coach_name">Teacher name</label>
                                                        <input type="text" id="coach_name" class="form-control" value="{{ ($isEdit) ? $coaching?->coach_name : (old('coach_name') ? old('coach_name') : Auth::user()->name) }}" name="coach_name" placeholder="Coach name" />
                                                        @error('coach_name')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                                                    </div>
                                                </div>
                                            @endif

                                            <div class="col-md-5  col-12">
                                                <div class="mb-1">
                                                    <label class="form-label" for="coach_bio">Teacher bio</label>
                                                    <input type="text" id="coach_bio" class="form-control" value="{{ ($isEdit) ? $coaching?->coach_bio : old('coach_bio') }}" name="coach_bio" placeholder="Based in London, Uncode is a blog by" />
                                                    @error('coach_bio')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                                                </div>
                                            </div>

                                            <div class="col-md-4  col-12">
                                                <div class="mb-1">
                                                    <label class="form-label" for="metting_link">Metting link</label>
                                                    <input type="link" id="metting_link" class="form-control" value="{{ ($isEdit) ? $coaching?->metting_link : old('metting_link') }}" name="metting_link" placeholder="https://zoom.us/, https://meet.google.com/" />
                                                    @error('metting_link')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
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
                                                            <div id="imagePreview" style="background-image: url({{ asset( (isset($coaching?->image_id)) ? $coaching?->image_id : 'assets/images/no-preview.png' ) }});">
                                                            </div>
                                                        </div>
                                                        @error('image')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror

                                                    </div>

                                                </div>
                                            </div>

                                            <div class="col-md-10  col-12">
                                                <div class="mb-1">
                                                    <label class="form-label" for="description">Description</label>
                                                    <textarea class="form-control" name="description" id="description" placeholder="Session description" rows="8">{{ ($isEdit) ? $coaching?->description : old('description')  }}</textarea>
                                                    @error('description')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                                                </div>
                                            </div>

                                            {{-- <div class="col-md-4 mb-1">
                                                <label class="form-label" for="session">Session</label>
                                                <select class="form-select" id="session" multiple name="session[]">

                                                    @php
                                                        $returnSessions = ($isEdit) ? ($coaching?->session) : [];
                                                    @endphp

                                                    @forelse ($returnSessions as $session)
                                                        <option value="{{ $session }}" selected>{{ $session }}</option>
                                                    @empty
                                                        <option value="10:00-10:15" selected>10:00 - 10:15</option>
                                                        <option value="10:15-10:30" selected>10:15 - 10:30</option>
                                                        <option value="10:30-10:45" selected>10:30 - 10:45</option>
                                                        <option value="10:45-11:00" selected>10:45 - 11:00</option>
                                                        <option value="11:00-11:15" selected>11:00 - 11:15</option>
                                                        <option value="11:15-11:30" selected>11:15 - 11:30</option>
                                                        <option value="11:30-11:45" selected>11:30 - 11:45</option>
                                                        <option value="11:45-12:00" selected>11:45 - 12:00</option>
                                                    @endforelse
                                                </select>
                                                @error('session')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                                            </div> --}}


                                            @php $days = ["Monday","Tuesday","Wednesday","Thursday","Friday","Saturday","Sunday"];
                                                $returnDays = ($isEdit) ? $coaching?->getslots->pluck('days')->toArray() : $days;
                                            @endphp

                                            @foreach ($days as $key => $day)

                                                @php
                                                    // $slot = [];
                                                    if (in_array($day, $returnDays) && $isEdit) {
                                                        $slotKey =  array_search($day, array_column($coaching?->getslots->toArray(), 'days'));
                                                        $slot = $coaching?->getslots[$slotKey];
                                                    }
                                                @endphp

                                                <div class="col-md-12 col-12 row days_slots {{ $day }}">
                                                    <div class="col-md-2 mt-2">
                                                        <div class="form-check form-check-inline">
                                                            <label class="form-label" for="days-{{ $key }}">{{ $day }}</label>
                                                            <input class="form-check-input days" type="checkbox" name="days[{{ $key }}]" id="days-{{ $key }}" value="{{ $day }}" {{ (in_array($day, $returnDays)) ? "checked" : '' }}>
                                                        </div>
                                                    </div>

                                                    <div class="col-md-2 mb-1">
                                                        <label class="form-label" for="start_time-{{ $key }}">Start Time</label>
                                                        <input type="text" id="start_time-{{ $key }}" value="{{ ($isEdit) ? $slot?->start_time : (old('start_time'.$key) ? old('start_time'.$key) : "10:00")  }}"  name="start_time[{{ $key }}]" class="form-control flatpickr-time text-start start_time" placeholder="HH:MM" />
                                                        @error('start_time')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                                                    </div>

                                                    <div class="col-md-2 mb-1">
                                                        <label class="form-label" for="end_time-{{ $key }}">End Time</label>
                                                        <input type="text" id="end_time-{{ $key }}" value="{{ ($isEdit) ? $slot?->end_time : (old('end_time'.$key) ? old('end_time'.$key) : "12:00") }}" name="end_time[{{ $key }}]" class="form-control flatpickr-time text-start end_time" placeholder="HH:MM" />
                                                        @error('end_time')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                                                    </div>

                                                    <div class="col-md-2 mb-1">
                                                        <label class="form-label" for="duration-{{ $key }}">Select duration</label>
                                                        <select class="form-select duration" id="duration-{{ $key }}" name="duration[{{ $key }}]" required>
                                                            @php $durations = [15, 20, 25, 30, 40, 45, 60, 90, 120] @endphp
                                                            @foreach ($durations as $duration)
                                                                <option value="{{ $duration }}" {{ ($isEdit && $slot?->duration == $duration) ? "selected" : '' }} >{{ $duration }} min</option>
                                                                {{-- <option value="{{ $duration }}" {{ (old('duration'.$key) && old('duration'.$key) == $duration) ? "selected" : ($isEdit && $slot?->duration == $duration ? "selected" : '') }} >{{ $duration }} min</option> --}}
                                                            @endforeach

                                                        </select>
                                                        @error('duration')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                                                    </div>

                                                    <div class="col-md-4 mb-1">
                                                        <label class="form-label" for="session_{{ $day }}">Session</label>
                                                        <select class="form-select session" id="session_{{ $day }}" multiple name="session[{{ $key }}][]">

                                                            @forelse (($isEdit) ? $slot?->session : [] as $session)
                                                                <option value="{{ $session }}" selected>{{ $session }}</option>
                                                            @empty
                                                                <option value="10:00-10:15" selected>10:00 - 10:15</option>
                                                                <option value="10:15-10:30" selected>10:15 - 10:30</option>
                                                                <option value="10:30-10:45" selected>10:30 - 10:45</option>
                                                                <option value="10:45-11:00" selected>10:45 - 11:00</option>
                                                                <option value="11:00-11:15" selected>11:00 - 11:15</option>
                                                                <option value="11:15-11:30" selected>11:15 - 11:30</option>
                                                                <option value="11:30-11:45" selected>11:30 - 11:45</option>
                                                                <option value="11:45-12:00" selected>11:45 - 12:00</option>
                                                            @endforelse
                                                        </select>
                                                        @error('session')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
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
    <script src="{{ asset('/') }}app-assets/vendors/js/forms/select/select2.full.min.js"></script>
    <script src="{{ asset('/') }}app-assets/vendors/js/pickers/pickadate/picker.js"></script>
    <script src="{{ asset('/') }}app-assets/vendors/js/pickers/pickadate/picker.date.js"></script>
    <script src="{{ asset('/') }}app-assets/vendors/js/pickers/pickadate/picker.time.js"></script>
    {{-- <script src="{{ asset('/') }}app-assets/vendors/js/pickers/pickadate/legacy.js"></script> --}}
    <script src="{{ asset('/') }}app-assets/vendors/js/pickers/flatpickr/flatpickr.min.js"></script>

    {{-- <script src="{{ asset('/') }}app-assets/js/scripts/forms/pickers/form-pickers.js"></script> --}}

    <!-- END: Page Vendor JS-->


    <script>

    var timePickr = $('.flatpickr-time'),
     multiPickr = $('.flatpickr-multiple');

                // Time
        if (timePickr.length) {
            timePickr.flatpickr({
            enableTime: true,
            noCalendar: true,
            });
        }

        // Multiple Dates
        if (multiPickr.length) {
            multiPickr.flatpickr({
                weekNumbers: true,
                mode: 'multiple',
                minDate: 'today',
                dateFormat: 'Y/m/d',
            });
        }

        $(document).on( 'change', '.duration, .start_time, .end_time', function(e){

            var $this       =   $(this).parents('.days_slots');
            var startTime   =   $this.find('.start_time').val();
            var endTime     =   $this.find('.end_time').val();
            var duration    =   $this.find('.duration').val();

            $.ajaxSetup({headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}});
            url = "{{ route('create-slot') }}"

            $.ajax({
                url: url,
                type: "POST",
                data: {start_time : startTime, end_time : endTime, duration : duration},
            }).done(function (data) {

                if (data.status) {

                    var html = '';
                    $.each(data.slots, function(key,val){
                        html += '<option value="'+val.start +'-'+val.end+'" selected>'+val.start +' - '+val.end+'</option>';
                    });
                    $this.find('.session').html(html);

                } else {
                    toastr.error(data.message);
                }
            });

            $('#calendar').find('.active').removeClass('active');
            $(this).addClass('active');
        });

        $(document).ready(function() {

            $(document).on( 'change', '.days', function(e){
                disabeldField();
            })

            function disabeldField(params) {
                $('.days').each(function(i, obj) {
                    var val = $(this).val();
                    var ischeck = $(this).is(':checked');
                    if(!ischeck){
                        $(this).parents('.'+val).find('input, select').prop('disabled', true);
                    }else{
                        $(this).parents('.'+val).find('input, select').prop('disabled', false);
                    }
                    $(this).prop('disabled', false);
                });
            }

            disabeldField();

            $('#session_Monday, #session_Tuesday, #session_Wednesday, #session_Thursday, #session_Friday, #session_Saturday, #session_Sunday').select2({
                // tags: true
            });
        });
    </script>

@endsection

