@extends('layouts.app')
@section('css')
    <!-- Data Table CSS -->
    <link href="{{URL::asset('plugins/datatable/datatables.min.css')}}" rel="stylesheet"/>
@endsection
@section('page-header')
    <!-- PAGE HEADER -->
    <div class="page-header mt-5-7">
        <div class="page-leftheader">
            <h4 class="page-title mb-0">{{ __('Transcribe Results') }}</h4>
            <ol class="breadcrumb mb-2">
                <li class="breadcrumb-item"><a href="{{route('user.dashboard')}}"><i
                            class="fa-solid fa-folder-music mr-2 fs-12"></i>{{ __('User') }}</a></li>
                <li class="breadcrumb-item" aria-current="page"><a
                        href="{{route('user.transcribe.file')}}"> {{ __('Transcribe Studio') }}</a></li>
                <li class="breadcrumb-item active" aria-current="page"><a
                        href="{{url('#')}}"> {{ __('Transcribe Results') }}</a></li>
            </ol>
        </div>
    </div>
    <!-- END PAGE HEADER -->
@endsection
@section('content')
    <style>
        .modal-backdrop {
            z-index: 1040 !important;
            background-color: rgba(0, 0, 0, 0.5) !important;
        }

        .modal-dialog-centered {
            display: flex;
            /*align-items: center;*/
            min-height: calc(100% - (1.75rem * 2));
        }

        .modal-dialog-centered::before {
            height: 0;
        }

        .modal-xl {
            max-width: none;
        }

        .text-data-container {
            display: flex !important;
            justify-content: center !important;
            align-items: center !important;
            height: 100%;
            margin-top: 20px;
        }

        .modal-xl {
            margin-right: 30%; /* Add margin to the right of the modal */
            max-width: none;
        }
        .sidebar {
            position: fixed;
            top: 5%;
            right: 8px;
            width: 320px;
            height: 90%;
            padding: 20px;
            overflow-y: auto;
            z-index: 9000;
            border-radius: 4%;
            background-color: rgb(31, 31, 31) !important;
            border-left: 1px solid #ddd;
            box-shadow: 0px 0px 20px rgba(0, 0, 0, 0.3);
        }

        .sidebar.open {
            transform: translateY(-50%) scale(1);
        }

        .sidebar h5 {
            font-size: 1.2rem;
            font-weight: bold;
            margin-top: 0;
            margin-bottom: 20px;
        }

        audio {
            width: 100%;
            height: 50px;
            background-color: rgb(31, 31, 31);
            color: white;
            border: none;
            outline: none;
            padding: 10px;
            border-radius: 5px;
            z-index: 1000 !important;
        }

        .sidebar ul {
            list-style: none;
            margin: 0;
            padding: 0;
        }

        .sidebar audio {
            width: 100%;
        }

        .sidebar textarea {
            resize: none;
        }

        .sidebar button {
            font-size: 0.9rem;
            padding: 5px 20px;
        }

        #closeSidebar {
            position: absolute;
            bottom: 20px;
            left: 20px;
        }

        #saveButton {
            position: absolute;
            bottom: 20px;
            left: 110px;
        }

        #feedback {
            background-color: white;
            border: none;
            color: black;
            font-weight: bold;
            box-shadow: 0px 0px 20px rgba(0, 0, 0, 0.3);
        }

        #feedback option {
            background-color: white;
            color: black;
            font-weight: bold;
        }

        #feedback:focus {
            outline: none;
            box-shadow: 0px 0px 20px rgba(0, 0, 0, 0.3);
        }

        #visibleTextarea {
            z-index: 9999;
            position: relative;
        }
        .custom-text {
            font-size: 12px;
            color: lightgrey;
        }

        .custom-data {
            font-size: 12px;
        }

        .modal-loader {
            position: absolute;
            top: 50%;
            left: 35%;
            transform: translate(-50%, -50%);
            z-index: 2;
        }
        .carousel-inner {
            margin-top: 20px;
        }
        .carousel-control-prev,
        .carousel-control-next {
            filter: invert(1);
        }
        .modal.fade.show {
            display: block !important;
        }
    </style>

    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12">
            <div class="card border-0">
                <div class="row">
                    <div class="col-md-8">
                        <div class="card-header d-flex align-items-center">
                            <h3 class="card-title mb-0 mr-3">{{ __('All Transcribe Results') }}</h3>
                            <a class="refresh-button" href="#" data-tippy-content="Refresh Table">
                                <i class="fa fa-refresh table-action-buttons view-action-button"></i>
                            </a>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <x-date-range-inputs/>
                    </div>
                </div>
                <div class="card-body pt-2">
                    <!-- SET DATATABLE -->
                    <table id='userResultTable' class='table' width='100%'>
                        <thead>
                        <tr>
                            <th width="1%"></th>
                            <th width="7%">{{ __('Created On') }}</th>
                            <th width="7%">{{ __('Username') }}</th>
{{--                            <th width="10%">{{ __('Language') }}</th>--}}
                            <th width="7%">{{ __('Status') }}</th>
                            <th width="7%">{{ __('Text') }}</th>
                            <th width="2%"><i class="fa fa-music fs-14"></i></th>
                            <th width="2%"><i class="fa fa-cloud-download fs-14"></i></th>
                            <th width="4%">{{ __('Duration') }}</th>
{{--                            <th width="2%">{{ __('Words') }}</th>--}}
                            <th width="4%">{{ __('Actions') }}</th>
                        </tr>
                        </thead>
                    </table> <!-- END SET DATATABLE -->
                </div>
            </div>
        </div>
    </div>
    <div id="sidebar" class="sidebar bg-light d-none" style="z-index: 9999;">
        <h5 class="ml-3 mb-4" style="color: white">Image Details</h5>
        <div class="d-flex justify-content-between" >
            <div class="custom-text"><strong>User Name:</strong></div>
            <div class="custom-data"><p id="username" style="color: white"></p></div>
        </div>
        <div class="d-flex justify-content-between" style="color: white">
            <div class="custom-text"><strong>Folder Name:</strong></div>
            <div class="custom-data"><p id="folder"></p></div>
        </div>
        <div class="d-flex justify-content-between" style="color: white">
            <div class="custom-text"><strong>Status:</strong></div>
            <div class="custom-data"><p id="status"></p></div>
        </div>
        <div class="d-flex justify-content-between" style="color: white">
            <div class="custom-text"><strong>Created At:</strong></div>
            <div class="custom-data"><p id="created_at" style="color: white"></p></div>
        </div>
        <div class="d-flex justify-content-between" style="color: white">
            <div class="custom-text"><strong>Assigned User:</strong></div>
            <div class="custom-data"><p id="assigned_user"></p></div>
        </div>
        <div class="d-flex justify-content-between" style="color: white">
            <div class="custom-text"><strong>Duration:</strong></div>
            <div class="custom-data"><p id="duration"></p></div>
        </div>
        <div class="d-flex justify-content-between align-items-center mt-4">
            <div class="form-group mb-0">
                <label class="custom-text" for="remark" style="color: white">Remarks</label>
                <select class="form-control custom-data" id="remark" name="remark">
                    <option value="" selected>Select any remarks</option>
                    {{--                    @foreach($projectRemarks as $projectRemark)--}}
                    {{--                        <option value="{{$projectRemark->id}}">{{$projectRemark->remark}}</option>--}}
                    {{--                    @endforeach--}}
                </select>
            </div>
        </div>
        <div class=" mt-4" id="clickableDiv">
            <label class="custom-text" for="comment" style="color: white">Comment</label>
            <textarea class="form-control" id="visibleTextarea" rows="3"
                      style="z-index: 1001 !important;"></textarea>
        </div>
        <div class="d-flex justify-content-between align-items-center mt-4">
            <div class="form-group mb-0">
                <label class="custom-text" for="feedback" style="color: white">Feedback</label>
                <select class="form-control custom-data" id="feedback">
                    <option value="0" selected disabled>Select any feedback</option>
                    <option value="correct">Correct</option>
                    <option value="incorrect">Incorrect</option>
                </select>
            </div>
        </div>
        <button id="saveButton" type="button" class="btn btn-success mt-3 ml-3">Save</button>
        <button id="closeSidebar" type="button" class="btn btn-secondary mt-3 ml-3">Close</button>
    </div>

    <div class="modal fade" id="textDataViewerModal" tabindex="-1" aria-labelledby="textDataViewerModalLabel"
         aria-hidden="true" style="background-color: rgba(0, 0, 0, 0.5) !important;">
        <!-- ... -->
        <div class="modal-dialog modal-dialog-centered modal-xl">
            <div class="modal-content">
                <div class="modal-body p-0">
                    <div class="row no-gutters h-100">
                        <div class="container-fluid">
                            <div id="textDataCarousel" class="carousel slide">
                                <div class="carousel-inner">
                                    <!-- Carousel items will be added dynamically -->
                                </div>
                                <button id="prevButton" class="carousel-control-prev" type="button"
                                        data-bs-target="#textDataCarousel" data-bs-slide="prev">
                                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                    <span class="visually-hidden">Previous</span>
                                </button>
                                <button id="nextButton" class="carousel-control-next" type="button"
                                        data-bs-target="#textDataCarousel" data-bs-slide="next">
                                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                    <span class="visually-hidden">Next</span>
                                </button>
                            </div>
                        </div>
                        <div class=" mt-8">
                            <audio id="audioPlayer" controls></audio>
                        </div>
                        <div class=" mt-4">
                                    <textarea class="form-control" id="hiddenTextarea" rows="3"
                                              style="opacity: 0; position: absolute; left: -9999px;"></textarea>
                        </div>
                        <!-- ... -->
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- ... -->

@endsection

@section('js')
    <!-- Data Tables JS -->
    <script src="{{URL::asset('plugins/datatable/datatables.min.js')}}"></script>
    <script src="{{URL::asset('js/results.js')}}"></script>
    <!-- Green Audio Players JS -->
    <script src="{{ URL::asset('plugins/audio-player/green-audio-player.js') }}"></script>
    <script src="{{URL::asset('plugins/sweetalert/sweetalert2.all.min.js')}}"></script>
    <script src="{{ URL::asset('js/audio-player.js') }}"></script>
    <script type="text/javascript">
        // Get the input date string
        var inputDate = "{{$date}}";

        $(function () {

            "use strict";

            function format(d) {
                // `d` is the original data object for the row
                return '<div class="slider">' +
                    '<table class="details-table">' +
                    '<tr>' +
                    '<td class="details-title" width="10%">File Name:</td>' +
                    '<td>' + ((d.file_name == null) ? '' : d.file_name) + '</td>' +
                    '</tr>' +
                    '<tr>' +
                    '<td class="details-title" width="10%">Task Type:</td>' +
                    '<td>' + ((d.type == null) ? '' : d.type) + '</td>' +
                    '</tr>' +
                    '<tr>' +
                    '<td class="details-title" width="10%">Task ID:</td>' +
                    '<td>' + ((d.task_id == null) ? '' : d.task_id) + '</td>' +
                    '</tr>' +
                    '<tr>' +
                    '<td class="details-title" width="10%">Transcript:</td>' +
                    '<td>' + ((d.text == null) ? '' : d.text) + '</td>' +
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
            var table = $('#userResultTable').DataTable({
                "lengthMenu": [[25, 50, 100, -1], [25, 50, 100, "All"]],
                responsive: {
                    details: {type: 'column'}
                },
                colReorder: true,
                language: {
                    "emptyTable": "<div><img id='no-results-img' src='{{ URL::asset('img/files/no-result.png') }}'><br>{{ __('No trascribe tasks created yet') }}</div>",
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
                    url: "{{ route('admin.liveTranscriptionText.index') }}",
                    data: function (d) {
                        d.created_on_from = $('#created_on_from').val();
                        d.created_on_to = $('#created_on_to').val();
                        d.date = inputDate;
                        // add any other filters you want to pass to the backend here
                    }
                },
                columns: [{
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
                        data: 'username',
                        name: 'username',
                        orderable: true,
                        searchable: true
                    },

                    // {
                    //     data: 'custom-language',
                    //     name: 'custom-language',
                    //     orderable: true,
                    //     searchable: true
                    // },
                    {
                        data: 'custom-status',
                        name: 'custom-status',
                        orderable: true,
                        searchable: true
                    },
                    {
                        data: 'text_data',
                        name: 'text_data',
                        orderable: true,
                        searchable: true,
                        render: function (data, type, row) {
                            if (type === 'display') {
                                // Add the text data URL to the text_data_links array
                                row.text_data = [row.text_data];

                                return '<a href="#" class="text-data-link" data-text-src="' + row.text_data + '" data-audio-src="' + row.file_url + '">' + data + '</a>';
                            }
                            return data;
                        }
                    },
                    {
                        data: 'single',
                        name: 'single',
                        orderable: true,
                        searchable: true
                    },
                    {
                        data: 'download',
                        name: 'download',
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
                    }
                ],
                initComplete: function () {
                    $(".dataTables_filter input").attr("placeholder", "Enter search term");
                },
            });
            $('#textDataViewerModal').on('shown.bs.modal', function () {
                $(this).off('focusin.modal');
            });
            // add a change event listener to the date filter inputs
            $('#created_on_from, #created_on_to').on('change', function () {
                table.draw();
            });
            $('#closeSidebar').on('click', function () {
                $('#sidebar').addClass('d-none');
            });

            $('#imageViewerModal').on('hidden.bs.modal', function () {
                $('#sidebar').addClass('d-none');
            });
            $(document).on('click', '.text-data-link', function (e) {
                e.preventDefault();

                // Retrieve the data for the clicked row
                var row = table.row($(this).closest('tr')).data();
                var textDataLinks = row.text_data;

                var carouselInner = $('#textDataCarousel .carousel-inner');
                carouselInner.empty();

                for (var i = 0; i < textDataLinks.length; i++) {
                    var activeClass = i === 0 ? ' active' : '';
                    var textDataSrc = textDataLinks[i];

                    var carouselItem = '<div class="carousel-item' + activeClass + '" id="' + row.id + '">' +
                        '<div class="text-data-container d-block w-100">' + textDataSrc + '</div>' +
                        '</div>';
                    carouselInner.append(carouselItem);
                }
                console.log(row)
                // Set other details here, like in the previous example
                $('#username').html(row.name);
                $('#duration').html(row['custom-length']);
                if (row.csv_text && row.csv_text.folder && row.csv_text.folder.name) {
                    $('#folder').html(row.csv_text.folder.name);
                } else {
                    $('#folder').html('N/A'); // Set a default value (e.g., 'N/A') if the folder name is not available
                }
                if (row.csv_text && row.csv_text.folder && row.csv_text.folder.quality_assurance) {
                    $('#assigned_user').html(row.csv_text.folder.quality_assurance.name);
                } else {
                    $('#assigned_user').html('N/A'); // Set a default value (e.g., 'N/A') if the folder name is not available
                }
                    $('#created_at').html(row['created-column']);
                // Set audio source here
                $('#audioPlayer').attr('src', row.result);

                if (row.status == "IN_PROGRESS") {
                    var audioStatus = '<i class="fas fa-clock text-warning"></i> In Progress'; // added clock icon for Pending status
                } else if (row.status == 'complete') {
                    var audioStatus = '<i class="fas fa-check-circle text-success"></i> Complete'; // added check circle icon for active status
                } else {
                    var audioStatus = '<i class="fas fa-times-circle text-danger"></i> Failed'; // added times circle icon for other status
                }
                $('#status').html(audioStatus);
                if (row.comment) {
                    $('#visibleTextarea').val(row.comment);
                    $('#hiddenTextarea').val(row.comment);
                } else {
                    $('#visibleTextarea').val('');
                    $('#hiddenTextarea').val('');
                }                // Set other details here, like in the previous example
                $('#textDataViewerModal').modal('show');
                $('#sidebar').removeClass('d-none');
            });

            $(document).on('click', '#nextButton, #prevButton', function (e) {
                e.preventDefault();
                $('.modal-loader').show();

                var currentIndex = $('#textCarousel .carousel-item.active').index();
                var totalTexts = $('#textCarousel .carousel-item').length;

                var direction = $(this).attr('id') === 'nextButton' ? 'next' : 'prev';
                var nextIndex = direction === 'next' ? (currentIndex + 1) % totalTexts : (currentIndex - 1 + totalTexts) % totalTexts;
                var currentSlide = $('.carousel-item.active').attr('id');

                $.ajax({
                    url: '/admin/next-text', // Update the URL to the actual endpoint that returns the text data
                    type: 'GET',
                    data: {
                        text_id: currentSlide,
                        date: inputDate,
                        direction: direction
                    },
                    success: function (response) {
                        console.log(response);
                            if (response.id) {
                                var currentActiveItem = $('#textDataCarousel .carousel-item.active');
                                var currentIndex = currentActiveItem.index();

                                var nextCarouselItem = $('<div class="carousel-item active" id="' + response.id + '"></div>');
                                var nextText = $('<div class="text-data-container"></div>');
                                console.log(nextText)
                                nextText.html(response.csv_text.text);
                                console.log(response.csv_text.text)

                                nextCarouselItem.append(nextText);
                                currentActiveItem.removeClass('active');
                                $('#textDataCarousel .carousel-inner').append(nextCarouselItem);
                                $('#textDataCarousel .carousel-item:first-child').remove();

                                nextText[0].offsetHeight;
                                nextText.html(response.csv_text.text);


                                // Update other HTML elements with the response data, similar to the image code
                                $('#username').html(response.user.name);
                                $('#duration').html(formatDuration(response.length));
                                if (response.csv_text && response.csv_text.folder && response.csv_text.folder.name) {
                                    $('#folder').html(response.csv_text.folder.name);
                                } else {
                                    $('#folder').html('N/A'); // Set a default value (e.g., 'N/A') if the folder name is not available
                                }
                                if (response.csv_text && response.csv_text.folder && response.csv_text.folder.quality_assurance) {
                                    $('#assigned_user').html(response.csv_text.folder.quality_assurance.name);
                                } else {
                                    $('#assigned_user').html('N/A'); // Set a default value (e.g., 'N/A') if the folder name is not available
                                }
                                $('#created_at').html(response.created_at);
                                // Set audio source here
                                $('#audioPlayer').attr('src', response.file_url);

                                if (response.status == "IN_PROGRESS") {
                                    var audioStatus = '<i class="fas fa-clock text-warning"></i> In Progress'; // added clock icon for Pending status
                                } else if (response.status == 'complete') {
                                    var audioStatus = '<i class="fas fa-check-circle text-success"></i> Complete'; // added check circle icon for active status
                                } else {
                                    var audioStatus = '<i class="fas fa-times-circle text-danger"></i> Failed'; // added times circle icon for other status
                                }
                                $('#status').html(audioStatus);
                                if (response.comment) {
                                    $('#visibleTextarea').val(response.comment);
                                    $('#hiddenTextarea').val(response.comment);
                                } else {
                                    $('#visibleTextarea').val('');
                                    $('#hiddenTextarea').val('');
                                }
                            }
                            $('.modal-loader').hide();
                        },
                    error: function (xhr) {
                        console.log(xhr);
                        $('.modal-loader').hide();
                    }
                });
            });
            function formatDuration(seconds) {
                var hours = Math.floor(seconds / 3600);
                var minutes = Math.floor((seconds - (hours * 3600)) / 60);
                var remainingSeconds = seconds - (hours * 3600) - (minutes * 60);

                if (hours < 10) { hours = "0" + hours; }
                if (minutes < 10) { minutes = "0" + minutes; }
                if (remainingSeconds < 10) { remainingSeconds = "0" + remainingSeconds; }
                return hours + ':' + minutes + ':' + remainingSeconds;
            }
            $('#userResultTable tbody').on('click', 'td.details-control', function () {
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
            // ACTIVATE Transcription
            $(document).on('click', '.agreeTranscriptionButton', function (e) {

                e.preventDefault();

                var formData = new FormData();
                formData.append("id", $(this).attr('id'));

                $.ajax({
                    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                    method: 'post',
                    url: '/admin/live/transcription/results/agree',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function (data) {
                        if (data == 'success') {
                            Swal.fire('{{ __('Transcription Completed') }}', '{{ __('Transcription of selected user has been passed successfully') }}', 'success');
                            $("#userResultTable").DataTable().ajax.reload();
                        } else {
                            Swal.fire('{{ __('Transcription Already Completed') }}', '{{ __('Transcription of selected user is already activated') }}', 'error');
                        }
                    },
                    error: function (data) {
                        Swal.fire({type: 'error', title: 'Oops...', text: 'Something went wrong!'})
                    }
                })

            });


            // DEACTIVATE Transcription
            $(document).on('click', '.disagreeTranscriptionButton', function (e) {

                e.preventDefault();

                var formData = new FormData();
                formData.append("id", $(this).attr('id'));

                $.ajax({
                    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                    method: 'post',
                    url: '/admin/live/transcription/results/disagree',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function (data) {
                        if (data == 'success') {
                            Swal.fire('{{ __('Transcription Failed') }}', '{{ __('Transcription of selected user has been failed successfully') }}', 'success');
                            $("#userResultTable").DataTable().ajax.reload();
                        } else {
                            Swal.fire('{{ __('Language Already Failed') }}', '{{ __('Transcription of selected user is already failed') }}', 'error');
                        }
                    },
                    error: function (data) {
                        Swal.fire({type: 'error', title: 'Oops...', text: 'Something went wrong!'})
                    }
                })
            });

            $('.refresh-button').on('click', function (e) {
                e.preventDefault();
                $("#userResultTable").DataTable().ajax.reload();
            });


            // DELETE SYNTHESIZE RESULT
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
                                    $("#userResultTable").DataTable().ajax.reload();
                                } else {
                                    Swal.fire('{{ __('Delete Failed') }}', '{{ __('There was an error while deleting this result') }}', 'error');
                                }
                            },
                            error: function (data) {
                                Swal.fire('Oops...', 'Something went wrong!', 'error')
                            }
                        })
                    }
                })
            });

            $(document).on('click', '#saveButton', function (e) {
                e.preventDefault();

                // Retrieve the current active image element ID
                var currentSlide = $('.carousel-item.active').attr('id');

                // Retrieve the comment and feedback values
                var comment     = $('#visibleTextarea').val();
                var feedback    = $('#feedback').val();
                var remark      = $('#remark').val();

                // Send the data to the server using AJAX
                $.ajax({
                    url: '/admin/save-feedback-result',
                    type: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        image_id: currentSlide,
                        comment: comment,
                        feedback: feedback,
                        remark: remark
                    },
                    success: function (response) {
                        $("#nextButton").click();
                    },
                    error: function (xhr) {
                        console.log(xhr.responseText);
                    }
                });
            });
            $(document).on('click', '#clickableDiv', function () {
                var otherTextarea = document.getElementById("hiddenTextarea");
                otherTextarea.focus();

            });

            $(document).on('keyup', '#hiddenTextarea', function () {
                var text = $(this).val();
                $('#visibleTextarea').val(text);
            });

        });
    </script>
@endsection
