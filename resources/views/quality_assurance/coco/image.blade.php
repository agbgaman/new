@extends('layouts.app')

@section('css')
    <!-- Data Table CSS -->
    <link href="{{URL::asset('plugins/datatable/datatables.min.css')}}" rel="stylesheet"/>
    <!-- Sweet Alert CSS -->
    <link href="{{URL::asset('plugins/sweetalert/sweetalert2.min.css')}}" rel="stylesheet"/>
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" />

@endsection

@section('page-header')
    <!-- PAGE HEADER -->
    <div class="page-header mt-5-7">
        <div class="page-leftheader">
            <h4 class="page-title mb-0">{{ __('All Images') }}</h4>
            <ol class="breadcrumb mb-2">
                <li class="breadcrumb-item"><a href="{{ route('user.dashboard') }}"><i
                            class="fa-solid fa-user-shield mr-2 fs-12"></i>{{ __('User') }}</a></li>
                <li class="breadcrumb-item" aria-current="page"><a
                        href="{{ route('qa.coco-folder') }}"> {{ __('Folders') }}</a></li>
                <li class="breadcrumb-item active" aria-current="page"><a href="#"> {{ __('User Images') }}</a></li>
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

        .carousel-image {
            max-height: 600px !important; /* Replace with your desired height */
            object-fit: contain; /* To maintain the aspect ratio of the image */
        }

        .custom-text {
            font-size: 12px;
            color: lightgrey;
        }

        .custom-data {
            font-size: 12px;
        }

        .carousel-image {
            max-height: 600px !important;
            object-fit: contain;
            transform: rotate(0deg); /* Add initial rotation of 0 degrees */
        }

        .carousel-image.rotated {
            transform: rotate(90deg) !important; /* Add new rotation of 90 degrees */
        }
        .modal-loader {
            position: absolute;
            top: 50%;
            left: 35%;
            transform: translate(-50%, -50%);
            z-index: 2;
        }

    </style>
    <!-- USERS LIST DATA TABEL -->
    <div class="row">
        <div class="col-lg-12 col-md-12 col-xm-12">
            <div class="card border-0">
                <div class="card-header flex justify-content-between">
                    <h3 class="card-title">{{ __('Images Management') }}</h3>
                </div>
                <div class="card-body pt-2">
                    <!-- BOX CONTENT -->
                    <div class="box-content">

                        <!-- DATATABLE -->
                        <table id='listFoldersTable' class='table listFoldersTable' width='100%'>
                            <thead>
                            <tr>
                                <th width="15%">{{ __('Image') }}</th>
                                <th width="15%">{{ __('UserName') }}</th>
{{--                                <th width="7%">{{ __('Quality Assurance') }}</th>--}}
                                <th width="7%">{{ __('Folder') }}</th>
                                <th width="7%">{{ __('Status') }}</th>
                                <th width="7%">{{ __('Created On') }}</th>
                                <th width="8%">{{ __('Actions') }}</th>
                            </tr>
                            </thead>
                        </table>
                        <!-- END DATATABLE -->

                    </div> <!-- END BOX CONTENT -->
                </div>
            </div>
        </div>
        <div class="modal fade" id="imageViewerModal" tabindex="-1" aria-labelledby="imageViewerModalLabel"
             aria-hidden="true" style="background-color: rgba(0, 0, 0, 0.5) !important;">
            <div class="modal-loader" style="display: none;">
                <div class="spinner-border text-primary" role="status">
                    <span class="sr-only">Loading...</span>
                </div>
            </div>
            <div class="modal-dialog modal-dialog-centered modal-xl">
                <div class="modal-content">
                    <div class="modal-body p-0">
                        <div class="row no-gutters h-100">
                            <div class="container-fluid">
                                <div id="imageCarousel" class="carousel slide">
                                    <div class="carousel-inner">
                                        <!-- Carousel items will be added dynamically -->
                                    </div>
                                    <button id="prevButton" class="carousel-control-prev" type="button"
                                            data-bs-target="#imageCarousel" data-bs-slide="prev">
                                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                        <span class="visually-hidden">Previous</span>
                                    </button>
                                    <button id="nextButton" class="carousel-control-next" type="button"
                                            data-bs-target="#imageCarousel" data-bs-slide="next">
                                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                        <span class="visually-hidden">Next</span>
                                    </button>
                                </div>
                                <div class=" mt-4">
                                    <textarea class="form-control" id="hiddenTextarea" rows="3"
                                              style="opacity: 0; position: absolute; left: -9999px;"></textarea>
                                </div>
                                <div class="text-center mt-4">
                                    <button id="rotateButton" class="btn btn-primary"><i class="fas fa-undo-alt"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div id="sidebar" class="sidebar bg-light d-none">
            <h5 class="ml-3 mb-4" style="color: white">Image Details</h5>
            <div class="d-flex justify-content-between" style="color: white">
                <div class="custom-text"><strong>User Name:</strong></div>
                <div class="custom-data"><p id="username"></p></div>
            </div>
            <div class="d-flex justify-content-between" style="color: white">
                <div class="custom-text"><strong>Folder Name:</strong></div>
                <div class="custom-data"><p id="duration"></p></div>
            </div>
            <div class="d-flex justify-content-between" style="color: white">
                <div class="custom-text"><strong>Status:</strong></div>
                <div class="custom-data"><p id="status"></p></div>
            </div>
            <div class="d-flex justify-content-between" style="color: white">
                <div class="custom-text"><strong>Dimension:</strong></div>
                <div class="custom-data"><p id="dimensions"></p></div>
            </div>
            <div class="d-flex justify-content-between" style="color: white">
                <div class="custom-text"><strong>Created At:</strong></div>
                <div class="custom-data"><p id="created_at"></p></div>
            </div>
            <div class="d-flex justify-content-between" style="color: white">
                <div class="custom-text"><strong>Assigned User:</strong></div>
                <div class="custom-data"><p id="assigned_user"></p></div>
            </div>
            <div class="d-flex justify-content-between" style="color: white">
                <div class="custom-text"><strong>Geo Location:</strong></div>
                <div class="custom-data"><p id="geo_location"></p></div>
            </div>
            <div class="d-flex justify-content-between align-items-center mt-4">
                <div class="form-group mb-0">
                    <label class="custom-text" for="remark" style="color: white">Remarks</label>
                    <select class="form-control custom-data" id="remark" name="remark">
                        <option value="0" selected disabled>Select any remarks</option>
                        @foreach($projectRemarks as $projectRemark)
                            <option value="{{$projectRemark->id}}">{{$projectRemark->remark}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class=" mt-4" id="clickableDiv">
                <label class="custom-text" for="comment" style="color: white">Comment</label>
                <textarea class="form-control" id="visibleTextarea" rows="3"
                          style="z-index: 9999 !important;"></textarea>
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
    </div>
    <!-- END USERS LIST DATA TABEL -->
@endsection

@section('js')
    <!-- Data Tables JS -->
    <script src="{{URL::asset('plugins/datatable/datatables.min.js')}}"></script>
    <script src="{{URL::asset('plugins/sweetalert/sweetalert2.all.min.js')}}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/exif-js/2.3.0/exif.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>


    <script type="text/javascript">
        $(function () {

            "use strict";

            var table = $('#listFoldersTable').DataTable({
                "lengthMenu": [[25, 50, 100, -1], [25, 50, 100, "All"]],
                responsive: true,
                colReorder: true,
                "order": [[0, "desc"]],
                language: {
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
                    url: "{{ route('qa.coco-imagesList') }}",
                    data: {
                        folder_id: {{$id}}
                    }
                },
                columns: [
                    {
                        data: 'image',
                        name: 'image',
                        orderable: true,
                        searchable: true,
                        render: function (data, type, row) {
                            if (type === 'display') {
                                // Add the image URL to the images array
                                row.image_link = [row.image_link];

                                return '<a href="#" class="image-link" data-image-src="' + row.image_link + '" data-image-audio="' + row.result + '">' + data + '</a>';
                            }
                            return data;
                        }
                    },
                    {
                        data: 'username',
                        name: 'username',
                        orderable: true,
                        searchable: true
                    },
                    // {
                    //     data: 'quality_assurance',
                    //     name: 'quality_assurance',
                    //     orderable: true,
                    //     searchable: true
                    // },
                    {
                        data: 'folderName',
                        name: 'folderName',
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
                        data: 'created-on',
                        name: 'created-on',
                        orderable: true,
                        searchable: true
                    },
                    {
                        data: 'actions',
                        name: 'actions',
                        orderable: false,
                        searchable: false
                    },
                ],
                initComplete: function () {
                    $(".dataTables_filter input").attr("placeholder", "Enter search term");
                },
            });

            $(document).on('click', '.image-link', function (e) {
                e.preventDefault();

                // Retrieve the data for the clicked row
                var row = table.row($(this).closest('tr')).data();
                var images = row.image_link;

                var carouselInner = $('#imageCarousel .carousel-inner');
                carouselInner.empty();

                for (var i = 0; i < images.length; i++) {
                    var activeClass = i === 0 ? ' active' : '';
                    var imageSrc = images[i];

                    // Get the saved rotation from localStorage, or default to 0
                    var savedRotation = parseInt(localStorage.getItem('imageRotation_' + imageSrc)) || 0;

                    var carouselItem = '<div class="carousel-item' + activeClass + '" id="' + row.id + '">' +
                        '<img src="' + imageSrc + '" class="d-block w-100 carousel-image" alt="Image ' + (i + 1) + '" data-rotation="' + savedRotation + '" style="transform: rotate(' + savedRotation + 'deg);">' +
                        '</div>';
                    carouselInner.append(carouselItem);
                }
                if (row.remark_id) {
                    $('#remark option[value="' + row.remark_id + '"]').attr('selected', 'selected');
                }

                // Set image details here, e.g., filename, size, dimensions
                $('#username').html(row.imageUserName);
                $('#duration').html(row.folderName);
                $('#created_at').html(row['created-on']);
                $('#assigned_user').html(row['quality_assurance']);

                if (row.status == 'Pending') {
                    var audioStatus = '<i class="fas fa-clock text-warning"></i> In Progress'; // added clock icon for Pending status
                } else if (row.status == 'active') {
                    var audioStatus = '<i class="fas fa-check-circle text-success"></i> Complete'; // added check circle icon for active status
                } else {
                    var audioStatus = '<i class="fas fa-times-circle text-danger"></i> Failed'; // added times circle icon for other status
                }

                $('#status').html(audioStatus);

                // Get dimensions and geolocation information
                getImageDetails(images[0], 'button', function (dimensions) {
                    console.log(dimensions)
                    $('#dimensions').text(dimensions.width + 'x' + dimensions.height);

                });
                if (row.comment) {
                    $('#visibleTextarea').val(row.comment);
                    $('#hiddenTextarea').val(row.comment);
                } else {
                    $('#visibleTextarea').val('');
                    $('#hiddenTextarea').val('');
                }

                $('#imageViewerModal').modal('show');
                $('#sidebar').removeClass('d-none');
            });
            $(document).on('click', '#rotateButton', function (e) {
                e.preventDefault();
                // Get the active carousel item
                var activeItem = $('#imageCarousel .carousel-item.active');
                // Get the image in the active carousel item
                var image = activeItem.find('.carousel-image');
                // Get the image src attribute to use as a unique key
                var imageSrc = image.attr('src');
                // Get the current rotation from the data attribute, or default to 0
                var currentRotation = parseInt(image.data('rotation')) || 0;
                // Calculate the new rotation
                var newRotation = (currentRotation + 90) % 360;
                // Update the data attribute with the new rotation
                image.data('rotation', newRotation);
                // Update the CSS transform property with the new rotation
                image.css('transform', 'rotate(' + newRotation + 'deg)');
                // Save the rotation to localStorage
                localStorage.setItem('imageRotation_' + imageSrc, newRotation);
            });

            function getImageDetails(imageSrc, type = null, callback) {
                if (type == 'button') {
                    var imageLink = imageSrc;
                } else {
                    var imageLink = imageSrc[0];
                }

                var img = new Image();
                img.src = imageLink;
                img.onload = function () {
                    var dimensions = {
                        width: this.width,
                        height: this.height
                    };

                    callback(dimensions);
                };
            }

            function getImageDetailsButton(imageSrc, callback) {
                console.log(imageSrc)

                var img = new Image();
                img.src = imageSrc;
                img.onload = function () {
                    var dimensions = {
                        width: this.width,
                        height: this.height
                    };

                    callback(dimensions);
                };
            }

            // Initialize the carousel when the modal is shown
            $('#imageViewerModal').on('shown.bs.modal', function () {
                var carousel = new bootstrap.Carousel(document.getElementById('imageCarousel'), {
                    interval: false // set interval option to false
                });
            });

            $('#closeSidebar').on('click', function () {
                $('#sidebar').addClass('d-none');
            });

            $('#imageViewerModal').on('hidden.bs.modal', function () {
                $('#sidebar').addClass('d-none');
            });

            // DELETE CONFIRMATION
            $(document).on('click', '.deleteUserButton', function (e) {

                e.preventDefault();

                Swal.fire({
                    title: '{{ __('Confirm Image Deletion') }}',
                    text: '{{ __('Warning! This action will delete image permanently') }}',
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
                            url: 'image-delete',
                            data: formData,
                            processData: false,
                            contentType: false,
                            success: function (data) {
                                if (data == 'success') {
                                    Swal.fire('{{ __('Image Deleted') }}', '{{ __('Image has been successfully deleted') }}', 'success');
                                    $("#listFoldersTable").DataTable().ajax.reload();
                                } else {
                                    Swal.fire('{{ __('Delete Failed') }}', '{{ __('There was an error while deleting this image') }}', 'error');
                                }
                            },
                            error: function (data) {
                                Swal.fire({type: 'error', title: 'Oops...', text: '{{ __("Something went wrong") }}!'})
                            }
                        })
                    }
                })
            });

            $(document).on('click', '#nextButton, #prevButton', function (e) {
                e.preventDefault();
                $('.modal-loader').show();

                // Retrieve the current active image element
                var currentActive = $('#imageCarousel .carousel-item.active');
                var currentActive = $('#imageCarousel .carousel-item.active');

                // Retrieve the index of the current active image element
                var currentIndex = currentActive.index();

                // Retrieve the total number of images in the carousel
                var totalImages = $('#imageCarousel .carousel-item').length;

                // Calculate the index of the next or previous image element based on the clicked button
                var direction = $(this).attr('id') === 'nextButton' ? 'next' : 'prev';
                var nextIndex = direction === 'next' ? (currentIndex + 1) % totalImages : (currentIndex - 1 + totalImages) % totalImages;
                var currentSlide = $('.carousel-item.active').attr('id');

                // Retrieve the next or previous image element
                var nextImage = $('#imageCarousel .carousel-item').eq(nextIndex);

                // Load the next or previous image using AJAX
                // You can replace the URL with the actual endpoint that returns the image data
                $.ajax({
                    url: '/admin/next-image',
                    type: 'GET',
                    data: {
                        image_id: currentSlide,
                        folder_id: {{$id}},
                        direction: direction
                    },
                    success: function (response) {
                        console.log(response);
                        if (response.id) {
                            // Retrieve the current active item and its index
                            var currentActiveItem = $('#imageCarousel .carousel-item.active');
                            var currentIndex = currentActiveItem.index();

                            var nextCarouselItem = $('<div class="carousel-item active" id="' + response.id + '"></div>');
                            var nextImage = $('<img class="d-block w-100 carousel-image" alt="Image">');
                            nextImage.attr('src', response.image);

                            // Get the saved rotation from localStorage, or default to 0
                            var imageSrc = response.image;
                            var savedRotation = parseInt(localStorage.getItem('imageRotation_' + imageSrc)) || 0;

                            // Add the data-rotation attribute and apply the saved rotation using the CSS transform property
                            nextImage.attr('data-rotation', savedRotation);
                            nextImage.css('transform', 'rotate(' + savedRotation + 'deg)');

                            nextCarouselItem.append(nextImage);
                            // Remove the "active" class from the current active item and add it to the new item
                            currentActiveItem.removeClass('active');
                            $('#imageCarousel .carousel-inner').append(nextCarouselItem);

                            // Remove the first carousel item, which is the old current active item
                            $('#imageCarousel .carousel-item:first-child').remove();
                            $('#username').text(response.user.name);
                            $('#duration').html(response.folder.name);

                            // Parse the date string
                            var date = new Date(response.date);

                            // Format the date and time
                            var formattedDate = date.toLocaleDateString() + ' ' + date.toLocaleTimeString();

                            // Display the formatted date and time in the HTML element with the ID 'created_at'
                            $('#created_at').html(formattedDate);


                            $('#feedback option').removeAttr('selected'); // Deselect all options

                            if (response.status == 'Pending') {
                                var audioStatus = '<i class="fas fa-clock text-warning"></i> In Progress';
                                $('#feedback option[value="0"]').attr('selected', 'selected'); // Disable the select element by selecting the disabled option
                            } else if (response.status == 'active') {
                                var audioStatus = '<i class="fas fa-check-circle text-success"></i> Complete';
                                $('#feedback option[value="correct"]').attr('selected', 'selected');
                            } else {
                                var audioStatus = '<i class="fas fa-times-circle text-danger"></i> Failed';
                                $('#feedback option[value="incorrect"]').attr('selected', 'selected');
                            }

                            $('#status').html(audioStatus);

                            $('#remark option').removeAttr('selected'); // Deselect all options

                            if (response.remark_id) {
                                $('#remark option[value="' + response.remark_id + '"]').attr('selected', 'selected');
                            }

                            if (response.folder.quality_assurance) {
                                $('#assigned_user').text(response.folder.quality_assurance.name);
                            }
                            if (response.comment) {
                                $('#visibleTextarea').val(response.comment);
                                $('#hiddenTextarea').val(response.comment);
                            } else {
                                $('#visibleTextarea').val('');
                                $('#hiddenTextarea').val('');
                            }
                            // Get dimensions and geolocation information
                            getImageDetailsButton(response.image, function (dimensions) {
                                console.log(dimensions)
                                $('#dimensions').text(dimensions.width + 'x' + dimensions.height);
                            });
                        }
                        $('.modal-loader').hide();
                    },
                    error: function (xhr) {
                        console.log(xhr);
                        $('.modal-loader').hide();

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

            $(document).on('click', '#saveButton', function (e) {
                e.preventDefault();

                // Retrieve the current active image element ID
                var currentSlide = $('.carousel-item.active').attr('id');

                // Retrieve the comment and feedback values
                var comment = $('#visibleTextarea').val();
                var feedback = $('#feedback').val();
                var remark = $('#remark').val();

                // Send the data to the server using AJAX
                $.ajax({
                    url: '/admin/save-feedback',
                    type: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        image_id: currentSlide,
                        comment: comment,
                        feedback: feedback,
                        remark: remark
                    },
                    success: function (response) {
                        console.log(231);
                        $("#nextButton").click();
                        // handle the response from the server
                        toastr.success('Feedback saved successfully');

                    },
                    error: function (xhr) {
                        console.log(xhr.responseText);
                    }
                });
            });

        });
    </script>
@endsection
