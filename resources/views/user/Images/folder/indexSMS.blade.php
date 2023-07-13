@extends('layouts.app')

@section('css')
    <!-- Data Table CSS -->
    <link href="{{URL::asset('plugins/datatable/datatables.min.css')}}" rel="stylesheet"/>
    <!-- Sweet Alert CSS -->
    <link href="{{URL::asset('plugins/sweetalert/sweetalert2.min.css')}}" rel="stylesheet"/>
    <!-- Signature Pad CSS -->
    <!-- Signature Pad from jsDelivr CDN -->
    <link rel="stylesheet" type="text/css"
          href="https://cdnjs.cloudflare.com/ajax/libs/signature_pad/2.5.0/signature-pad.min.css">

@endsection

@section('page-header')
    <!-- PAGE HEADER -->
    <div class="page-header mt-5-7">
        <div class="page-leftheader">
            <h4 class="page-title mb-0">{{ __('All SMS Project Folder') }}</h4>
            <ol class="breadcrumb mb-2">
                <li class="breadcrumb-item"><a href="#"><i
                            class="fa-solid fa-user-shield mr-2 fs-12"></i>{{ __('User') }}</a></li>
                <li class="breadcrumb-item active" aria-current="page"><a
                        href="{{ route('user.images.folder') }}"> {{ __('User Images Folder') }}</a></li>
            </ol>
        </div>
        <div>
            <button id="delete-folders-button" class="btn btn-danger" style="display: none;">Delete Selected Folders
            </button>
            <button id="export-folders-button" class="btn btn-primary" style="display: none;">Export</button>
        </div>
    </div>
    <!-- END PAGE HEADER -->
@endsection

@section('content')
    <style>
        .folders-grid {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
        }

        .folder-card {
            background-color: #f8f9fa;
            border: 1px solid #dee2e6;
            border-radius: 5px;
            padding: 20px;
            width: 260px;
            display: flex;
            flex-direction: column;
            align-items: center;
            position: relative;
            display: none;
        }

        .folder-icon {
            font-size: 48px;
            color: #ffc107;
        }

        .folder-info {
            text-align: center;
            margin-top: 10px;
        }

        .folder-icon:hover {
            transform: scale(1.8);
            transition: transform 0.3s;
            cursor: pointer;
        }

        .folder-actions {
            position: absolute;
            top: 10px;
            right: 10px;
            display: flex;
            gap: 5px;
        }

        .folder-checkbox {
            position: absolute;
            top: 27px;
            left: 22px;
            display: flex;
            gap: 5px;
        }

        .folders-list {

        }

        .folder-list-item {
            display: flex;
            align-items: center;
            padding: 0px 20px;
            border: 1px solid #dee2e6;
            border-radius: 5px;
            margin-bottom: 10px;
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

        .folder-link {
            text-decoration: none;
        }

        .folder-icon-list {
            margin-right: 5px;
            transition: transform 0.3s ease;
        }

        .folder-link:hover .folder-icon-list {
            transform: scale(1.2);
        }

        .folder-link.all-images .folder-icon-list {
            color: #ffc107;

        }

        .folder-link.accepted .folder-icon-list {
            color: green;
        }

        .folder-link.rejected .folder-icon-list {
            color: red;
        }

        @media screen and  (min-device-width: 320px) and (max-device-width: 450px) {
            .page-rightheader {
                width: 50%;
            }
        }

        table.dataTable tbody td.select-checkbox:before, table.dataTable tbody th.select-checkbox:before {
            display: none;
            content: " ";
            margin-top: -2px;
            margin-left: -6px;
            border: 1px solid black;
            border-radius: 3px;
        }

    </style>
    <!-- FOLDER GRID -->
    <div class="container">

        <div class="row">
            <div class="col-lg-12 col-md-12 col-xm-12">
                <div class="card border-0">
                    <div class="card-header flex justify-content-between">
                        <h3 class="card-title">{{ __('Folder Management') }}</h3>
                        <div class="page-rightheader">
                            <select id="view-selector" class="form-control" style="width: auto;">
                                <option value="list" selected>List View</option>
                                <option value="grid">Grid View</option>
                            </select>
                            <button id="add-folder" class="btn btn-primary">{{ __('Add Folder') }}</button>
                        </div>
                    </div>

                    <div class="card-body pt-2">
                        <!-- GRID CONTENT -->
                        <div class="folders-grid d-flex flex-wrap folder-div" id="folders-grid">
                            @foreach($data as $folder)
                                <div class="folder-card">
                                    <!-- Add a checkbox with the folder ID -->
                                    <input type="checkbox" class="folder-checkbox" data-folder-id="{{ $folder->id }}"/>

                                    <a href="{{ route('user.images.folder.edit', $folder->id) }}">
                                        <div class="folder-icon">
                                            <i class="fa fa-folder"></i>
                                        </div>
                                    </a>
                                    <div class="folder-info">
                                        <h4 class="folder-name" contenteditable="true"
                                            data-folder-id="{{ $folder->id }}">{{ ucfirst($folder->name) }}</h4>
                                        <p>{{ __('Total Images') }}: {{ $folder->images_count }}</p>
                                    </div>

                                    <div class="folder-actions">
                                        <a class="deleteUserButton" id="{{ $folder->id }}" href="#"><i
                                                class="fa-solid fa-trash delete-action-button"
                                                title="{{ __('Delete User') }}"></i></a>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        <!-- END GRID CONTENT -->

                        <!-- BOX CONTENT -->
                        <div id="folders-list" class="box-content folders-list">

                            <!-- DATATABLE -->
                            <table id='listFoldersTable' class='table listFoldersTable' width='100%'>
                                <thead>
                                <tr>
                                    <th width="2%"></th>
                                    <th width="15%">{{ __('Folder Name') }}</th>
                                    <th width="8%">{{ __('Total Images') }}</th>
                                    <th width="8%">{{ __('Accepted Image') }}</th>
                                    <th width="8%">{{ __('RejectedImage') }}</th>
                                    <th width="7%">{{ __('QC Status') }}</th>
                                    <th width="8%">{{ __('Actions') }}</th>
                                </tr>
                                </thead>
                            </table>
                            <!-- END DATATABLE -->
                        </div>
                        <!-- END BOX CONTENT -->
                    </div>
                </div>
            </div>
        </div>
        <div class="modal fade" id="termAndConditionModal" tabindex="-1" role="dialog"
             aria-labelledby="termAndConditionModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="termAndConditionModalLabel">Consent From</h5>
                        <button type="button" class="close btn" data-bs-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <input type="hidden" id="termAndConditionContentVal" name="termAndConditionContentVal">
                    <input type="hidden" id="termAndConditionContentdata" name="termAndConditionContentVal">

                    <div class="modal-body">
                        <form>
                            <div class="form-group">
                                <div id="termAndConditionContent">

                                </div>
                                <div class="signature-container">
                                    <canvas id="signatureCanvas" style="border: 1px solid #ccc;"></canvas>
                                </div>

                                {{--                            <label for="consentFormContent">Fill Consent Form</label>--}}
                                {{--                            <textarea class="form-control" id="consentFormContent" name="consentFormContent"></textarea>--}}
                            </div>
                            <div class="form-check mt-3">
                                <input class="form-check-input" type="checkbox" id="acceptTermsCheckbox">
                                <label class="form-check-label" for="acceptTermsCheckbox">
                                    I accept the terms and conditions.
                                </label>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-primary" id="saveSignatureBtn">Apply</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>

        <form id="export-folders-form" action="{{ route('user.images.folder.export-multiple') }}" method="POST"
              style="display: none;">
            @csrf
            <input type="text" name="folder_ids" id="export-folders-input">
        </form>

    </div>

    <!-- END FOLDER GRID -->
@endsection

@section('js')
    <!-- Data Tables JS -->
    <script src="{{URL::asset('plugins/datatable/datatables.min.js')}}"></script>
    <script src="{{URL::asset('plugins/sweetalert/sweetalert2.all.min.js')}}"></script>
    <!-- Signature Pad JavaScript -->
    <!-- Signature Pad from cdnjs CDN -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/signature_pad/1.3.4/signature_pad.min.js"></script>
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
                ajax: "{{ route('user.images.folder.list') }}",
                columns: [
                    {
                        data: 'checkbox',
                        name: 'checkbox',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'name',
                        name: 'name',
                        orderable: true,
                        searchable: true
                    },

                    {
                        data: 'images_count',
                        name: 'Total Images',
                        orderable: true,
                        searchable: true
                    },
                    {
                        data: 'accepted_image',
                        name: 'accepted_image',
                        orderable: true,
                        searchable: true
                    },
                    {
                        data: 'rejected_image',
                        name: 'rejected_image',
                        orderable: true,
                        searchable: true
                    },
                    {
                        data: 'status',
                        name: 'status',
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
                columnDefs: [
                    {
                        targets: 0, // The first column (checkbox column)
                        data: null, // Set data to null for the checkbox column
                        defaultContent: '', // Set default content to an empty string
                        orderable: false, // Disable ordering for the checkbox column
                        className: 'select-checkbox' // Add a class to style the checkbox column
                    }
                ],
                initComplete: function () {
                    $(".dataTables_filter input").attr("placeholder", "Enter search term");
                },
            });

            // DELETE CONFIRMATION
            $(document).on('click', '.deleteUserButton', function (e) {

                e.preventDefault();

                Swal.fire({
                    title: '{{ __('Confirm Folder Deletion') }}',
                    text: '{{ __('Warning! This action will delete folder permanently') }}',
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
                            url: 'user-folder-delete',
                            data: formData,
                            processData: false,
                            contentType: false,
                            success: function (data) {
                                if (data == 'success') {
                                    Swal.fire('{{ __('User Deleted') }}', '{{ __('User has been successfully deleted') }}', 'success');
                                    location.reload();
                                    // $("#listFoldersTable").DataTable().ajax.reload();
                                } else {
                                    Swal.fire('{{ __('Delete Failed') }}', '{{ __('There was an error while deleting this user') }}', 'error');
                                }
                            },
                            error: function (data) {
                                Swal.fire({type: 'error', title: 'Oops...', text: '{{ __("Something went wrong") }}!'})
                            }
                        })
                    }
                })
            });

        });

        $('.folder-checkbox').on('click', function () {

            // Check if at least one checkbox is selected
            const hasChecked = $('.folder-checkbox:checked').length > 0;

            // Show or hide the delete button based on whether at least one checkbox is selected
            if (hasChecked) {
                $('#delete-folders-button').show();
                $('#export-folders-button').show();
            } else {
                $('#delete-folders-button').hide();
                $('#export-folders-button').hide();
            }
        });

        $('#listFoldersTable').on('click', '.folder-checkbox', function () {
            // Check if at least one checkbox is selected
            const hasChecked = $('.folder-checkbox:checked').length > 0;

            // Show or hide the delete button based on whether at least one checkbox is selected
            if (hasChecked) {
                $('#delete-folders-button').show();
                $('#export-folders-button').show();
            } else {
                $('#export-folders-button').hide();
                $('#delete-folders-button').hide();
            }
        });
        $('#export-folders-button').on('click', function () {
            // Get the IDs of the selected folders
            let selectedFolderIds = [];
            $('.folder-checkbox:checked').each(function () {
                selectedFolderIds.push($(this).data('folder-id'));
            });

            // If no folders are selected, show an alert and return
            if (selectedFolderIds.length === 0) {
                alert('Please select at least one folder to export.');
                return;
            }

            // Set the selected folder IDs as the value of the hidden input in the form
            $('#export-folders-input').val(JSON.stringify(selectedFolderIds));

            // Submit the form to download the CSV file
            $('#export-folders-form').submit();
        });

        // When the "Delete Selected" button is clicked
        $('#delete-folders-button').on('click', function () {
            console.log(123)
            // Get the IDs of the selected folders
            let selectedFolderIds = [];
            $('.folder-checkbox:checked').each(function () {
                selectedFolderIds.push($(this).data('folder-id'));
            });

            // If no folders are selected, show an alert and return
            if (selectedFolderIds.length === 0) {
                alert('Please select at least one folder to delete.');
                return;
            }

            // Show Swal warning
            Swal.fire({
                title: 'Confirm Folder Deletion',
                text: 'Warning! This action will delete the selected folders permanently',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Delete',
                cancelButtonText: 'Cancel',
                reverseButtons: true,
            }).then((result) => {
                if (result.isConfirmed) {
                    // Send an AJAX request to delete the selected folders
                    $.ajax({
                        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                        url: "{{ route('user.images.folder.delete-multiple') }}",
                        type: 'POST',
                        data: {
                            folder_ids: selectedFolderIds
                        },
                        success: function () {
                            // Show Swal success message and reload the page
                            Swal.fire('Folders Deleted', 'Folders have been successfully deleted', 'success').then(() => {
                                location.reload();
                            });
                        },
                        error: function (error) {
                            // Show Swal error message
                            Swal.fire('Delete Failed', 'There was an error while deleting the folders', 'error');
                            console.error('Error deleting folders:', error);
                        }
                    });
                }
            });
        });

        // CREATE NEW Folder
        $(document).on('click', '#add-folder', function (e) {

            e.preventDefault();

            Swal.fire({
                title: '{{ __('Create New Folder') }}',
                showCancelButton: true,
                confirmButtonText: 'Create',
                reverseButtons: true,
                closeOnCancel: true,
                input: 'text',
            }).then((result) => {
                if (result.value) {
                    var formData = new FormData();
                    formData.append("name", result.value);
                    $.ajax({
                        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                        method: 'post',
                        url: 'folder-store',
                        data: formData,
                        processData: false,
                        contentType: false,
                        success: function (data) {
                            console.log(data, 'data')
                            if (data['status'] == 'success') {
                                Swal.fire('{{ __('Folder Created') }}', '{{ __('New Folder has been successfully created') }}', 'success');
                                location.reload();
                            } else {
                                Swal.fire('{{ __('Folder Creation Error') }}', data['message'], 'error');
                            }
                        },
                        error: function (data) {
                            Swal.fire({type: 'error', title: 'Oops...', text: '{{ __('Something went wrong') }}!'})
                        }
                    })
                } else if (result.dismiss !== Swal.DismissReason.cancel) {
                    Swal.fire('{{ __('No Folder Name Entered') }}', '{{ __('Make sure to provide a new Folder name before creating') }}', 'error')
                }
            })
        });
        $(document).on('click', '.folder-name', function () {
            $(this).focus();
        });

        $(document).on('keyup', '.folder-name', function (e) {
            if (e.keyCode === 13) { // Enter key
                e.preventDefault();
                $(this).blur();
                return false;
            }
        });

        $(document).on('focusout', '.folder-name', function () {
            var folderId = $(this).data('folder-id');
            var newName = $(this).text();
            updateFolderName(folderId, newName);
        });

        function updateFolderName(folderId, newName) {
            $.ajax({
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                method: 'post',
                url: 'user-folder-update', // Change this to the route for updating folder name in your application
                data: {
                    id: folderId,
                    name: newName
                },
                success: function (data) {
                    if (data['status'] == 'success') {
                        Swal.fire('{{ __("Folder Updated") }}', '{{ __("The folder name has been successfully updated") }}', 'success');

                    } else {
                        Swal.fire('{{ __("Folder Update Error") }}', data['message'], 'error');
                    }
                },
                error: function (data) {
                    Swal.fire({type: 'error', title: 'Oops...', text: '{{ __("Something went wrong") }}!'});
                }
            });
        }

        $(document).ready(function () {
            $("#view-selector").on("change", function () {
                var selectedView = $(this).val();
                if (selectedView === "grid") {
                    $(".folder-card").show();
                    $(".folders-list").hide();
                } else {
                    $(".folder-card").hide();
                    $(".folders-list").show();
                }
            });
        })

        $(document).ready(function () {


                // Check the condition here
                var condition = <?= $appliedForm ?>; // Replace with your actual condition
                console.log(condition === 1)
                // Show the modal if the condition is true
                if (condition === 1) {
                    var data = <?= json_encode($project->consent_form) ?>;
                    var participantName = "<?= auth()->user()->name ?>";
                    var currentDate = "<?= now() ?>";
                    // var generatedSignature  = generateSignatureFromName(participantName);

                    // Replace the placeholder with the actual value
                    data = data.replace('participant_name', participantName);
                    data = data.replace('participant_name', participantName);
                    // data = data.replace('signature', generatedSignature);
                    data = data.replace('current_date', currentDate);
                    data = data.replace('start time', currentDate);


                    // Update the content of the term and condition element
                    $('#termAndConditionContent').html(data);
                    // $('#termAndConditionContentdata').val(data);
                    $('#termAndConditionModal').modal('show');

                }




            // Trigger updateTermAndCondition on keyup event
            $('#inputField').on('keyup', function () {

                var inputFieldValue = $(this).val();
                if (inputFieldValue === 'desiredValue') {

                }
            });

            // Signature pad functionality
            var canvas = document.getElementById('signatureCanvas');
            var signaturePad = new SignaturePad(canvas);

            $('#saveSignatureBtn').click(function () {
                // Get the signature data as a base64-encoded PNG image
                var signatureData = signaturePad.toDataURL();

                // Replace the placeholder with the actual signature image
                var data = $('#termAndConditionContent').html().replace('signature', '<img src="' + signatureData + '" alt="Participant\'s Signature">');
                var projectId = "<?= $project->id ?>";


                // Perform further actions with the updated data variable
                // For example, you can send it to the server or display it
                // setTimeout(function(){
                    //code goes here

                // Update the content of the term and condition element with the updated data
                $('#termAndConditionContent').html(data);

                var contentData = $('#termAndConditionContentdata').val(data);

                var formData = new FormData();
                formData.append("consentFormContent", data);
                formData.append("projectId", projectId);
                // formData.append("consentFormContent", consentFormContent);
                $.ajax({
                    headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                    method: 'post',
                    url: "{{ route('user.project.apply.consent.form') }}",
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function (data) {
                        // Close the modal
                        $('#termAndConditionModal').modal('hide');

                    },
                    error: function (data) {
                        Swal.fire({ type: 'error', title: 'Oops...', text: 'Something went wrong!' })
                    }
                });
                // }, 2000); //Time before execution
            });
        });
    </script>
@endsection
