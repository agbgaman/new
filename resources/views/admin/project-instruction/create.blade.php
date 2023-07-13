@extends('layouts.app')

@section('css')
    <!-- Awselect CSS -->
    <link href="{{ URL::asset('plugins/awselect/awselect.min.css') }}" rel="stylesheet"/>
    <!-- Sweet Alert CSS -->
    <link href="{{ URL::asset('plugins/sweetalert/sweetalert2.min.css') }}" rel="stylesheet"/>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css">
    <link rel="stylesheet"
          href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.13/css/bootstrap-multiselect.css">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/css/select2.min.css" rel="stylesheet"/>
    <!-- Lightbox CSS -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.3/css/lightbox.min.css" rel="stylesheet"/>

@endsection

@section('page-header')
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- PAGE HEADER -->
    <div class="page-header mt-5-7">
        <div class="page-leftheader">
            <h4 class="page-title mb-0">{{ __('Create project instruction') }}</h4>
            <ol class="breadcrumb mb-2">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}"><i
                            class="fa-solid fa-user-shield mr-2 fs-12"></i>{{ __('Admin') }}</a></li>
                <li class="breadcrumb-item"><a
                        href="{{ route('admin.project-instruction') }}">{{ __('Project instruction') }}</a></li>
                <li class="breadcrumb-item active" aria-current="page"><a
                        href="{{ route('admin.project-instruction.create-view') }}">
                        {{ __('create Project instruction') }}</a></li>
            </ol>
        </div>
        <div class="page-rightheader">
            <button class="btn btn-special create-project mr-2" type="button" id="add-folder"
                    data-tippy-content="{{ __('Create New project instruction') }}"><i
                    class="fa-solid fa-rectangle-history-circle-plus"></i></button>
        </div>
    </div>
    <!-- END PAGE HEADER -->
@endsection

@section('content')
    <style>
        .select2-container--default .select2-selection--multiple {
            max-height: 70px; /* Set a fixed maximum height */
            overflow-y: auto !important; /* Enable scrolling if the box exceeds the maximum height */
        }
    </style>
    <div class="row">
        <!-- create project instruction -->
        <div class="col-lg-12 col-md-12 col-sm-12">
            <div class="card border-0">
                <div class="card-header">
                    <h3 class="card-title">{{ __('Create') }}</h3>
                </div>
                <div class="card-body pb-0">
                    <form method="POST" id="newform">
                        @csrf
                        <div class="row">
                            <div class="col-md-6 col-sm-12">
                                <div class="form-group">
                                    <div class="form-group">
                                        <label class="form-label fs-12">{{ __('Project Name') }} <span
                                                class="text-muted">({{ __('Required') }})</span></label>
                                        <input type="text" id="name"
                                               class="form-control @error('name') is-danger @enderror" name="name"
                                               value="{{ old('name') }}" required>
                                        @error('name')
                                        <p class="text-danger">{{ $errors->first('name') }}</p>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 col-sm-12">
                                <div class="form-group">
                                    <div class="form-group">
                                        <label class="form-label fs-12">{{ __('Price') }} <span
                                                class="text-muted">({{ __('Required') }})</span></label>
                                        <input type="number" id="price"
                                               class="form-control @error('name') is-danger @enderror" name="price"
                                               value="{{ old('price') }}" required>
                                        @error('name')
                                        <p class="text-danger">{{ $errors->first('name') }}</p>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6 col-md-6">
                                <div class="input-box">
                                    <label class="form-label fs-12">{{ __('Status') }} <span class="text-muted">({{ __('Required') }})</span></label>
                                    <select id="user-country" name="status"
                                            data-placeholder="{{ __('Select status') }} ">
                                        <option value=active>{{ __('Active') }}</option>
                                        <option value=inactive>{{ __('In Active') }}</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-6 col-md-6">
                                <div class="input-box">
                                    <label class="form-label fs-12">{{ __('Type') }} <span class="text-muted">({{ __('Required') }})</span></label>
                                    <select id="languages" name="type"
                                            data-placeholder="{{ __('Select Type') }} ">
                                        <option value=text_to_speech>{{ __('Text to Speech') }}</option>
                                        <option value=text_to_text>{{ __('Text to Text') }}</option>
                                        <option value=image>{{ __('Image') }}</option>
                                        <option value=image_to_speech>{{ __('Image to Speech') }}</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-sm-6 col-md-6">
                                <div class="form-group">
                                    <label class="form-label fs-12">{{ __('Country') }}</label>
                                    <select id="country" name="country[]" multiple="multiple">
                                        <option value="all">{{ __('Select All') }}</option>
                                        @foreach(config('countries') as $value)
                                            <option value="{{ $value }}">{{ $value }}</option>
                                        @endforeach
                                    </select>
                                    @error('country')
                                    <p class="text-danger">{{ $errors->first('country') }}</p>
                                    @enderror
                                </div>

                            </div>

                        </div>
                        <div class="row">
                            <div class="col-md-12 col-sm-12">
                                <div class="row">
                                    <div class="col-lg-12 col-sm-12 col-md-12">
                                        <div class="input-box">
                                            <div class="form-group">
                                                <label class="form-label fs-12">{{ __('Short Description') }} <span
                                                        class="text-muted">({{ __('Required') }}) 20 words</span></label>
                                                <textarea class="form-control" id="short_description"
                                                          name="short_description"></textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12 col-sm-12">
                                <div class="row">
                                    <div class="col-lg-12 col-sm-12 col-md-12">
                                        <div class="input-box">
                                            <div class="form-group">
                                                <label class="form-label fs-12">{{ __('Description') }} <span
                                                        class="text-muted">({{ __('Required') }})</span></label>
                                                <textarea class="form-control ckeditor" id="description"
                                                          name="description"></textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-12 col-sm-12">
                                <div class="row">
                                    <div class="col-lg-12 col-sm-12 col-md-12">
                                        <div class="input-box">
                                            <div class="form-group">
                                                <label class="form-label fs-12">{{ __('Term and Condition') }} <span
                                                        class="text-muted">({{ __('Required') }})</span></label>
                                                <textarea class="form-control ckeditor" id="term_and_condition"
                                                          name="description2"></textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12 col-sm-12">
                                <div class="row">
                                    <div class="col-lg-12 col-sm-12 col-md-12">
                                        <div class="input-box">
                                            <div class="form-group">
                                                <label class="form-label fs-12">{{ __('Consent Form') }} <span
                                                        class="text-muted">({{ __('Required') }})</span></label>
                                                <textarea class="form-control ckeditor" id="consent_form"
                                                          name="description3"></textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-11 col-sm-11">

                                <label for="remarks">Rejection Reason</label>
                                <input type="text" id="remarks" class="form-control" name="remarks[]"
                                       value="{{ old('remarks') }}" required>

                            </div>
                            <div class="col-md-1 col-sm-1">
                                <button id="addMore" class="btn btn-primary mb-2 mt-6" type="button">Add More</button>
                            </div>

                        </div>
                        <div id="inputContainer"></div>
                        <br>
                        {{--                        <div class="row">--}}


                        {{--                            <div class="col-md-11 col-sm-11">--}}
                        {{--                                <label for="rejectionReason">Rejection Reason</label>--}}
                        {{--                                <input type="text" id="rejectionReason" class="form-control"--}}
                        {{--                                       name="rejectionReason[]"--}}
                        {{--                                       value="{{ old('rejectionReason') }}" required>--}}
                        {{--                            </div>--}}
                        {{--                            <div class="col-md-1 col-sm-1">--}}
                        {{--                                <button id="addMoreRejectionReason" class="btn btn-primary mb-2 mt-6" type="button">Add--}}
                        {{--                                    More--}}
                        {{--                                </button>--}}
                        {{--                            </div>--}}


                        {{--                        </div>--}}
                        {{--                        <div id="rejectionReasonContainer"></div>--}}

                        <div class="card-footer border-0 text-right mb-2 pr-0">
                            <a href="{{ route('admin.project-instruction') }}"
                               class="btn btn-cancel mr-2">{{ __('Return') }}</a>
                            <button id="add-project-instructions" type="submit"
                                    class="btn btn-primary">{{ __('Submit') }}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <!-- Awselect JS -->
    <script src="{{ URL::asset('plugins/awselect/awselect.min.js') }}"></script>
    <script src="{{ URL::asset('plugins/sweetalert/sweetalert2.all.min.js') }}"></script>

    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.bundle.min.js"></script>
    <script
        src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.13/js/bootstrap-multiselect.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/js/select2.min.js"></script>
    <script src="{{ URL::asset('js/awselect.js') }}"></script>
    <!-- Ckeditor 5 Cdn -->
    {{--    <script src="https://cdn.ckeditor.com/ckeditor5/29.2.0/classic/ckeditor.js"></script>--}}

    <script src="https://cdn.ckeditor.com/4.17.2/full-all/ckeditor.js"></script>

    <script src="{{ URL::asset('cskeditor/ckfinder/ckfinder.js') }}"></script>
    <!-- Lightbox JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.3/js/lightbox.min.js"></script>

    <script type="text/javascript">
        let csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        let editors = {};

        $(document).ready(function() {
            let csrfToken = $('meta[name="csrf-token"]').attr('content');

            $('.ckeditor').each(function () {
                let id = $(this).attr('id');
                let editor = CKEDITOR.replace(id, {
                    filebrowserUploadUrl: '/admin/upload',
                });

                CKFinder.config( { connectorPath: '/admin/upload' } );
                CKFinder.setupCKEditor(editor);

                // Store the editor instance in the "editors" object.
                editors[id] = editor;
            });
        });
        $(function () {

            "use strict";

            // CREATE PROJECT INSTRUCTIONS
            $(document).on('click', '#add-project-instructions', function (e) {
                e.preventDefault();
                var project = $('#name').val();
                var type = $('#languages').val();
                var status = $('#user-country').val();
                var short_description = $('#short_description').val();
                var country = $('#country').val();
                var price = $('#price').val();
                var description         = wrapElements(CKEDITOR.instances['description'].getData());
                var term_and_condition  = CKEDITOR.instances['term_and_condition'].getData();
                var consent_form        = CKEDITOR.instances['consent_form'].getData();

                var formData = new FormData();
                formData.append("name", project);
                formData.append("description", description);
                formData.append("status", status);
                formData.append("type", type);
                formData.append("term_and_condition", term_and_condition);
                formData.append("consent_form", consent_form);
                formData.append("short_description", short_description);
                formData.append("country", country);
                formData.append("price", price);

                // Append remarks to formData
                $('input[name="remarks[]"]').each(function (index) {
                    formData.append("remarks[" + index + "]", $(this).val());
                });
                // Append rejection to formData
                // $('input[name="rejectionReason[]"]').each(function (index) {
                //     formData.append("rejectionReason[" + index + "]", $(this).val());
                // });

                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    method: 'post',
                    url: '/admin/instruction-store',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function (data) {
                        if (data['status'] == 'success') {
                            Swal.fire('{{ __('Project Instruction Created') }}',
                                '{{ __('Project Instructions has been successfully created') }}',
                                'success')
                                .then((success) => {
                                    if (success) {
                                        window.location.href = '/admin/project-instruction';
                                    }
                                });
                        } else {
                            Swal.fire('{{ __('Project Instructions Creation Error') }}', data[
                                'message'], 'error');
                        }
                    },
                    error: function (data) {
                        Swal.fire({
                            type: 'error',
                            title: 'Oops...',
                            text: '{{ __('Something went wrong') }}!'
                        })
                    }
                })
            });
        });

        function wrapElements(data) {
            let $html = $('<div>').html(data);
            $html.find('img').each(function () {
                let src = $(this).attr('src');
                $(this).wrap('<a href="' + src + '" data-lightbox="image"></a>');
            });
            $html.find('iframe').each(function() {
                let $this = $(this);
                let src = $this.attr('src');
                $this.unwrap(); // remove any parent .video-container div
                $this.wrap(`<a data-fancybox data-type="iframe" data-src="${src}" href="javascript:;">${$this.prop('outerHTML')}</a>`);
            });

            return $html.html();
        }


        $(document).ready(function () {
            $("#addMore").click(function () {
                let newRow = `
            <div class="row input-row mt-2">
              <div class="col-md-11 col-sm-10">
                <input type="text" class="form-control" name="remarks[]" value="" required>
              </div>
              <div class="col-md-1 col-sm-2">
                <button type="button" class="btn btn-danger deleteBtn"><i class="fa-solid fa fa-trash" title="Delete"></i></button>
              </div>
            </div>`;

                $("#inputContainer").append(newRow);
            });

            $(document).on('click', '.deleteBtn', function () {
                $(this).closest('.input-row').remove();
            });

            $("#addMoreRejectionReason").click(function () {
                let newRow = `
            <div class="row input-row mt-2">
              <div class="col-md-11 col-sm-10">
                <input type="text" class="form-control" name="rejectionReason[]" value="" required>
              </div>
              <div class="col-md-1 col-sm-2">
                <button type="button" class="btn btn-danger deleteRejectionReason"><i class="fa-solid fa fa-trash" title="Delete"></i></button>
              </div>
            </div>`;

                $("#rejectionReasonContainer").append(newRow);
            });

            $(document).on('click', '.deleteRejectionReason', function () {
                $(this).closest('.input-row').remove();
            });

            $("#country").on('change', function () {
                if ($(this).val() == 'all') {
                    $(this).find('option').prop('selected', true);
                }
            });

            $("#country").select2({
                closeOnSelect: false
            });
            $("#country").on("select2:select", function (e) {
                var selected_element = $(e.currentTarget);
                var select_val = selected_element.val();
                $("#country").val(select_val).trigger("change.select2");
            });
            $("#country").on("select2:opening", function (e) {
                $("input[type=checkbox]").on("click", function (e) {
                    e.stopPropagation();
                });
            });
        });
    </script>
@endsection
