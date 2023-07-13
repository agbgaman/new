@extends('layouts.app')
@section('css')
    <!-- Data Table CSS -->
    <link href="{{URL::asset('plugins/datatable/datatables.min.css')}}" rel="stylesheet" />
@endsection
@section('page-header')
    <!-- PAGE HEADER -->
    <div class="page-header mt-5-7">
        <div class="page-leftheader">
            <h4 class="page-title mb-0">{{ __('Transcribe Results') }}</h4>
            <ol class="breadcrumb mb-2">
                <li class="breadcrumb-item"><a href="{{route('user.dashboard')}}"><i class="fa-solid fa-folder-music mr-2 fs-12"></i>{{ __('User') }}</a></li>
                <li class="breadcrumb-item" aria-current="page"><a href="{{route('user.transcribe.file')}}"> {{ __('Transcribe Studio') }}</a></li>
                <li class="breadcrumb-item active" aria-current="page"><a href="{{url('#')}}"> {{ __('Transcribe Results') }}</a></li>
            </ol>
        </div>
    </div>
    <!-- END PAGE HEADER -->
@endsection
@section('content')
    <style>
        #imageCarousel {
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.8);
        }
        .modal-content {
            background-color: transparent;
            border: none;
            box-shadow: none;
        }
        .modal-body {
            padding: 0;
        }
        .carousel-inner {
            max-width: 80%;
            max-height: 80%;
            width: 100%; /* Add this line */
            margin: auto;
        }
        .carousel-item img {
            max-width: 100%;
            max-height: 100%;
        }
        .carousel-inner {
            height: 100%;
        }
        .carousel-item {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            width: 100%;
            height: auto;
            text-align: center;
        }
    </style>
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12">
            <div class="row mb-3">
                <div class="col-lg-9"></div>
                <div class="col-lg-3 text-right">
                    <button  class="btn btn-primary " id="submit-button">Download All Recording</button>
                </div>
            </div>
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
    </div>
    <div class="modal fade" id="imageCarouselModal" tabindex="-1" aria-labelledby="imageCarouselModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-xl">
            <div class="modal-content">
                <div class="modal-body">
                    <div class="container-fluid">
                        <div id="imageCarousel" class="carousel slide">
                            <div class="carousel-inner">
                                <!-- Carousel items will be added dynamically -->
                            </div>
                            <button class="carousel-control-prev" type="button" data-bs-target="#imageCarousel" data-bs-slide="prev">
                                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                <span class="visually-hidden">Previous</span>
                            </button>
                            <button class="carousel-control-next" type="button" data-bs-target="#imageCarousel" data-bs-slide="next">
                                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                <span class="visually-hidden">Next</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
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
                return '<div class="slider">'+
                    '<table class="details-table">'+
                    '<tr>'+
                    '<td class="details-title" width="10%">File Name:</td>'+
                    '<td>'+ ((d.file_name == null) ? '' : d.file_name) +'</td>'+
                    '</tr>'+
                    '<tr>'+
                    '<td class="details-title" width="10%">Task Type:</td>'+
                    '<td>'+ ((d.type == null) ? '' : d.type) +'</td>'+
                    '</tr>'+
                    '<tr>'+
                    '<td class="details-title" width="10%">Task ID:</td>'+
                    '<td>'+ ((d.task_id == null) ? '' : d.task_id) +'</td>'+
                    '</tr>'+
                    '<tr>'+
                    '<td class="details-title" width="10%">Transcript:</td>'+
                    '<td>'+ ((d.text == null) ? '' : d.text) +'</td>'+
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
                ajax: {
                    url: "{{ route('admin.liveTranscription.index') }}",
                    data: function (d) {
                        d.created_on_from = $('#created_on_from').val();
                        d.created_on_to = $('#created_on_to').val();
                        d.user = {{$user}};
                    }
                },
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
                        searchable: true
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
                    // {
                    //     data: 'format',
                    //     name: 'format',
                    //     orderable: true,
                    //     searchable: true
                    // },
                    // {
                    //     data: 'file_size',
                    //     name: 'file_size',
                    //     orderable: true,
                    //     searchable: true
                    // },
                    {
                        data: 'words',
                        name: 'words',
                        orderable: true,
                        searchable: true
                    },
                    // {
                    //     data: 'custom-mode',
                    //     name: 'custom-mode',
                    //     orderable: true,
                    //     searchable: true
                    // },
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
            function loadImageCarousel(images) {
                var carouselInner = $('#imageCarousel .carousel-inner');
                carouselInner.empty();

                for (var i = 0; i < images.length; i++) {
                    var activeClass = i === 0 ? ' active' : '';
                    var carouselItem = '<div class="carousel-item' + activeClass + '"><img src="' + images[i] + '" class="d-block w-100" alt="Image ' + (i + 1) + '"></div>';
                    carouselInner.append(carouselItem);
                }

                $('#imageCarouselModal').modal('show');
            }

            $(document).on('click', '.widget-user-image-sm img', function() {
                var images = [
                    'https://gtsdashbucket.s3.eu-west-1.amazonaws.com/Phandar-Valley-Beautiful-places-in-Pakistan-Depositphotos.jpg',
                    'https://gtsdashbucket.s3.eu-west-1.amazonaws.com/iguazu-falls-argentina-brazil-MOSTBEAUTIFUL0921-e967cc4764ca4eb2b9941bd1b48d64b5.jpg',
                    'https://gtsdashbucket.s3.eu-west-1.amazonaws.com/E3bTQWWDs6DPITBylSIG7B3UJMwInSTLRwoL8Zsl.jpg'
                    // Add the image URLs of all the images you want to display in the carousel
                    // For example, you can use the 'src' attribute of all the image thumbnails in the DataTable
                ];


                loadImageCarousel(images);
            });

            // Initialize the carousel when the modal is shown
            $('#imageCarouselModal').on('shown.bs.modal', function() {
                var carousel = new bootstrap.Carousel(document.getElementById('imageCarousel'), {
                    interval: false
                });
            });

            // Reset the carousel when the modal is hidden
            $('#imageCarouselModal').on('hidden.bs.modal', function() {
                $('#imageCarousel').carousel('dispose');
            });



            $(document).on('click', '.widget-user-image-sm img', function() {
                var imgSrc = $(this).attr('src');

                // Set the large image source in the slider
                $('#largeImage').attr('src', imgSrc);

                // Show the slider
                $('#imageSlider').css('display', 'block');
            });

            // Close the slider when the close button is clicked
            $('.close').click(function() {
                $('#imageSlider').css('display', 'none');
            });


            $('#userResultTable tbody').on('click', 'td.details-control', function () {
                var tr = $(this).closest('tr');
                var row = table.row( tr );

                if ( row.child.isShown() ) {
                    // This row is already open - close it
                    $('div.slider', row.child()).slideUp( function () {
                        row.child.hide();
                        tr.removeClass('shown');
                    } );
                }
                else {
                    // Open this row
                    row.child( format(row.data()), 'no-padding' ).show();
                    tr.addClass('shown');

                    $('div.slider', row.child()).slideDown();
                }
            });


            $('.refresh-button').on('click', function(e){
                e.preventDefault();
                $("#userResultTable").DataTable().ajax.reload();
            });

            // ACTIVATE Transcription
            $(document).on('click', '.agreeTranscriptionButton', function(e) {

                e.preventDefault();

                var formData = new FormData();
                formData.append("id", $(this).attr('id'));

                $.ajax({
                    headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
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
                    error: function(data) {
                        Swal.fire({ type: 'error', title: 'Oops...', text: 'Something went wrong!' })
                    }
                })
                0
            });


            // DEACTIVATE Transcription
            $(document).on('click', '.disagreeTranscriptionButton', function(e) {

                e.preventDefault();

                var formData = new FormData();
                formData.append("id", $(this).attr('id'));

                $.ajax({
                    headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
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
                    error: function(data) {
                        Swal.fire({ type: 'error', title: 'Oops...', text: 'Something went wrong!' })
                    }
                })

            });
            // DELETE SYNTHESIZE RESULT
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
                                    $("#userResultTable").DataTable().ajax.reload();
                                } else {
                                    Swal.fire('{{ __('Delete Failed') }}', '{{ __('There was an error while deleting this result') }}', 'error');
                                }
                            },
                            error: function(data) {
                                Swal.fire('Oops...','Something went wrong!', 'error')
                            }
                        })
                    }
                })
            });

            $('#submit-button').click(function (e) {
                downloadAllRecordings(e);
            });
            function downloadAllRecordings() {
                var totalData = {{$transcribeResultsTotal}};
                var batchSize = 10;
                var batches = Math.ceil(totalData / batchSize);
                console.log(totalData, batchSize, batches)

                // disable the submit button while uploading
                $('#submit-button').prop('disabled', true);

                // Send the AJAX requests for each batch
                for (var i = 0; i < batches; i++) {
                    var start = i * batchSize;
                    var end = Math.min(start + batchSize, totalData);
                    var ids = []; // The ids of the rows in the current batch

                    // for (var j = start; j < end; j++) {
                    //     ids.push(rows[j].id);
                    // }

                    console.log('Request ' + (i + 1) + ': ' + ids.join(','));

                    // Send the AJAX request for the current batch
                    $.ajax({
                        url: '/live/transcription/results/download-all-audio',
                        type: 'GET',
                        data: { requestNumber: i + 1 , user: {{$user}},total: totalData, batch:batchSize},
                        xhrFields: {
                            responseType: 'blob' // Set the expected response type to 'blob'
                        },
                        success: function (data) {
                            var blob = new Blob([data], { type: 'application/zip' });
                            var url = window.URL.createObjectURL(blob);
                            var a = document.createElement('a');
                            a.href = url;
                            a.download = 'audios.zip';
                            document.body.appendChild(a);
                            a.click();
                            document.body.removeChild(a);
                        },
                        error: function (xhr, status, error) {
                            console.log('Error for request ' + (i + 1) + ': ' + error);
                        }
                    });

                }
            }
        });
    </script>
@endsection
