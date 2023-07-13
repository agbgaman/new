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
            z-index: 1050;
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

        .modal-content {
            background-color: transparent;
            border: none;
            box-shadow: none;
        }

        .modal-xl {
            margin-right: 30%; /* Add margin to the right of the modal */
            max-width: none;
        }

        .carousel-item img {
            max-width: 100%;
            max-height: 100%;
        }

        .sidebar {
            position: fixed;
            top: 5%;
            right: 8px;
            width: 300px;
            height: 90%;
            padding: 20px;
            overflow-y: auto;
            z-index: 2000;
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
    </style>
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12">
            <div class="card border-0">
                <div class="row">
                    <div class="col-md-8">
                        <div class="card-header d-flex align-items-center">
                            <h3 class="card-title">{{ __('All Transcribe Results') }}</h3>
                            <a class="refresh-button" href="#" data-tippy-content="Refresh Table">
                                <i class="fa fa-refresh table-action-buttons view-action-button"></i>
                            </a>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <x-date-range-inputs />
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
                            <th width="10%">{{ __('Language') }}</th>
                            <th width="7%">{{ __('Status') }}</th>
                            <th width="7%">{{ __('Image') }}</th>
                            <th width="2%"><i class="fa fa-music fs-14"></i></th>
                            <th width="2%"><i class="fa fa-cloud-download fs-14"></i></th>
                            <th width="4%">{{ __('Duration') }}</th>
                            <th width="2%">{{ __('Words') }}</th>
                            <th width="4%">{{ __('Actions') }}</th>
                        </tr>
                        </thead>
                    </table> <!-- END SET DATATABLE -->
                </div>
            </div>
        </div>
        <div class="modal fade" id="imageViewerModal" tabindex="-1" aria-labelledby="imageViewerModalLabel"
             aria-hidden="true" style="background-color: rgba(0, 0, 0, 0.5) !important;">
            <div class="modal-dialog modal-dialog-centered modal-xl">
                <div class="modal-content">
                    <div class="modal-body p-0">
                        <div class="row no-gutters h-100">
                            <div class="container-fluid">
                                <div id="imageCarousel" class="carousel slide">
                                    <div class="carousel-inner">
                                        <!-- Carousel items will be added dynamically -->
                                    </div>
                                    <button class="carousel-control-prev" type="button" data-bs-target="#imageCarousel"
                                            data-bs-slide="prev">
                                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                        <span class="visually-hidden">Previous</span>
                                    </button>
                                    <button class="carousel-control-next" type="button" data-bs-target="#imageCarousel"
                                            data-bs-slide="next">
                                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                        <span class="visually-hidden">Next</span>
                                    </button>
                                </div>
                                <audio id="audio" controls>
                                    <source
                                        src="https://gtsdashbucket.s3.eu-west-1.amazonaws.com/aws/Phandar-Valley-Beautiful-places-in-Pakistan-Depositphotos.wav"
                                        type="audio/wav">
                                    Your browser does not support the audio element.
                                </audio>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div id="sidebar" class="sidebar bg-light d-none">
            <h5 class="ml-3 mb-4" style="color: white">Image Details</h5>
            <div class="d-flex justify-content-between" style="color: white">
                <div><strong>User Name:</strong></div>
                <div><p id="username">Usama</p></div>
            </div>
            <div class="d-flex justify-content-between" style="color: white">
                <div><strong>Duration:</strong></div>
                <div><p id="duration">10 sec</p></div>
            </div>
            <div class="d-flex justify-content-between" style="color: white">
                <div><strong>Status:</strong></div>
                <div><p id="status">In Progress</p></div>
            </div>
            <div class="form-group mt-4">
                <label for="comment" style="color: white">Comment</label>
                <textarea class="form-control" id="comment" rows="3"></textarea>
            </div>

            <div class="d-flex justify-content-between align-items-center mt-4">
                <div class="form-group mb-0">
                    <label for="feedback" style="color: white">Feedback</label>
                    <select class="form-control" id="feedback">
                        <option value="correct">Correct</option>
                        <option value="incorrect">Incorrect</option>
                    </select>
                </div>
            </div>
            <button id="closeSidebar" type="button" class="btn btn-secondary mt-3 ml-3">Close</button>
        </div>
    </div>
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
                    url: "{{ route('admin.liveTranscription.index') }}",
                    data: function (d) {
                        d.created_on_from = $('#created_on_from').val();
                        d.created_on_to = $('#created_on_to').val();
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

                    {
                        data: 'custom-language',
                        name: 'custom-language',
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
                        data: 'image_id',
                        name: 'image_id',
                        orderable: true,
                        searchable: true,
                        render: function (data, type, row) {
                            if (type === 'display') {
                                // Add the image URL to the images array
                                row.images = [row.image];

                                return '<a href="#" class="image-link" data-image-src="' + row.image + '" data-image-audio="' + row.result + '">' + data + '</a>';
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
                        data: 'words',
                        name: 'words',
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
            // add a change event listener to the date filter inputs
            $('#created_on_from, #created_on_to').on('change', function () {
                table.draw();
            });
            $(document).on('click', '.image-link', function (e) {
                e.preventDefault();

                // Retrieve the data for the clicked row
                var row = table.row($(this).closest('tr')).data();
                console.log(row)
                var images = row.images;

                var carouselInner = $('#imageCarousel .carousel-inner');
                carouselInner.empty();

                for (var i = 0; i < images.length; i++) {
                    var activeClass = i === 0 ? ' active' : '';
                    var carouselItem = '<div class="carousel-item' + activeClass + '"><img src="' + images[i] + '" class="d-block w-100"  alt="Image ' + (i + 1) + '"></div>';
                    carouselInner.append(carouselItem);
                }
                // Set image details here, e.g., filename, size, dimensions
                $('#username').text(row.name);
                $('#duration').text(row.duration);
                if (row.status == 'IN_PROGRESS') {
                    var audioStatus = 'In Progress';
                } else if (row.status == 'COMPLETED') {
                    var audioStatus = 'Complete';
                } else {
                    var audioStatus = 'Failed';
                }
                $('#status').text(audioStatus);
                $('#imageViewerModal').modal('show');

                $('#sidebar').removeClass('d-none');
            });
            $(window).on('resize', function () {
                if ($('#imageViewerModal').hasClass('show')) {
                    adjustModalWidth();
                }
            });
            // Initialize the carousel when the modal is shown
            $('#imageViewerModal').on('shown.bs.modal', function () {
                var carousel = new bootstrap.Carousel(document.getElementById('imageCarousel'), {
                    interval: false // set interval option to false
                });
            });

            function adjustModalWidth() {
                var imagePreview = document.getElementById('imagePreview');
                var modalDialog = document.querySelector('.modal-dialog.modal-xl');
                var maxWidth = Math.min(window.innerWidth - 400, imagePreview.clientWidth);
                modalDialog.style.maxWidth = maxWidth + 'px';
            }

            $('#closeSidebar').on('click', function () {
                $('#sidebar').addClass('d-none');
            });
            $('#imageViewerModal').on('hidden.bs.modal', function () {
                $('#sidebar').addClass('d-none');
            });

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

        });
    </script>
@endsection
