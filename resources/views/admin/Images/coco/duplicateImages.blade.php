@extends('layouts.app')

@section('css')
    <!-- Data Table CSS -->
    <link href="{{URL::asset('plugins/datatable/datatables.min.css')}}" rel="stylesheet"/>
    <!-- Sweet Alert CSS -->
    <link href="{{URL::asset('plugins/sweetalert/sweetalert2.min.css')}}" rel="stylesheet"/>
@endsection

@section('page-header')
    <!-- PAGE HEADER -->
    <div class="page-header mt-5-7">
        <div class="page-leftheader">
            <h4 class="page-title mb-0">{{ __('All Images') }}</h4>
            <ol class="breadcrumb mb-2">
                <li class="breadcrumb-item"><a href="{{ route('user.dashboard') }}"><i
                            class="fa-solid fa-user-shield mr-2 fs-12"></i>{{ __('User') }}</a></li>
                <li class="breadcrumb-item active" aria-current="page"><a href="#"> {{ __('Duplicate Images') }}</a>
                </li>
            </ol>
        </div>

    </div>
    <!-- END PAGE HEADER -->
@endsection

@section('content')
    <style>
        .row {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-around;
        }

        .col {
            flex: 0 0 calc(25.33% - 1em);
        }

        .card {
            border: 1px solid #ccc;
            margin-bottom: 1em;
            border-radius: 5px;
            box-shadow: 0px 0px 15px rgba(0, 0, 0, 0.1);
        }

        .card-img {
            width: 100%;
            height: 300px; /* adjust this value as needed */
            object-fit: cover; /* this will ensure the image maintains its aspect ratio */
            border-top-left-radius: 5px;
            border-top-right-radius: 5px;
        }

        .card-details p {
            font-size: 0.9em;
            color: #555;
        }

        .card-details i {
            color: #007BFF;
        }
    </style>

    <!-- USERS LIST DATA TABEL -->
    <div class="row">
        <div class="col-lg-12 col-md-12 col-xm-12">
            <div class="card border-0">
                <div class="card-header flex justify-content-between">
                    <h3 class="card-title">{{ __('Similar & Duplicate Images') }}</h3>
                </div>
                <div class="card-body pt-2">
                    <!-- BOX CONTENT -->
                    <div class="box-content">

                        <div class="row">
                            @foreach($images as $image)
                                <div class="col">
                                    <div class="card">
                                        <img class="card-img" src="{{$image->image}}" alt="Image 1">
                                        <div class="container card-details">
                                            <p><i class="fas fa-user"></i> : @if($image->user)
                                                    {{$image->user->name}}
                                                @endif</p>
                                            <p><i class="fas fa-envelope"></i> : @if($image->user)
                                                    {{$image->user->email}}
                                                @endif</p>
                                            <p>
                                                @if($image->folder && $image->folder->project)
                                                    <i class="fas fa-rocket"></i>
                                                    {{$image->folder->project->name}}
                                                @else
                                                    <i class="fas fa-folder"></i>
                                                @endif
                                            </p>
                                            <p><i class="fas fa-folder"></i> : @if($image->user)
                                                {{$image->folder->name}}
                                                @endif</p>
                                            <p><i class="fas fa-calendar-alt"></i>
                                                : {{$image->created_at->format('D M Y, h:i A')}}</p>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        <!-- END BOX CONTENT -->
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
            <script type="text/javascript">
                $(function () {

                    "use strict";


                });
            </script>
        @endsection

