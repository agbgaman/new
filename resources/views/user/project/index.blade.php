@extends('layouts.app')

@section('css')
    <!-- Data Table CSS -->
    <link href="{{ URL::asset('plugins/datatable/datatables.min.css') }}" rel="stylesheet"/>
    <!-- Awselect CSS -->
    <link href="{{ URL::asset('plugins/awselect/awselect.min.css') }}" rel="stylesheet"/>
    <!-- Sweet Alert CSS -->
    <link href="{{ URL::asset('plugins/sweetalert/sweetalert2.min.css') }}" rel="stylesheet"/>
    <!-- Signature Pad CSS -->

    <link rel="stylesheet" type="text/css"
          href="https://cdnjs.cloudflare.com/ajax/libs/signature_pad/2.5.0/signature-pad.min.css">

    <style>
        .scrollable-column {
            max-height: 60px;
            overflow-y: auto;
            display: inline-block;
            width: 100%;
        }
    </style>
@endsection

@section('page-header')
    <style>
        .short-description {
            max-height: 1.2em; /* Adjust this value according to the line height of your table rows */
            /*white-space: nowrap;*/
            overflow: hidden;
            text-overflow: ellipsis;
        }
    </style>

    <!-- PAGE HEADER -->
    <div class="page-header mt-5-7">
        <div class="page-leftheader">
            <h4 class="page-title mb-0">{{ __('All project') }}</h4>
            <ol class="breadcrumb mb-2">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}"><i
                            class="fa-solid fa-user-shield mr-2 fs-12"></i>{{ __('User') }}</a></li>
                <li class="breadcrumb-item active" aria-current="page"><a
                        href="{{ route('admin.project-instruction') }}">
                        {{ __('Projects') }}</a></li>
            </ol>
        </div>
    </div>
    <!-- END PAGE HEADER -->
@endsection

@section('content')
    <!-- PROJECT INSTRACTION LIST DATA TABEL -->
    <div class="row">
        <div class="col-lg-12 col-md-12 col-xm-12">
            <div class="card border-0">
                <div class="card-header flex justify-content-between">
                    <h3 class="card-title">{{ __('Projects') }}</h3>
                </div>
                <div class="card-body pt-2">
                    <!-- BOX CONTENT -->
                    <div class="box-content">
                        <!-- DATATABLE -->
                        <table id='listProjectInstructionTable' class='table' width='100%'>
                            <thead>
                            <tr>
                                <th width="8%">{{ __('Name') }}</th>
                                <th width="8%">{{ __('Price') }}</th>
                                <th width="8%">{{ __('Description') }}</th>
                                <th width="8%">{{ __('Created At') }}</th>
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
    <!-- Modal -->
    <div class="modal fade" id="termAndConditionModal" tabindex="-1" role="dialog"
         aria-labelledby="termAndConditionModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="termAndConditionModalLabel">Term and Condition</h5>
                    <button type="button" class="close btn" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <input type="hidden" id="termAndConditionContentVal" name="termAndConditionContentVal">

                <div class="modal-body">
                    <form>
                        <div class="form-group">
                            <div id="termAndConditionContent">

                            </div>
                        </div>
                        <label for="signatureCanvas">Signature here</label>
                        <div class="signature-container">
                            <canvas id="signatureCanvas" style="border: 1px solid #ccc;"></canvas>
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
                    <button type="button" class="btn btn-primary" id="appliedButton">Apply</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>



    <!-- END PROJECT INSTRACTION LIST DATA TABEL -->
@endsection

@section('js')
    <!-- Data Tables JS -->
    <script src="{{ URL::asset('plugins/datatable/datatables.min.js') }}"></script>
    <!-- Awselect JS -->
    <script src="{{ URL::asset('plugins/awselect/awselect.min.js') }}"></script>
    <script src="{{ URL::asset('plugins/sweetalert/sweetalert2.all.min.js') }}"></script>
    <script src="{{ URL::asset('js/awselect.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/signature_pad/1.3.4/signature_pad.min.js"></script>


    <script type="text/javascript">
        $(function () {

            "use strict";

            var table = $('#listProjectInstructionTable').DataTable({
                "lengthMenu": [
                    [25, 50, 100, -1],
                    [25, 50, 100, "All"]
                ],
                "scrollY": "300px", // Set the fixed height, e.g., 300px
                "scrollCollapse": true,
                // "paging": false // Disable pagination
                responsive: true,
                colReorder: true,
                "order": [],
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
                ajax: "{{ route('user.project.index') }}",
                columns: [
                    {
                        data: 'name',
                        name: 'name',
                        orderable: true,
                        searchable: true
                    },
                    {
                        data: 'price',
                        name: 'price',
                        orderable: true,
                        searchable: true
                    },
                    {
                        data: 'description',
                        name: 'description',
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
                columnDefs: [
                    {responsivePriority: 1, targets: 0},
                    {responsivePriority: 2, targets: 1},
                    {responsivePriority: 3, targets: 4},
                    {responsivePriority: 4, targets: 2},
                    {responsivePriority: 5, targets: 3},
                ],
            });

        });

        $('#listProjectInstructionTable').on('click', '.dropdown', function (e) {
            e.stopPropagation();
            console.log('Dropdown clicked');
        });

        $('#dropdownMenuButton').on('show.bs.dropdown', function () {
            $(this).find('.fas').removeClass('fa-angle-down').addClass('fa-angle-up');
        }).on('hide.bs.dropdown', function () {
            $(this).find('.fas').removeClass('fa-angle-up').addClass('fa-angle-down');
        });
        $(document).ready(function () {

            $('#listProjectInstructionTable').on('click', '.applyButton', function (event) {
                console.log('Modal opened');
                $('#termAndConditionContentVal').val('');
                // $('#termAndConditionContentdata').val('');

                var button = $(this);
                var projectId = button.data('project-id');
                console.log('Project ID: ' + projectId);
                $.ajax({
                    method: 'GET',
                    url: '{{ route("user.project.term.condition", ":id") }}'.replace(':id', projectId),
                    success: function (data) {
                        console.log(data)
                        $('#termAndConditionContentVal').val(projectId);
                        var participantName = "<?= $participant_name ?>";
                        var currentDate = "<?= $current_date ?>";
                        var address = "<?= auth()->user()->address ?>";
                        // var generatedSignature = generateSignatureFromName(participantName);

                        // Replace the placeholder with the actual value
                        data = data.replace('participant_name', participantName);
                        data = data.replace('Vendor Name', participantName);
                        data = data.replace('Address', address);
                        // data = data.replace('signature', generatedSignature);
                        data = data.replace('current_date', currentDate);
                        data = data.replace('start time', currentDate);


                        // Update the content of the term and condition element
                        $('#termAndConditionContent').html(data);
                        // $('#termAndConditionContentdata').val(data);


                        console.log('data: ' + data);
                        // // Initialize CKEditor instance for the consent form content
                        // CKEDITOR.replace('consentFormContent', {
                        //     toolbar: [
                        //         { name: 'basicstyles', items: ['Bold', 'Italic'] },
                        //         { name: 'paragraph', items: ['Heading'] }
                        //     ],
                        //     removeButtons: 'Underline,Strike,Subscript,Superscript,Outdent,Indent,Blockquote,CreateDiv,JustifyLeft,JustifyCenter,JustifyRight,JustifyBlock,BidiLtr,BidiRtl,Link,Unlink,Anchor,Image,Table,HorizontalRule,SpecialChar,Maximize,Source,About',
                        //     autoGrow_maxHeight: '100%',
                        //     on: {
                        //         instanceReady: function(evt) {
                        //             // Get the CKEditor instance
                        //             var editor = evt.editor;
                        //
                        //             // Set the data in the CKEditor instance
                        //             editor.setData(data);
                        //         }
                        //     }
                        // });


                        // Show the modal
                        $('#termAndConditionModal').modal('show');
                    },
                    error: function () {
                        $('#termAndConditionContent').html('<p>Error retrieving term and condition.</p>');
                    }
                });
            });
        });

        // Signature pad functionality
        var canvas = document.getElementById('signatureCanvas');
        var signaturePad = new SignaturePad(canvas);

        $('#appliedButton').click(function () {
            console.log('Apply button clicked');
            if ($('#acceptTermsCheckbox').is(':checked')) {
                var signatureData = signaturePad.toDataURL();
                console.log('Signature data: ' + signatureData);
                var data = $('#termAndConditionContent').html().replace('signature', '<img src="' + signatureData + '" alt="Participant\'s Signature">');
                console.log('data: ' + data);
                var projectId = $('#termAndConditionContentVal').val();
                // var consentFormContent = $('#termAndConditionContentdata').val();
                $('#termAndConditionContent').html(data);

                var formData = new FormData();
                formData.append("projectId", projectId);
                formData.append("contract_form", data);

                $.ajax({
                    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                    method: 'post',
                    url: "{{ route('user.project.apply') }}",
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function (data) {

                    },
                    error: function (data) {
                        Swal.fire({type: 'error', title: 'Oops...', text: 'Something went wrong!'})
                    }
                });
                window.location.reload();

            } else {
                alert('Please accept the terms and conditions.');
            }
        });


    </script>
@endsection
