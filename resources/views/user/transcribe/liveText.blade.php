@extends('layouts.app')
@section('css')
    <!-- Data Table CSS -->
    <link href="{{URL::asset('plugins/datatable/datatables.min.css')}}" rel="stylesheet"/>
    <!-- Awselect CSS -->
    <link href="{{URL::asset('plugins/awselect/awselect.min.css')}}" rel="stylesheet"/>
    <!-- Flipclock CSS -->
    <link href="{{URL::asset('plugins/flipclock/flipclock.css')}}" rel="stylesheet"/>
    <!-- Sweet Alert CSS -->
    <link href="{{URL::asset('plugins/sweetalert/sweetalert2.min.css')}}" rel="stylesheet"/>
    <!-- Green Audio Players CSS -->
    <link href="{{ URL::asset('plugins/audio-player/green-audio-player.css') }}" rel="stylesheet" />
@endsection
@section('page-header')
    <!-- PAGE HEADER -->
    <div class="page-header mt-5-7">
        <div class="page-leftheader">
            <h4 class="page-title mb-0">{{ __('Record text audio') }}</h4>
            <ol class="breadcrumb mb-2">
                <li class="breadcrumb-item"><a href="{{route('user.transcribe.file')}}"><i
                            class="fa-solid fa-microphone-lines mr-2 fs-12"></i>{{ __('User') }}</a></li>
                <li class="breadcrumb-item" aria-current="page"><a
                        href="#"> {{ __('Assign Folder') }}</a></li>
                <li class="breadcrumb-item active" aria-current="page"><a
                        href="{{url('#')}}"> {{ __('Record Text Audio') }}</a></li>
            </ol>
        </div>
    </div>
    <!-- END PAGE HEADER -->
@endsection
@section('content')

    <style>
        .carousel-inner {
            transition: all 0.5s ease;
        }

        .carousel-item {
            opacity: 0;
            transition: opacity 0.5s ease;
        }

        .carousel-item.active {
            opacity: 1;
        }

        @media only screen and (max-width: 500px) {
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

            .transition-div {
                margin-top: 11rem;
            }

            .time_div {
                margin-top: 17rem !important;
                position: absolute;
            }

            .slider_div {
                height: 16rem;

            }

            .slider_img {
                height: 15rem !important;
                width: fit-content;
            }

            .current_day_result {
                margin-top: 15rem !important;
            }

        }

        /*media query end */
        #record-buttons .controls {
            width: 40px
        }

        .slider_img {
            padding: 14px;
            height: 500px
        }

        #countdown-time {
            display: flex;
            justify-content: center;
            align-items: center;
            text-align: center;
        }

        .countdown {
            display: flex;
            justify-content: center;
            text-align: center;
        }

        .loader-wrapper {
            display: flex;
            justify-content: center;
        }

        .loader-audio {
            border: 8px solid #f3f3f3;
            border-top: 8px solid #3498db;
            border-radius: 50%;
            width: 40px;
            height: 40px;
            animation: spin 2s linear infinite;
        }

        @keyframes spin {
            0% {
                transform: rotate(0deg);
            }
            100% {
                transform: rotate(360deg);
            }
        }

        .inner-div {
            width: 80%;
            margin: 0 auto;
        }


    </style>
    <div class="row">
        <div class="col-lg-3 col-md-12 col-sm-12">
            <form id="live-transcribe-form" action="{{ route('transcribeLiveText.transcribe.save') }}" method="POST"
                  enctype="multipart/form-data">
                @csrf

                <div class="card border-0 time_div">
                    <div class="card-body pt-6 pb-6 minute-div">

                        <!-- RECORD AUDIO -->
                        <div id="record-audio">
                            <!-- TIME COUNTDOWN -->
                            <div id="countdown-time">
                                <div class="countdown">
                                    <div class="middle"></div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div id="record-actions" class="text-center">
                                <!-- AUDIO FORMAT-->
                                <div id="transcribe-audio-format">{{ __('Audio Format') }}: 1 channel PCM @ 44.1kHz
                                </div>

                                <!-- AUDIO RECORDER-->

                                <div id="record-buttons">
                                    <div>
                                        <button class="controls active play ml-2" id="reply" style="z-index: 1">
                                            <i class="fa-solid fa-reply"></i>
                                        </button>
                                    </div>
                                    <div style="padding-left: 10px;">
                                        <button class="controls active play" id="start" style="z-index: 1; width: 56px; font-size: 30px">
                                            <i class="fa-solid fa-microphone-lines pl-1 " ></i>
                                        </button>
                                    </div>
                                    <div style="padding-left: 10px;">
                                        <button class="controls active play" id="transcribe-record" style="z-index: 1; ">
                                            <i class="fa fa-upload" aria-hidden="true"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <div id="error"></div>
                            <div style="z-index: 1">
                                <div id="recordings" class="text-center green-player">
                                    <audio id="audio" src=""></audio>
                                </div>
                            </div>
    {{--                            <button class="btn btn-primary " id="transcribe-record">{{ __('Submit') }}</button>--}}
    {{--                            <button class="btn btn-danger " id="transcribe-cancel" style="border-radius: 35px;">{{ __('Cancel') }}</button>--}}

                        </div>
                    </div>
                </div>
            </form>
        </div>


        <div class="col-lg-9 col-md-12 col-sm-12">
            <div class="card border-0 slider_div">
                <div class="card-header border-0 pb-1 d-flex justify-content-between align-items-center">
                    <div class="d-flex align-items-center">
                        <h3 class="card-title">{{ __('Text') }}</h3>
                        <span class="image-count" style="margin-left: 20px;"></span>
                    </div>
                    <div class="w-40 d-flex justify-content-between">
                        <div class="w-60">
                            <select class="form-control" id="filter">
                                <option selected value="complete">All</option>
                                <option value="notComplete">Not Complete</option>
                            </select>
                        </div>
                        <div class="w-35">
                            <input type="text" id="inputNumber" placeholder="Number" class="form-control">
                        </div>
                    </div>

                </div>

            @if($texts->count() > 0)
{{--                    <div class="card-header border-0 pb-1">--}}
{{--                        <h3 class="card-title">{{ __('Text') }}</h3>--}}
{{--                    </div>--}}
                    <div id="slider" class="carousel slide" data-ride="carousel">
                        <div class="progress hidden">
                            <div class="progress-bar " role="progressbar" style="width: 2%;" aria-valuenow="25"
                                 aria-valuemin="0" aria-valuemax="100">
                                <span class="progress-text"></span>
                            </div>
                        </div>
                        <span class="progress-notification ml-2 hidden"> Please Don't reload page until recording is not uploaded you can record you next audio</span>
                        <!-- Indicators -->
                        <ol class="carousel-indicators">
                            @include('user.transcribe.textSlider')
                        </ol>

                        <!-- Slides -->
                        <div class="carousel-inner">
                            @foreach ($texts as $key => $text)
                                <div class="row">
                                    <div class="text-center inner-div p-5 carousel-item {{ $key == 0 ? 'active' : '' }}"
                                         id="{{$text->id}}">
                                        {{ $text->text }} <!-- Replace image with text -->
                                    </div>
                                </div>
                                <div class="row text-center mb-4">
                                    <div class="col-lg-4 col-md-10 col-sm-10">
                                    </div>
                                    <div class="col-lg-4 col-md-10 col-sm-10">
                                        <div class="text-center">
                                            @if($text->transcribeText)
                                                <audio controls id="audio-{{ $text->id }}">
                                                    <source src="{{ $text->transcribeText->file_url }}"
                                                            type="{{ $text->transcribeText->audio_type }}">
                                                </audio>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <!-- Controls -->
                        <button class="carousel-control-prev" id="prev-btn" role="button" data-slide="prev">
                            <span class="carousel-control-prev-icon hidden" aria-hidden="true"></span>
                            <span> <i class="fa fa-angle-left" style="font-size:30px; color: black"></i></span>
                            <span class="visually-hidden">Previous</span>
                        </button>
                        <button class="carousel-control-next" id="next-btn" role="button" data-slide="next">
                            <span class="carousel-control-next-icon hidden" aria-hidden="true"></span>
                            <span><i class="fa fa-angle-right" style="font-size:30px; color: black"></i></span>
                            <span class="visually-hidden">Next</span>
                        </button>
                    </div>

            </div>
            @endif
            <input type="hidden" name="image_id" class="uploadingImage">
            <div class="card border-0 mt-6 current_day_result">
                <div class="card-header">
                    <h3 class="card-title">{{ __('Current Day Results') }}</h3>
                    <a class="refresh-button" href="#" data-tippy-content="Refresh Table"><i
                            class="fa fa-refresh table-action-buttons view-action-button"></i></a>
                </div>
                <div class="card-body pt-2">
                    <!-- SET DATATABLE -->
                    <table id='resultTable' class='table' width='100%'>
                        <thead>
                        <tr>
                            <th width="2%"></th>
                            <th width="7%">{{ __('Created On') }}</th>
                            <th width="7%">{{ __('Task Id') }}</th>
                            <th width="7%">{{ __('Text') }}</th>
                            <th width="5%">{{ __('Words') }}</th>
                            <th width="5%">{{ __('Duration') }}</th>
                            <th width="5%">{{ __('Status') }}</th>
                            <th width="3%">{{ __('Actions') }}</th>
                        </tr>
                        </thead>
                    </table> <!-- END SET DATATABLE -->
                </div>
            </div>
        </div>
    </div>

    <!-- TRANSCRIBE MODAL -->
    <div class="modal fade transcript-modal" id="transcriptModalText" tabindex="-1" role="dialog"
         aria-labelledby="exampleModalCenterTitle" aria-hidden="true" data-bs-keyboard="false">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">{{ __('Text Transcript Results') }}</h5>
                    <button type="button" class="btn-close fs-12" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-buttons pl-5">

                    <span><a href="#" id="live-download-txt" data-tippy-content="{{ __('Download as Text File') }}"><i
                                class="fa-sharp fa-solid fa-download table-action-buttons view-action-button"></i></a></span>
                    <span><a href="#" id="live-save" data-id="" data-tippy-content="{{ __('Save Edited Text') }}"><i
                                class="fa-sharp fa-solid fa-floppy-disk-circle-arrow-right table-action-buttons edit-action-button"></i></a></span>
                </div>
                <span class="save-status fs-12 pl-5 pt-4 text-success" id="live-save-status"></span>
                <div class="modal-body p-5">
                    <h5 class="p-5" id="text-for-transcript"></h5>
                </div>
                <div class="modal-body p-5">
                    <textarea class="form-control fs-12" id="textarea-transcript" rows="10" autofocus></textarea>
                </div>
            </div>
        </div>
    </div>
    <!-- TRANSCRIBE MODAL -->
    </div>

    @php
        $textUrlsWithSerialNumbers = \Illuminate\Support\Facades\Cache::get('textUrlsWithSerialNumbers'.auth()->user()->id);

    @endphp
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
    <script src="{{URL::asset('js/transcribe-live-text.js')}}"></script>
    <script src="{{URL::asset('js/awselect.js')}}"></script>
    <script src="{{URL::asset('plugins/socket/websocket.min.js')}}"></script>
    {{--    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.3/jquery.min.js"></script>--}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.touchswipe/1.6.19/jquery.touchSwipe.min.js"></script>
    <script type="text/javascript">
        $(document).ready(function () {
            $('#slider').carousel(
                {
                    interval: 0
                }
            );
        });

        $(function () {

            "use strict";

            function format(d) {
                console.log('d', d)
                // `d` is the original data object for the row
                return '<div class="slider">' +
                    '<table class="details-table">' +
                    '<tr>' +
                    '<td class="details-title " width="10%">Transcript:</td>' +
                    '<td id="' + ((d.id == null) ? '' : d.id) + '" class="transcribeResultText">' + ((d.text == null) ? '' : d.text) + '</td>' +
                    '</tr>' +
                    '<tr>' +
                    '<td class="details-result" width="10%">Audio File:</td>' +
                    '<td><audio controls preload="none">' +
                    '<source src="' + d.result + '" type="' + d.audio_type + '">' +
                    '</audio></td>' +
                    '</tr>' +
                    '</table>' +
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
                    paginate: {
                        first: '<i class="fa fa-angle-double-left"></i>',
                        last: '<i class="fa fa-angle-double-right"></i>',
                        previous: '<i class="fa fa-angle-left"></i>',
                        next: '<i class="fa fa-angle-right"></i>'
                    }
                },
                pagingType: 'full_numbers',
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('user.transcribe.live.Text') }}",
                    data: {
                        folderId: {{ $folderId }}
                    }
                },                columns: [{
                    "className": 'details-control',
                    "orderable": false,
                    "searchable": false,
                    "data": null,
                    "defaultContent": ''
                },
                    {
                        data: 'created-on',
                        name: 'created-on',
                        orderable: true,
                        searchable: true
                    },
                    {
                        data: 'text_id',
                        name: 'text_id',
                        orderable: true,
                        searchable: true
                    },
                    {
                        data: 'text_model',
                        name: 'text_model',
                        orderable: true,
                        searchable: true
                    },
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
                        data: 'custom-status',
                        name: 'custom-status',
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

            $(document).ready(function ()   {
                if ($(window).width() <= 500) {
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

                } else {
                    $('#resultTable tbody').on('click', 'td.details-control', function () {
                        var tr = $(this).closest('tr');
                        var row = table.row(tr);

                        if (row.child.isShown()) {
                            // This row is already open - close it
                            $('div.slider', row.child()).slideUp(function () {
                                row.child.hide();
                                tr.removeClass('shown');
                            });
                        } else {
                            // Open this row
                            row.child(format(row.data()), 'no-padding').show();
                            tr.addClass('shown');

                            $('div.slider', row.child()).slideDown();
                        }
                    });
                }
            });

            $('.refresh-button').on('click', function (e) {
                e.preventDefault();
                $("#resultTable").DataTable().ajax.reload();
            });

            $('#add-project').on('click', function () {
                $('#projectModal').modal('show');
            });

            $(document).ready(function () {
                $("#transcript").jQTArea({
                    setLimit: 100000,
                    setExt: "W",
                    setExtR: true
                });
            });

            // CREATE NEW PROJECT
            $(document).on('click', '#add-project', function (e) {

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
                            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
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
                            error: function (data) {
                                Swal.fire({type: 'error', title: 'Oops...', text: 'Something went wrong!'})
                            }
                        })
                    } else if (result.dismiss !== Swal.DismissReason.cancel) {
                        Swal.fire('{{ __('No Project Name Entered') }}', '{{ __('Make sure to provide a new project name before creating') }}', 'error')
                    }
                })
            });


            // DELETE TRANSCRIBE RESULT
            $(document).on('click', '.deleteResultButton', function (e) {

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
                            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
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
                            error: function (data) {
                                Swal.fire({type: 'error', title: 'Oops...', text: 'Something went wrong!'})
                            }
                        })
                    }
                })
            });
        });
        $('.carousel-control-next').click(function (e) {

            e.preventDefault();
            var $active = $('#slider .carousel-item.active');
            var $next = $active.next();

            if ($next.length) {
                $next.addClass('active');
                $active.removeClass('active');
            }
        });
        $('.carousel-control-prev').click(function (e) {
            e.preventDefault();
            var $active = $('#slider .carousel-item.active');
            var $prev = $active.prev();
            if ($prev.length) {
                $prev.addClass('active');
                $active.removeClass('active');
            }
        });

        $(document).ready(function () {
            // Get the initial image count and total count from the blade view
            var textCount = {{ $textCount }};
            var totalCount = {{ $totalCount }};
            var folderID = {{ $folderId }};

            // Update the image count text with the initial values
            $('.image-count').text('(' + textCount + '/' + totalCount + ')');

            $(document).on('click', '#prev-btn, #next-btn', function () {
                var direction     = $(this).attr('id') == 'next-btn' ? 'next' : 'prev';
                var currentSlide  = $('.carousel-item.active').attr('id');
                var filter        = $('#filter').val();

                $.ajax({
                    url: '/account/speech-to-text/get-text/' + currentSlide + '/' + direction + '/' + folderID + '/' + filter,
                    success: function (data) {
                        console.log(data)
                        if (data.texts.length > 0) {
                            var textHtml = '';
                            $.each(data.texts, function (key, text) {

                                var url = text.id;
                                var serialNumber = null; // initialize the serial number to null
                                // loop over the image URLs with serial numbers
                                for (var number in @json($textUrlsWithSerialNumbers)) {
                                    if (@json($textUrlsWithSerialNumbers)[number] === url) {
                                        // if the current URL matches one in the cache, set the serial number
                                        serialNumber = number;
                                        break; // exit the loop
                                    }
                                }
                                //  Update the image count text`
                                $('.image-count').text('(' + serialNumber + '/' + totalCount + ')');


                                var activeClass = key == 0 ? 'active' : '';
                                textHtml += '<div class="text-center carousel-item ' + activeClass + '" id="' + text.id + '">';
                                textHtml += '<p class="slider_text p-5">' + text.text + '</p>';

                                // add the audio tag here
                                $('#reply').hide()
                                $('#start').show()

                                if (text.transcribe_text && text.transcribe_text.file_url) {
                                    $('#reply').show()
                                    $('#start').hide()
                                    textHtml += '<audio controls class="mb-4">';
                                    textHtml += '<source src="' + text.transcribe_text.file_url + '" type="' + text.transcribe_text.audio_type + '">';
                                    textHtml += '</audio>';
                                }
                                if (direction == 'prev') {
                                    var previousImageId = text.id
                                    var currentImageId = $('.uploadingImage').val()
                                    if (previousImageId == currentImageId) {
                                        // Show loader HTML
                                        textHtml += '<div class="loader-wrapper"><div class="loader-audio"></div></div>';
                                        textHtml += '<div class="loader-wrapper"><h6> Audio is uploading</h6></div>';
                                    }
                                }
                                textHtml += '</div>';
                            });

                            // Replace the current slides with the new ones
                            $('.carousel-inner').html(textHtml);

                            // Update the carousel indicators
                            $('.carousel-indicators').html(data.indicatorsHtml);


                        } else {
                            console.log("No image exist")
                        }
                    },
                    error: function () {
                        $('#loader').hide(); // Hide the loader when the AJAX request fails
                        // alert('An error occurred while getting images.');
                    }
                });
            });

            $('#inputNumber').on('focusout keyup', function (event) {
                // Check if the event is a focusout or if the Enter key is pressed
                if (event.type === 'focusout' || (event.type === 'keyup' && event.key === 'Enter')) {
                    var number = $(this).val();

                    $.ajax({
                        url: '/account/speech-to-text/get-text-by-id/' + number  + '/' + folderID,
                        success: function (data) {
                            console.log(data)
                            if (data.texts.length > 0) {
                                var textHtml = '';
                                $.each(data.texts, function (key, text) {

                                    //  Update the image count text`
                                    $('.image-count').text('(' + number + '/' + totalCount + ')');


                                    var activeClass = key == 0 ? 'active' : '';
                                    textHtml += '<div class="text-center carousel-item ' + activeClass + '" id="' + text.id + '">';
                                    textHtml += '<p class="slider_text p-5">' + text.text + '</p>';

                                    // add the audio tag here
                                    $('#reply').hide()
                                    $('#start').show()

                                    if (text.transcribe_text && text.transcribe_text.file_url) {
                                        $('#reply').show()
                                        $('#start').hide()
                                        textHtml += '<audio controls class="mb-4">';
                                        textHtml += '<source src="' + text.transcribe_text.file_url + '" type="' + text.transcribe_text.audio_type + '">';
                                        textHtml += '</audio>';
                                    }
                                    // if (direction == 'prev') {
                                    //     var previousImageId = text.id
                                    //     var currentImageId = $('.uploadingImage').val()
                                    //     if (previousImageId == currentImageId) {
                                    //         // Show loader HTML
                                    //         textHtml += '<div class="loader-wrapper"><div class="loader-audio"></div></div>';
                                    //         textHtml += '<div class="loader-wrapper"><h6> Audio is uploading</h6></div>';
                                    //     }
                                    // }
                                    textHtml += '</div>';
                                });

                                // Replace the current slides with the new ones
                                $('.carousel-inner').html(textHtml);

                                // Update the carousel indicators
                                $('.carousel-indicators').html(data.indicatorsHtml);


                            } else {
                                console.log("No image exist")
                            }
                        },
                        error: function () {
                            $('#loader').hide(); // Hide the loader when the AJAX request fails
                            // alert('An error occurred while getting images.');
                        }
                    });
                }
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
                        $("#prev-btn").click();
                    }
                },
                allowPageScroll: 'vertical'
            });
        });


    </script>
@endsection
