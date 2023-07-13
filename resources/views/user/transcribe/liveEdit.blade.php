@extends('layouts.app')
@section('css')
    <!-- Data Table CSS -->
    <link href="{{URL::asset('plugins/datatable/datatables.min.css')}}" rel="stylesheet" />
    <!-- Awselect CSS -->
    <link href="{{URL::asset('plugins/awselect/awselect.min.css')}}" rel="stylesheet" />
    <!-- Flipclock CSS -->
    <link href="{{URL::asset('plugins/flipclock/flipclock.css')}}" rel="stylesheet" />
    <!-- Sweet Alert CSS -->
    <link href="{{URL::asset('plugins/sweetalert/sweetalert2.min.css')}}" rel="stylesheet" />
@endsection
@section('page-header')
    <!-- PAGE HEADER -->
    <div class="page-header mt-5-7">
        <div class="page-leftheader">
            <h4 class="page-title mb-0">{{ __('Live Transcribe Studio') }}</h4>
            <ol class="breadcrumb mb-2">
                <li class="breadcrumb-item"><a href="{{route('user.transcribe.file')}}"><i class="fa-solid fa-microphone-lines mr-2 fs-12"></i>{{ __('User') }}</a></li>
                <li class="breadcrumb-item active" aria-current="page"><a href="{{url('#')}}"> {{ __('Live Transcribe Studio') }}</a></li>
            </ol>
        </div>
    </div>
    <!-- END PAGE HEADER -->
@endsection
@section('content')


    <style>
        3        @media only screen and (max-width: 500px) {
            .carouel-button {
                width: 35%;
            }
            .countdown.flip-clock-wrapper ul {
                height: 45.5px;
                margin: 0 -0.95px 0 2.11px;
                width: 30px;
            }
            .minute-div {
                padding-bottom: 5px !important;
                padding-top: 5px !important;
            }
            .countdown.flip-clock-wrapper {
                padding-left: 2.9rem;
            }
            .transition-div {
                margin-top: 11rem;
            }
            .time_div{
                margin-top: 23rem !important;
                position: absolute;
            }
            .slider_div{
                height: 22rem;

            }
            .slider_img{
                height: 15rem;
                width: fit-content;
            }

        }
    </style>
    <div class="row">
        <div class="col-lg-3 col-md-12 col-sm-12">
            <form id="live-transcribe-form" action="{{ route('user.transcribe.transcribe.live') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="card border-0 time_div">
                    <div class="card-body pt-6 pb-6 minute-div">

                        <!-- RECORD AUDIO -->
                        <div id="record-audio">
                            <!-- TIME COUNTDOWN -->
                            <div id="countdown-time">
                                <div class="countdown"><div class="middle"></div></div>
                            </div>
                        </div>

                        <div id="record-actions" class="text-center">
                            <!-- AUDIO FORMAT-->
                            <div id="transcribe-audio-format">{{ __('Audio Format') }}: 1 channel PCM @ 44.1kHz</div>

                            <!-- AUDIO RECORDER-->
                            <div id="record-buttons">
                                <div>
                                    <button class="controls active play " id="start" style="z-index: 1"><i class="fa-solid fa-microphone-lines " ></i>{{ __('Start') }}</button>
                                </div>

                            </div>

                            <div id="error"></div>
                            <div style="display: none">
                                <div id="recordings" class="text-center green-player hidden" >
                                    <audio id="audio" src=""></audio>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
                <input type="hidden" name="trancribe_studio_id" class="form-control" value="{{$transcribe->id}}">
                <div class="card border-0 hidden">
                    <div class="card-body pt-5 pl-5 pr-5 pb-2">
                        <div class="row">
                            <div class="col-sm-12 ">
                                <div class="form-group ">
                                    <h6 class="task-heading">{{ __('Speech Language') }}</h6>

                                    <select id="languages" name="language" data-placeholder="{{ __('Select your language') }}">
                                        @foreach ($languages as $language)
                                            <option value="{{ $language->id }}"
                                                    data-code="{{ $language->language_code }}"
                                                    data-img="{{ URL::asset($language->language_flag) }}"
                                                    @if (config('stt.vendor_logos') == 'show')
                                                    data-vendor="{{ URL::asset($language->vendor_img) }}"
                                                    @endif
                                                    @if($folder)
                                                    @if ($folder->language_id == $language->id)
                                                    selected
                                                @endif
                                                @endif
                                            > {{ $language->language }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="col-sm-12">
                                <div class="row" style="display: none">
                                    <div class="col-md-10">
                                        <div class="form-group">
                                            <h6 class="task-heading">{{ __('Project Name') }}</h6>
                                            <select id="project" name="project" data-placeholder="{{ __('Select Project Name') }}">
                                                <option value="all">{{ __('All Projects') }}</option>
                                                @foreach ($projects as $project)
                                                    <option value="{{ $project->name }}" @if (auth()->user()->default_project == $project->name) selected @endif> {{ ucfirst($project->name) }}</option>
                                                @endforeach
                                            </select>
                                            @error('project')
                                            <p class="text-danger">{{ $errors->first('project') }}</p>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-2 pl-1 pt-align">
                                        <button class="btn btn-special create-project" type="button" id="add-project" data-tippy-content="{{ __('Create New Project') }}" ><i class="fa-solid fa-rectangle-history-circle-plus"></i></button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>


        <div class="col-lg-9 col-md-12 col-sm-12">
            <div class="card border-0 slider_div">


                    <div class="card-header border-0 pb-1">

                        <h3 class="card-title">{{ __('Images') }}</h3>
                    </div>
                    <div id="slider" class="carousel slide" data-ride="carousel">
                        <!-- Indicators -->
                        <ol class="carousel-indicators">
                            @include('user.transcribe.imageSlider')
                        </ol>

                        <!-- Slides -->
                        <div class="carousel-inner">
                            @foreach ($images as $key => $image)

                                <div class="text-center carousel-item {{ $key == 0 ? 'active' : '' }}" id="{{$image->id}}">
                                    <img class="slider_img " src="{{ $image->image }}" alt="Image {{ $key + 1 }}" style="padding: 14px; height:500px ">
                                </div>
                            @endforeach
                        </div>

                        <!-- Controls -->
                        <a class="carousel-control-prev" href="#slider" id="prev-btn" role="button" data-slide="prev">
                            <span class="carousel-control-prev-icon " aria-hidden="true"></span>
                            <span class="visually-hidden">Previous</span>
                        </a>
                        <a class="carousel-control-next" href="#slider" id="next-btn" role="button" data-slide="next">
                            <span class="carousel-control-next-icon " aria-hidden="true"></span>
                            <span class="visually-hidden">Next</span>
                        </a>
                        {{--                        <div class="row justify-content-between mb-2">--}}
                        {{--                            <div class="carousel-control-prev col-lg-2 col-md-2 col-sm-2 text-center carouel-button">--}}
                        {{--                                <button class="btn btn-primary" id="prev-btn"><i class="fa fa-angle-left" style="font-size:30px"></i></button>--}}
                        {{--                            </div>--}}
                        {{--                            <div class="carousel-control-next col-lg-2 col-md-2 col-sm-2 text-center carouel-button">--}}
                        {{--                                <button class="btn btn-primary" id="next-btn"><i class="fa fa-angle-right" style="font-size:30px"></i></button>--}}
                        {{--                            </div>--}}
                        {{--                        </div>--}}

                    </div>
            </div>


            <div class="card border-0 transition-div">
                <div class="card-header border-0 pb-1">
                    <h3 class="card-title">{{ __('Transcript') }}</h3>
                </div>
                <div class="card-body pt-2">
                    <div class="row mt-5px">
                        <div class="col-md-12">
                            <div class="input-box mb-0" id="textarea-box">
                                <textarea class="form-control" name="transcript" id="transcript" rows="15" placeholder="{{ __('Click start to initiate live speech transcription...') }}"></textarea>

                                <label class="input-label">
                                    <span class="input-label-content input-label-main">{{ __('Speech to Text') }}</span>
                                </label>
                            </div>

                            <p class="jQTAreaExt"></p>

                            <div id="textarea-settings">
                                <div class="character-counter">
                                    <span><em class="jQTAreaCount"></em>/<em class="jQTAreaValue"></em> {{ __('characters') }}</span>
                                </div>

                                <div class="clear-button">
                                    <button type="button" id="clear-text">{{ __('Clear Text') }}</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>


        </div>
    </div>

    <!-- TRANSCRIBE MODAL -->
    <div class="modal fade transcript-modal" id="transcriptModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true" data-bs-keyboard="false">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">{{ __('Live Transcript Results') }}</h5>
                    <button type="button" class="btn-close fs-12" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-buttons pl-5">

                    <span><a href="#" id="live-download-txt" data-tippy-content="{{ __('Download as Text File') }}"><i class="fa-sharp fa-solid fa-download table-action-buttons view-action-button"></i></a></span>
                    <span><a href="#" id="live-save" data-id="" data-tippy-content="{{ __('Save Edited Text') }}"><i class="fa-sharp fa-solid fa-floppy-disk-circle-arrow-right table-action-buttons edit-action-button"></i></a></span>
                </div>
                <span class="save-status fs-12 pl-5 pt-4 text-success" id="live-save-status"></span>
                <div class="modal-body p-5">
                    <img id="edit-live-image-transcription" alt="Avatar" src="">
                </div>
                <div class="modal-body p-5">
                    <textarea class="form-control fs-12" id="textarea" rows="10" autofocus></textarea>
                </div>
            </div>
        </div>
    </div>
    <!-- TRANSCRIBE MODAL -->
    </div>
@endsection
@section('js')
    <!-- Green Audio Players JS -->
    <script src="{{ URL::asset('plugins/audio-player/green-audio-player.js') }}"></script>
    <script src="{{ URL::asset('js/audio-player.js') }}"></script>
    <!-- Flipclock JS -->
    <script src={{ URL::asset('plugins/flipclock/moment.min.js') }}></script>
    <script src={{ URL::asset('plugins/flipclock/popper.min.js') }}></script>
    <script src={{ URL::asset('plugins/flipclock/flipclock.min.js') }}></script>
    <script src={{ URL::asset('plugins/flipclock/recorder.js') }}></script>

    <!-- Data Tables JS -->
    <script src="{{URL::asset('plugins/datatable/datatables.min.js')}}"></script>
    <script src="{{URL::asset('plugins/sweetalert/sweetalert2.all.min.js')}}"></script>
    <script src="{{URL::asset('js/live-main.js')}}"></script>
    <!-- Awselect JS -->
    <script src="{{URL::asset('plugins/jqtarea/plugin-jqtarea.min.js')}}"></script>
    <script src="{{URL::asset('plugins/awselect/awselect-custom.js')}}"></script>
    <script src="{{URL::asset('js/transcribe-dashboard.js')}}"></script>
    <script src="{{URL::asset('js/transcribe-live.js')}}"></script>
    <script src="{{URL::asset('js/awselect.js')}}"></script>
    <script src="{{URL::asset('plugins/socket/websocket.min.js')}}"></script>
    {{--    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.3/jquery.min.js"></script>--}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.touchswipe/1.6.19/jquery.touchSwipe.min.js"></script>
    <script type="text/javascript">
        $(document).ready(function() {
            $('#slider').carousel(
                {
                    interval: 0
                }
            );
        });

        $(function () {

            "use strict";

            function format(d) {
                // `d` is the original data object for the row
                return '<div class="slider">'+
                    '<table class="details-table">'+
                    '<tr>'+
                    '<td class="details-title " width="10%">Transcript:</td>'+
                    '<td id="'+ ((d.id == null) ? '' : d.id) +'" class="transcribeResult">'+ ((d.text == null) ? '' : d.text) +'</td>'+
                    '</tr>'+
                    '<tr>'+
                    '<td class="details-result" width="10%">Audio File:</td>'+
                    '<td><audio controls preload="none">' +
                    '<source src="'+ d.result +'" type="'+ d.audio_type +'">' +
                    '</audio></td>'+
                    '</tr>'+
                    '</table>'+
                    '</div>';
            }

            // INITILIZE DATATABLE
            var table = $('#resultTable').DataTable({
                "lengthMenu": [[25, 50, 100, -1], [25, 50, 100, "All"]],
                responsive: {
                    details: {type: 'column'}
                },
                colReorder: true,
                language: {
                    "emptyTable": "<div><img id='no-results-img' src='{{ URL::asset('img/files/no-result.png') }}'><br>{{ __('No transcribe tasks submitted yet') }}</div>",
                    search: "<i class='fa fa-search search-icon'></i>",
                    lengthMenu: '_MENU_ ',
                    paginate : {
                        first    : '<i class="fa fa-angle-double-left"></i>',
                        last     : '<i class="fa fa-angle-double-right"></i>',
                        previous : '<i class="fa fa-angle-left"></i>',
                        next     : '<i class="fa fa-angle-right"></i>'
                    }
                },
                pagingType : 'full_numbers',
                processing: true,
                serverSide: true,
                ajax: "{{ route('user.transcribe.live') }}",
                columns: [{
                    "className":      'details-control',
                    "orderable":      false,
                    "searchable":     false,
                    "data":           null,
                    "defaultContent": ''
                },
                    {
                        data: 'created-on',
                        name: 'created-on',
                        orderable: true,
                        searchable: true
                    },
                    {
                        data: 'image_id',
                        name: 'image_id',
                        orderable: true,
                        searchable: true
                    },
                    // {
                    //     data: 'image_code',
                    //     name: 'image_code',
                    //     orderable: true,
                    //     searchable: true
                    // },
                    // {
                    //     data: 'task_id',
                    //     name: 'task_id',
                    //     orderable: true,
                    //     searchable: true
                    // },
                    // {
                    //     data: 'custom-language',
                    //     name: 'custom-language',
                    //     orderable: true,
                    //     searchable: true
                    // },
                    {
                        data: 'words',
                        name: 'words',
                        orderable: true,
                        searchable: true
                    },
                    {
                        data: 'custom-length',
                        name: 'custom-length',
                        orderable: true,
                        searchable: true
                    },
                    {
                        data: 'actions',
                        name: 'actions',
                        orderable: false,
                        searchable: false
                    },
                ]
            });

            $('#resultTable tbody').on('click', 'td.details-control', function () {
                var tr = $(this).closest('tr');
                var row = table.row(tr);

                if (row.child.isShown()) {
                    tr.addClass('shown-transcription');
                    // This row is already open - close it
                    $('div.slider', row.child()).slideUp('fast', function () {
                        row.child.hide();
                        tr.removeClass('shown');
                    });
                } else {
                    // Open this row
                    row.child(format(row.data()), 'no-padding').show();
                    tr.addClass('shown');


                    // Check if child row is shown
                    if (tr.hasClass('shown-transcription')) {
                        console.log(123)
                        $('div.slider', row.child()).slideDown('fast');
                        tr.removeClass('shown-transcription');
                    } else {
                        $('div.slider', row.child()).slideUp('fast');
                        tr.removeClass('shown');
                        tr.addClass('shown-transcription');
                    }
                }
            });



            $('.refresh-button').on('click', function(e){
                e.preventDefault();
                $("#resultTable").DataTable().ajax.reload();
            });


            $('#add-project').on('click', function() {
                $('#projectModal').modal('show');
            });


            $(document).ready(function(){
                $("#transcript").jQTArea({
                    setLimit: 100000,
                    setExt: "W",
                    setExtR: true
                });

            });


            // CREATE NEW PROJECT
            $(document).on('click', '#add-project', function(e) {

                e.preventDefault();

                Swal.fire({
                    title: '{{ __('Create New Project') }}',
                    showCancelButton: true,
                    confirmButtonText: '{{ __('Create') }}',
                    reverseButtons: true,
                    closeOnCancel: true,
                    input: 'text',
                }).then((result) => {
                    if (result.value) {
                        var formData = new FormData();
                        formData.append("new-project", result.value);
                        $.ajax({
                            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                            method: 'post',
                            url: 'project',
                            data: formData,
                            processData: false,
                            contentType: false,
                            success: function (data) {
                                if (data['status'] == 'success') {
                                    Swal.fire('Project Created', '{{ __('New project has been successfully created') }}', 'success');
                                    location.reload();
                                } else {
                                    Swal.fire('Project Creation Error', data['message'], 'error');
                                }
                            },
                            error: function(data) {
                                Swal.fire({ type: 'error', title: 'Oops...', text: 'Something went wrong!' })
                            }
                        })
                    } else if (result.dismiss !== Swal.DismissReason.cancel) {
                        Swal.fire('{{ __('No Project Name Entered') }}', '{{ __('Make sure to provide a new project name before creating') }}', 'error')
                    }
                })
            });


            // DELETE TRANSCRIBE RESULT
            $(document).on('click', '.deleteResultButton', function(e) {

                e.preventDefault();

                Swal.fire({
                    title: '{{ __('Confirm Result Deletion') }}',
                    text: '{{ __('It will permanently delete this transcribe result') }}',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: '{{ __('Delete') }}',
                    reverseButtons: true,
                }).then((result) => {
                    if (result.isConfirmed) {
                        var formData = new FormData();
                        formData.append("id", $(this).attr('id'));
                        $.ajax({
                            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                            method: 'post',
                            url: 'result/delete',
                            data: formData,
                            processData: false,
                            contentType: false,
                            success: function (data) {
                                if (data == 'success') {
                                    Swal.fire('{{ __('Result Deleted') }}', '{{ __('Transcribe result has been successfully deleted') }}', 'success');
                                    $("#resultTable").DataTable().ajax.reload();
                                } else {
                                    Swal.fire('{{ __('Delete Failed') }}', '{{ __('There was an error while deleting this result') }}', 'error');
                                }
                            },
                            error: function(data) {
                                Swal.fire({ type: 'error', title: 'Oops...', text: 'Something went wrong!' })
                            }
                        })
                    }
                })
            });
        });
        $('.carousel-control-next').click(function(e) {
            e.preventDefault();
            var $active = $('#slider .carousel-item.active');
            var $next = $active.next();

            if ($next.length) {
                $next.addClass('active');
                $active.removeClass('active');
            }
        });
        $('.carousel-control-prev').click(function(e) {
            e.preventDefault();
            var $active = $('#slider .carousel-item.active');
            var $prev = $active.prev();
            if ($prev.length) {
                $prev.addClass('active');
                $active.removeClass('active');
            }
        });

        $(document).ready(function () {
            $(document).on('click', '#prev-btn, #next-btn', function () {
                var direction = $(this).attr('id') == 'next-btn' ? 'next' : 'prev';
                var currentSlide = $('.carousel-item.active').attr('id');

                $.ajax({
                    url: '/account/speech-to-text/get-images/' + currentSlide + '/' + direction,
                    success: function (data) {
                        console.log(data)
                        if (data.images.length > 0) {
                            var imagesHtml = '';
                            $.each(data.images, function (key, image) {
                                var activeClass = key == 0 ? 'active' : '';
                                imagesHtml += '<div class="text-center carousel-item ' + activeClass + '" id="' + image.id + '">';
                                imagesHtml += '<img class="slider_img "  src="' + image.image + '" alt="Image ' + (key + 1) + '" style="padding: 14px; height:500px">';
                                imagesHtml += '</div>';
                            });

                            // Replace the current slides with the new ones
                            $('.carousel-inner').html(imagesHtml);

                            // Update the carousel indicators
                            $('.carousel-indicators').html(data.indicatorsHtml);

                            // Update the previous and next buttons
                            // $('#prev-btn').prop('disabled', data.prevSlide == null).attr('href', '#' + data.prevSlide).attr('data-slide', 'prev');
                            // $('#next-btn').prop('disabled', data.nextSlide == null).attr('href', '#' + data.nextSlide).attr('data-slide', 'next');
                        } else {
                            console.log("No image exist")
                        }
                    },
                    error: function () {
                        $('#loader').hide(); // Hide the loader when the AJAX request fails
                        alert('An error occurred while getting images.');
                    }
                });
            });
        });
        $(document).ready(function () {
            // Initialize TouchSwipe on the slider
            $('#slider').swipe({
                swipe: function (event, direction, distance, duration, fingerCount, fingerData) {
                    if (direction == 'left') {
                        // Go to the next slide
                        $("#next-btn").click();
                    } else if (direction == 'right') {
                        // Go to the previous slide
                        $("#next-btn").click();
                    }
                },
                allowPageScroll: 'vertical'
            });
        });


    </script>
@endsection
