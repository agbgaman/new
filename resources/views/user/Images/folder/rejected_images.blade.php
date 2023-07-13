@extends('layouts.app')

@section('css')
    <!-- Data Table CSS -->
    <link href="{{URL::asset('plugins/datatable/datatables.min.css')}}" rel="stylesheet" />
    <!-- Sweet Alert CSS -->
    <link href="{{URL::asset('plugins/sweetalert/sweetalert2.min.css')}}" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.9.3/dropzone.min.css" />

@endsection

@section('page-header')
    <!-- PAGE HEADER -->
    <div class="page-header mt-5-7">
        <div class="page-leftheader">
            <h4 class="page-title mb-0">{{ __('All Images') }}</h4>
            <ol class="breadcrumb mb-2">
                <li class="breadcrumb-item"><a href="{{ route('user.dashboard') }}"><i class="fa-solid fa-user-shield mr-2 fs-12"></i>{{ __('User') }}</a></li>
                <li class="breadcrumb-item" aria-current="page"><a
                        href="{{route('user.images.folder',$project->name)}}   "> {{ $project->name }}</a></li>
                <li class="breadcrumb-item active" aria-current="page"><a href="#"> {{ $folder->name }}</a></li>
                <li class="breadcrumb-item active" aria-current="page"><a href="#"> Rejected Images</a></li>
                {{--\/                <li class="breadcrumb-item active" aria-current="page"><a href="#"> {{ __('Images') }}</a></li>--}}
            </ol>
        </div>
    </div>
    <!-- END PAGE HEADER -->
@endsection

@section('content')
    <style>
        .folder-card {
            background-color: #f8f9fa;
            border: 1px solid #dee2e6;
            border-radius: 5px;
            padding: 20px;
            width: 221px;
            display: flex;
            flex-direction: column;
            align-items: center;
            position: relative;
            margin: 18px;
        }

        .folder-icon {
            font-size: 48px;
            color: #ffc107;
        }

        .folder-info {
            text-align: center;
            margin-top: 10px;
        }

        .folder-list-item .folder-icon {
            margin-right: 10px;
        }
        .folder-list-item .folder-info {
            display: flex;
            justify-content: space-between;
            width: 80%;
        }
        .folder-list-item .folder-info .name {
            flex-grow: 0;
        }
        .name {
            width: 20%;
        }
        .page-rightheader {
            display: flex;
            width: 22%;
            justify-content: space-between;
        }
        @media screen and  (min-device-width: 320px) and (max-device-width: 450px) {
            .page-rightheader {
                width: 50%;
            }
        }
        .card {
            height: 100%;
            display: flex;
            flex-direction: column;
        }

        .card-body {
            flex-grow: 1;
        }

        .card-body img {
            height: 158px; /* Adjust this value as needed */
            width: 100%;
            object-fit: cover;
        }
        .carousel-image {
            width: 50% !important;
            margin: 0 auto;
            display: block;
        }
        .carousel-control-prev,
        .carousel-control-next {
            filter: invert(1);
        }
    </style>
    <!-- USERS LIST DATA TABEL -->
    <div class="row">
        <div class="col-lg-12 col-md-12 col-xm-12">
            <div class="card border-0">
                <!-- ADD DROPDOWN HERE -->


                <div class="card-header flex justify-content-between">
                    <h3 class="card-title">{{ __('Images Management') }}</h3>
                    <div class="form-group">
                        <select class="form-control" id="view-switcher">
                            <option value="list" selected>List View</option>
                            <option value="grid">Grid View</option>
                        </select>
                    </div>
                </div>
                <!-- EXISTING CARD BODY AND DATATABLE CODE -->
                <div class=" card-body pt-2" id="list-view" >
                    <!-- BOX CONTENT -->
                    <div class="box-content">

                        <!-- DATATABLE -->
                        <table id='listFoldersTable' class='table listFoldersTable' width='100%'>
                            <thead>
                            <tr>
                                <th width="15%">{{ __('Image') }}</th>
                                <th width="7%">{{ __('Folder') }}</th>
                                <th width="15%">{{ __('Comment') }}</th>
                                <th width="7%">{{ __('Status') }}</th>
                                <th width="8%">{{ __('Actions') }}</th>
                            </tr>
                            </thead>
                        </table>
                        <!-- END DATATABLE -->

                    </div> <!-- END BOX CONTENT -->
                </div>

                <div class="container" id="grid-view" style="display: none">
                    <div class="row" id="images-grid">
                    <!-- Images will be appended here through AJAX -->
                    </div>
                </div>
            </div>
        </div>
        <!-- Image Modal -->
        <div class="modal fade" id="image-modal" tabindex="-1" aria-labelledby="image-modal-label" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="image-modal-label">Image Details</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body text-center">
                        <div id="carouselExampleControls" class="carousel slide" data-bs-ride="carousel">
                            <div class="carousel-inner" id="imageCarousel">
                                <!-- Image slides will be added here using JavaScript -->
                            </div>
                            <button id="prevButton" class="carousel-control-prev" type="button" data-bs-target="#carouselExampleControls" data-bs-slide="prev">
                                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                <span class="visually-hidden">Previous</span>
                            </button>
                            <button id="nextButton" class="carousel-control-next" type="button" data-bs-target="#carouselExampleControls" data-bs-slide="next">
                                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                <span class="visually-hidden">Next</span>
                            </button>
                        </div>

                        <h4 class="mb-3">Status</h4>
                        <p id="image-status" class="mb-4"></p>

                        <h4 class="mb-3 remarks" style="display: none">Remarks</h4>
                        <p id="image-comment" class="mb-1"></p>
                        <p id="image-remark" class="mt-2"></p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- END USERS LIST DATA TABEL -->
@endsection

@section('js')
    <!-- Data Tables JS -->
    <script src="{{URL::asset('plugins/datatable/datatables.min.js')}}"></script>
    <script src="{{URL::asset('plugins/sweetalert/sweetalert2.all.min.js')}}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/image-compressor.js/1.1.3/image-compressor.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/image-compressor.js@1.1.3/dist/image-compressor.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.9.3/min/dropzone.min.js"></script>


    <script type="text/javascript">
        $(function () {

            "use strict";
            var table = $('#listFoldersTable').DataTable({
                "lengthMenu": [[25, 50, 100, -1], [25, 50, 100, "All"]],
                responsive: true,
                colReorder: true,
                "order": [[ 0, "desc" ]],
                language: {
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
                    url: "{{ route('user.table.image.list') }}",
                    data: {
                        id: {{$folder->id}},
                        rejected: true
                    }
                },
                columns: [
                    {
                        data: 'image',
                        name: 'image',
                        orderable:  true,
                        searchable: true
                    },
                    {
                        data: 'folderName',
                        name: 'folderName',
                        orderable: true,
                        searchable: true
                    },
                    {
                        data: 'comment',
                        name: 'comment',
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
                ],
                initComplete: function () {
                    $(".dataTables_filter input").attr("placeholder", "Enter search term");
                },
            });
            // Add click event listener for image elements with the 'image-click' class
            $('#listFoldersTable tbody').on('click', '.image-click', function() {
                // Get the data for the clicked row
                var rowData = table.row($(this).closest('tr')).data();

                // Call the openImageModal function with the image ID
                openImageModal(rowData.id);
            });
            // DELETE CONFIRMATION
            $(document).on('click', '.deleteUserButton', function(e) {

                e.preventDefault();

                Swal.fire({
                    title: 'Confirm Image Deletion',
                    text: 'Warning! This action will delete the image permanently',
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
                            url: "{{ route('user.image.delete') }}",
                            data: formData,
                            processData: false,
                            contentType: false,
                            success: function (data) {
                                if (data == 'success') {
                                    Swal.fire('{{ __('Image Deleted') }}', '{{ __('Image has been successfully deleted') }}', 'success');
                                    $("#listFoldersTable").DataTable().ajax.reload();
                                } else {
                                    Swal.fire('{{ __('Delete Failed') }}', '{{ __('There was an error while deleting this user') }}', 'error');
                                }
                            },
                            error: function(data) {
                                Swal.fire({ type: 'error', title: 'Oops...', text: '{{ __("Something went wrong") }}!' })
                            }
                        })
                    }
                })
            });

            function deleteImage(imageId) {
                Swal.fire({
                    title: 'Confirm Image Deletion',
                    text: 'Warning! This action will delete the image permanently',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Delete',
                    reverseButtons: true,
                }).then((result) => {
                    if (result.isConfirmed) {
                        var formData = new FormData();
                        formData.append("id", imageId);
                        $.ajax({
                            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                            method: 'post',
                            url: "{{ route('user.image.delete') }}",
                            data: formData,
                            processData: false,
                            contentType: false,
                            success: function (data) {
                                if (data == 'success') {
                                    Swal.fire('Image Deleted', 'Image has been successfully deleted', 'success');
                                    location.reload();
                                } else {
                                    Swal.fire('Delete Failed', 'There was an error while deleting this image', 'error');
                                }
                            },
                            error: function(data) {
                                Swal.fire({ type: 'error', title: 'Oops...', text: 'Something went wrong!' })
                            }
                        })
                    }
                })
            }
            $(document).ready(function () {
                let currentPage = 1;
                const imagesPerPage = 8;
                const folderId = {{$folder->id}};

                function fetchImages(page) {
                    $.ajax({
                        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                        url: "{{ route('user.image.list') }}",
                        type: 'GET',
                        data: {
                            id: folderId,
                            per_page: imagesPerPage,
                            page: page,
                            rejected: true,
                        },
                        success: function (data) {
                            renderImagesGrid(data.images);
                        },
                        error: function (error) {
                            console.error('Error fetching images:', error);
                        },
                    });
                }
                fetchImages(currentPage);
            });

            function renderImagesGrid(images) {
                const imagesGrid = document.getElementById('images-grid');
                imagesGrid.innerHTML = '';

                images.forEach((image) => {
                    const imageCol = document.createElement('div');
                    imageCol.className = 'col-lg-3 col-md-4 col-sm-6 col-xs-12 mb-4';

                    const imageCard = document.createElement('div');
                    imageCard.className = 'card';

                    const imageCardBody = document.createElement('div');
                    imageCardBody.className = 'card-body';

                    const imageElement = document.createElement('img');
                    imageElement.src = image.image;
                    imageElement.width = 100;
                    imageElement.height = 85;
                    imageElement.className = 'img-fluid';
                    // Add a click event listener to the image element
                    imageElement.addEventListener('click', function() {
                        openImageModal(image.id); // Call a function to open the image modal
                    });


                    const imageNameParts = image.image.split('/');
                    const imageLastName = truncateName(imageNameParts[imageNameParts.length - 1], 20);

                    const imageTitle = document.createElement('h5');
                    imageTitle.className = 'mt-3';
                    imageTitle.textContent = imageLastName;

                    // Add delete icon
                    const deleteIcon = document.createElement('i');
                    deleteIcon.className = 'fa fa-trash delete-action-button';
                    deleteIcon.title = 'Delete Image';
                    deleteIcon.className = 'fas fa-trash-alt delete-icon';
                    deleteIcon.style.position = 'absolute';
                    deleteIcon.style.top = '5px';
                    deleteIcon.style.right = '5px';
                    deleteIcon.style.cursor = 'pointer';
                    deleteIcon.style.zIndex = '10';
                    deleteIcon.style.cursor = 'pointer';

                    // Add event listener for the delete icon
                    deleteIcon.addEventListener('click', function () {
                        // Call the function to delete the image
                        deleteImage(image.id);
                    });
                    imageCard.style.position = 'relative'; // Set the position of the image card to relative

                    imageCardBody.appendChild(imageElement);
                    imageCardBody.appendChild(imageTitle);
                    imageCardBody.appendChild(deleteIcon); // Append the delete icon to the card body

                    imageCard.appendChild(imageCardBody);
                    imageCol.appendChild(imageCard);

                    imagesGrid.appendChild(imageCol);
                });
            }

        });
        function createCarouselItem(image, active = false) {
            console.log(image, active);
            const carouselItem = document.createElement("div");
            carouselItem.classList.add("carousel-item");
            if (active) carouselItem.classList.add("active");
            console.log(image.id)
            // Set the ID of the carousel item using the image ID
            carouselItem.id =  image.id;

            const imageElement = document.createElement("img");
            imageElement.src = image.image;
            imageElement.alt = "Image";
            imageElement.className = "d-block w-50 carousel-image";

            carouselItem.appendChild(imageElement);

            return carouselItem;
        }

        function populateCarousel(image) {
            console.log(image)
            const carouselImages = document.getElementById("imageCarousel");
            const carouselItem = createCarouselItem(image, true);
            carouselImages.appendChild(carouselItem);
        }

        function openImageModal(id) {
            $('.remarks').hide();
            $.ajax({
                headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                url: "{{ route('user.image.comment') }}",
                type: 'GET',
                data: {
                    id: id
                },
                success: function (data) {
                    const modal = $('#image-modal');
                    const comment = modal.find('#image-comment');
                    const remark = modal.find('#image-remark');
                    const imageStatus = modal.find('#image-status');
console.log(data.status)
                    if (data.status == "inactive") {
                        imageStatus.html("<span class='text-danger'><i class='fas fa-times-circle text-danger'></i> Failed</span>");
                    } else if (data.status == "In QC") {
                        imageStatus.html("<span class='text-info'><i class='fas fa-tasks mr-1'></i>In QC</span>"); // added info icon and color
                    } else {
                        imageStatus.html("<span class='text-success'><i class='fas fa-check-circle mr-1'></i>Done</span>");
                    }
                    if (data.comment) {
                        $('.remarks').show();
                        comment.text(data.comment);
                    }
                    if (data.remark) {
                        $('.remarks').show();
                        remark.text(data.remark.remark);
                    }
                    populateCarousel(data); // Pass the fetched images to populate the carousel

                    // Show the modal
                    modal.modal('show');
                },
                error: function (error) {
                    console.error('Error fetching image details:', error);
                }
            });
        }

        const userImageNextUrl = "{{ route('user.image.nextImage') }}";
        $(document).on('click', '#nextButton, #prevButton', function (e) {
            e.preventDefault();

            // Calculate the direction based on the clicked button
            var direction = $(this).attr('id') === 'nextButton' ? 'next' : 'prev';

            const activeCarouselItem = document.querySelector('.carousel-item.active');
            const currentImageId = activeCarouselItem.id;

            // Send the AJAX request to fetch the next or previous image
            $.ajax({
                url: userImageNextUrl,
                type: 'GET',
                data: {
                    image_id: currentImageId,
                    folder_id: {{$folder->id}},
                    direction: direction,
                    status: 'inactive'
                },
                success: function (response) {
                    console.log(response);
                    // if (response.length > 0) {
                    //     // Loop through the response and update the carousel with each image
                    //     response.forEach(function (imageData) {
                    // Update the carousel with the new image

                    updateCarousel(response);

                    // Update other elements with the new image data
                    updateImageInfo(response);
                    //     });
                    // }
                },
                error: function (xhr) {
                    console.log(xhr);
                    $('.modal-loader').hide();
                }
            });
        });
        function updateCarousel(imageData) {
            console.log("Image Data 12:", imageData);
            console.log("Image URL:", imageData.image);

            // Find the current active image
            var currentActive = document.querySelector('#carouselExampleControls .carousel-item.active');
            console.log("Current Active:", currentActive);

            // Remove the current active image
            if (currentActive) {
                currentActive.remove();
            }

            // Create a new carousel item with the new image
            var newCarouselItem = document.createElement('div');
            newCarouselItem.className = 'carousel-item active';
            newCarouselItem.id = imageData.id;

            var newImage = document.createElement('img');
            newImage.className = 'd-block w-50 carousel-image';
            newImage.alt = 'Image';
            newImage.src = imageData.image;

            newCarouselItem.appendChild(newImage);

            // Add the new carousel item to the carousel
            var carouselInner = document.querySelector('#carouselExampleControls .carousel-inner');
            carouselInner.appendChild(newCarouselItem);

            // Debugging: Check if the new carousel item is appended correctly
            console.log("Updated Carousel HTML:", carouselInner.innerHTML);
        }

        function truncateName(name, maxLength) {
            console.log(name, maxLength)
            if (name.length <= maxLength) {
                return name;
            }

            return name.slice(0, maxLength - 3) + '...';
        }


        document.getElementById('view-switcher').addEventListener('change', function (e) {
            const listView = document.getElementById('list-view');
            const gridView = document.getElementById('grid-view');
            if (e.target.value === 'list') {
                listView.style.display = 'block';
                gridView.style.display = 'none';
            } else {
                listView.style.display = 'none';
                gridView.style.display = 'block';
            }
        });

    </script>
@endsection
