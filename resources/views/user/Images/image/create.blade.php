@extends('layouts.app')

@section('css')
    <!-- Data Table CSS -->
    <link href="{{URL::asset('plugins/awselect/awselect.min.css')}}" rel="stylesheet" />
    <!-- Telephone Input CSS -->
    <link href="{{URL::asset('plugins/telephoneinput/telephoneinput.css')}}" rel="stylesheet" >
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet"/>
@endsection

@section('page-header')
    <!-- EDIT PAGE HEADER -->
    <div class="page-header mt-5-7">
        <div class="page-leftheader">
            <h4 class="page-title mb-0">{{ __('Create Image') }}</h4>
            <ol class="breadcrumb mb-2">
                <li class="breadcrumb-item"><a href="{{ route('user.dashboard') }}"><i class="fa-solid fa-user-shield mr-2 fs-12"></i>{{ __('User') }}</a></li>
                <li class="breadcrumb-item active" aria-current="page"><a href="{{ route('user.image.index') }}"> {{ __('User Images') }}</a></li>

            </ol>
        </div>
    </div>
    <!-- END PAGE HEADER -->
@endsection


@section('content')
    <!-- EDIT USER PROFILE PAGE -->

    <style>
        .select2-selection__rendered {
            background-color: #F5F9FC;
        }
    </style>

    <div class="row">
        <div class="col-xl-12 col-lg-12 col-sm-12">
            <div class="card border-0">
                <div class="card-header">
                    <h3 class="card-title">{{ __('Create Image') }}</h3>
                </div>
                <div class="card-body pb-0">
                    <form method="POST" action="{{ route('user.image.store') }}" id="my-form"
                          enctype="multipart/form-data">
                        @csrf

                        <div class="row">
                            <div class="col-sm-6 col-md-6">
                                <div class="input-box">
                                    <div class="form-group">
                                        <label class="form-label fs-12">{{ __('Image') }} <span class="text-muted">({{ __('Required') }})</span></label>
                                        <input type="file" onclick="fillInput(event)" data-value="Image"
                                               class="form-control @error('image') is-danger @enderror" name="image[]"
                                               multiple value="{{ old('image') }}" required>
                                        @error('image')
                                        <p class="text-danger">{{ $errors->first('image') }}</p>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6 col-md-6">
                                <div class="input-box">
                                    <div class="form-group">
                                        <label class="form-label fs-12">{{ __('Name') }} <span class="text-muted">({{ __('Required') }})</span></label>
                                        <input type="text" id="imageName"
                                               class="form-control @error('name') is-danger @enderror" name="name"
                                               value="{{ old('name') }}" required>
                                        @error('name')
                                        <p class="text-danger">{{ $errors->first('name') }}</p>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6 col-md-6">
                                <div class="input-box">
                                    <label class="form-label fs-12">{{ __('Folder') }} <span class="text-muted">({{ __('Required') }})</span></label>
                                    <select class="form-control" id="folder" name="folder_id"
                                            data-placeholder="{{ __('Select Folder') }} "
                                            style="background: #f5f9fc !important;  color: #1e1e2d; border-radius: 0.5rem; padding: 10px 20px !important;">
                                        @foreach($folders as $folder)
                                            <option value="{{$folder->id}}">{{$folder->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="card-footer border-0 text-right mb-2 pr-0">
                                <a href="{{ route('admin.images.folder') }}"
                                   class="btn btn-cancel mr-2">{{ __('Return') }}</a>
                                <button type="submit" id="submit-button"
                                        class="btn btn-primary">{{ __('Create') }}</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- EDIT USER PROFILE PAGE -->
@endsection

@section('js')
    <!-- Awselect JS -->
    <script src="{{URL::asset('plugins/awselect/awselect.min.js')}}"></script>
    <script src="{{URL::asset('js/awselect.js')}}"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/compressorjs/1.1.1/compressor.min.js"></script>
    <!-- Telephone Input JS -->
    <script src="{{URL::asset('plugins/telephoneinput/telephoneinput.js')}}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>


    <script>
        $(document).ready(function () {
            $('#folder').select2();
        });

        function fillInput(event) {
            // Get the value of the selected image
            let value = event.target.getAttribute("data-value");

            // Set the value of the input field
            document.getElementById("imageName").value = value;
        }

        $('#submit-button').click(function (e) {
            submitForm(e);
        });

        function submitForm(e) {
            e.preventDefault()
            var folder = $('#folder').val();
            var name = $('#imageName').val();

            // check if folder and name are not empty
            if (folder.trim() === '' || name.trim() === '') {
                alert('Please enter folder and name.');
                return;
            }
            $('#preloader').show();
            var form        = $('form')[0];
            var formData    = new FormData(form);
            var images      = $('input[name="image[]"]')[0].files;
            var imagesArray = Array.from(images);
            var batchSize   = 5;
            var batches     = Math.ceil(images.length / batchSize);
            var imageId     = $('#imageName').val();
            var folderId    = $('#folder').val();
            var status      = 'Pending';

            // disable the submit button while uploading
            $('#submit-button').prop('disabled', true);

            // start uploading batches
            var imageName;
            for (var i = 0; i < batches; i++) {
                var start = i * batchSize;
                var end = start + batchSize;
                var batch = imagesArray.slice(start, end);
                var batchFormData = new FormData();

                // add the batch of images to the form data
                for (var j = 0; j < batch.length; j++) {
                    batchFormData.append('image[]', batch[j]);
                }

                // merge the batch form data with the rest of the form data
                for (var pair of formData.entries()) {
                    if (pair[0] !== 'image[]') {
                        batchFormData.append(pair[0], pair[1]);
                        batchFormData.append('imageName', imageId);
                        batchFormData.append('folder', folderId);
                        batchFormData.append('status', status);
                    }
                }


                // send the batch to the server using AJAX
                $.ajax({
                    url: '/account/image-store',
                    method: 'POST',
                    data: batchFormData,
                    processData: false,
                    contentType: false,
                    success: function (response) {
                        // handle the response from the server
                        console.log(response);
                    },
                    error: function (xhr, status, error) {
                        // handle errors during the upload
                        console.error(error);
                    },
                    complete: function () {
                        // re-enable the submit button after the batch is uploaded
                        setTimeout(function() {
                            $('#preloader').hide();
                        }, 60000);
                        $('#submit-button').prop('disabled', false);
                        // redirect to the image index page
                        // window.location.href = '/admin/image';
                    }
                });
            }
        }
    </script>
@endsection
