@extends('layouts.master')
@section('title',($isEdit) ? "Edit On-Demand Session" : 'Add On-Demand Session'.' | '.config('app.name'))
@section('style')

    <link rel="stylesheet" type="text/css" href="{{ asset('/') }}app-assets/vendors/css/pickers/flatpickr/flatpickr.min.css">
    <link rel="stylesheet" type="text/css" href="{{ asset('/') }}app-assets/css/plugins/forms/pickers/form-flat-pickr.css">
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
                            <h2 class="content-header-title float-start mb-0"> {{ ($isEdit) ? "Edit On-Demand Session" : 'Add On-Demand Session' }}</h2>
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
                                    <form class="form form-vertical" method="POST" action="{{ ($isEdit) ?  route('schedule-session-update', $id) : route('schedule-session-store') }}" enctype="multipart/form-data" >
                                        @csrf
                                        <div class="row">
                                            {{-- <div class="col-md-4 col-12">
                                                <div class="mb-1">
                                                    <label class="form-label" for="grade_id">Select Grade</label>
                                                    <select class="form-select" id="grade_id" name="grade_id" required>
                                                        @foreach ($grades as $grade)
                                                            <option value="{{ $grade?->id }}" {{ ($isEdit && $grade?->id == $session?->grade_id) ? 'selected' : '' }}>{{ $grade?->name }}</option>
                                                        @endforeach
                                                    </select>
                                                    @error('grade_id')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                                                </div>
                                            </div> --}}

                                            <div class="col-md-6  col-12">
                                                <div class="mb-1">
                                                    <label class="form-label" for="title" >Title</label>
                                                    <input type="text" id="title" class="form-control" value="{{ ($isEdit) ? $session?->title : old('title')  }}" name="title" placeholder="10 hours: $50/ hour" />
                                                    @error('title')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                                                </div>
                                            </div>

                                            <div class="col-md-6  col-12">
                                                <div class="mb-1">
                                                    <label class="form-label" for="status">Session Status</label>
                                                    <select class="form-select" id="status" name="status" required>
                                                        <option value="1" {{ ($isEdit && $session?->status) ? 'selected' : '' }}>active</option>
                                                        <option value="0" {{ ($isEdit && !$session?->status) ? 'selected': '' }}>Inactive</option>
                                                    </select>
                                                    @error('status')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                                                </div>
                                            </div>

                                            <div class="col-md-3  col-12">
                                                <div class="mb-1">
                                                    <label class="form-label" for="plan_price">Plan Price $</label>
                                                    <input type="number" id="plan_price" class="form-control" value="{{ ($isEdit) ? $session?->plan_price : (old('plan_price') ? old('plan_price') : "0")  }}" name="plan_price" placeholder="Price Per Session" />
                                                    @error('plan_price')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                                                </div>
                                            </div>

                                            <div class="col-md-3  col-12">
                                                <div class="mb-1">
                                                    <label class="form-label" for="plan_hours">Plan hours</label>
                                                    <input type="number" id="plan_hours" class="form-control" value="{{ ($isEdit) ? $session?->plan_hours : (old('plan_hours') ? old('plan_hours') : "")  }}" name="plan_hours" placeholder="Price Per Session" />
                                                    @error('plan_hours')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                                                </div>
                                            </div>

                                            <div class="col-md-3  col-12">
                                                <div class="mb-1">
                                                    <label class="form-label" for="unique_color">Unique color</label>
                                                    <input type="color" id="unique_color" class="form-control" value="{{ ($isEdit) ? $session?->unique_color : (old('unique_color') ? old('unique_color') : "")  }}" name="unique_color" />
                                                    @error('unique_color')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                                                </div>
                                            </div>

                                            {{-- <div class="col-md-4  col-12">
                                                <div class="mb-1">
                                                    <label class="form-label" for="customize_hour">Customize Hour</label>
                                                    <input type="number" id="customize_hour" class="form-control" value="{{ ($isEdit) ? $session?->customize_hour : (old('customize_hour') ? old('customize_hour') : "")  }}" name="customize_hour" placeholder="eg. 1-10" />
                                                    @error('customize_hour')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                                                </div>
                                            </div> --}}

                                            <div class="col-md-2  col-12">
                                                <div class="mb-1">
                                                    <div class="form-check">
                                                       <label class="form-label" for="recommended">Recommended</label>
                                                        <input {{ ($isEdit && $session?->recommended) ? 'checked' : '' }}  type="checkbox" class="form-check-input select-all" name="recommended" id="recommended" value="1">
                                                        {{-- <label class="form-check-label" for="select-all">Recommended</label> --}}
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-md-3 mb-1">
                                                <div class="mb-1">
                                                    <label class="form-label" for="fp-multiple">Blackout dates</label>
                                                    <input type="text" id="fp-multiple" class="form-control flatpickr-multiple" value="{{ ($isEdit) ? $session?->blackout_dates : old('blackout_dates') }}" name="blackout_dates" placeholder="YYYY-MM-DD"  />
                                                    @error('blackout_dates')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                                                </div>
                                            </div>

                                            <div class="col-md-2  col-12">
                                                <div class="mb-1">
                                                    <label class="form-label" for="month_limit">Month limit</label>
                                                    <input type="number" id="month_limit" class="form-control" value="{{ ($isEdit) ? $session?->month_limit : old('month_limit')  }}" name="month_limit" placeholder="month limit eg. 1, 3, 6, 12" />
                                                    @error('month_limit')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                                                </div>
                                            </div>

                                             <div class="col-md-2 mb-1">
                                                <label class="form-label" for="start_time">Start Time</label>
                                                <input type="text" id="start_time" value="{{ ($isEdit) ? $session?->start_time : (old('start_time') ? old('start_time') : "10:00")  }}"  name="start_time" class="form-control flatpickr-time text-start" placeholder="HH:MM" />
                                                @error('start_time')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                                            </div>

                                            <div class="col-md-2 mb-1">
                                                <label class="form-label" for="end_time">End Time</label>
                                                <input type="text" id="end_time" value="{{ ($isEdit) ? $session?->end_time : (old('end_time') ? old('end_time') : "12:00") }}" name="end_time" class="form-control flatpickr-time text-start" placeholder="HH:MM" />
                                                @error('end_time')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                                            </div>

                                            <div class="col-md-3 mb-1">
                                                <label class="form-label" for="duration">Select duration</label>
                                                <select class="form-select" id="duration" name="duration" required>
                                                    @php $durations = [30, 60, 120] @endphp
                                                    @foreach ($durations as $duration)
                                                        <option value="{{ $duration }}" {{ ($isEdit && $session?->duration == $duration) ? "selected" : '' }} >{{ $duration }} min</option>
                                                    @endforeach

                                                </select>
                                                @error('duration')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                                            </div>

                                            <div class="col-md-6  col-12">
                                                <div class="mb-1">
                                                    <label class="form-label" for="select-day">Select Days</label>
                                                    <select class="form-select" id="select-day" name="days[]" multiple>

                                                        @php $days = ["Monday","Tuesday","Wednesday","Thursday","Friday","Saturday","Sunday"];
                                                            $returnDays = ($isEdit && $session?->days) ? $session?->days : $days;
                                                        @endphp
                                                        @foreach ($days as $day)
                                                            <option value="{{ $day }}" {{ (in_array($day, $returnDays)) ? "selected" : '' }} >{{ $day }}</option>
                                                        @endforeach

                                                    </select>
                                                    @error('days')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                                                </div>
                                            </div>

                                            <div class="col-md-6 col-12">
                                                <div class="mb-1">
                                                    <label class="form-label" for="teachers">Select Teacher</label>
                                                    <select class="form-select" id="teachers" name="teachers[]" required multiple>
                                                        {{-- <optgroup label="Teacher"> --}}
                                                            @php
                                                            $returnTeacher = ($isEdit && $session?->teachers) ? $session?->teachers : $teachers;
                                                            @endphp
                                                            @foreach ($teachers as $teacher)
                                                                <option value="{{ $teacher?->id }}" {{ $isEdit ? (in_array($teacher?->id, $returnTeacher) ? "selected" : '') : 'selected' }}>{{ $teacher?->name }}</option>
                                                                @endforeach
                                                            {{-- </optgroup> --}}
                                                    </select>
                                                    @error('teachers')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                                                </div>
                                            </div>



                                            <div class="col-md-12  col-12">
                                                <div class="mb-1">
                                                    <label class="form-label" for="description">Description</label>
                                                    <textarea class="form-control" name="description" id="description" placeholder="Session description" rows="8">{{ ($isEdit) ? $session?->description : old('description')  }}</textarea>
                                                    @error('description')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
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

<script src="{{ asset('/') }}app-assets/vendors/js/pickers/pickadate/picker.js"></script>
<script src="{{ asset('/') }}app-assets/vendors/js/pickers/pickadate/picker.date.js"></script>
<script src="{{ asset('/') }}app-assets/vendors/js/pickers/pickadate/picker.time.js"></script>
{{-- <script src="{{ asset('/') }}app-assets/vendors/js/pickers/pickadate/legacy.js"></script> --}}
<script src="{{ asset('/') }}app-assets/vendors/js/pickers/flatpickr/flatpickr.min.js"></script>

<script>
     $('#select-day, #teachers').select2({
        // tags: true
    });

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
</script>
@endsection

