@extends('layouts.app')
@section('css')
    <link href="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.3/css/lightbox.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fancybox/3.5.7/jquery.fancybox.min.css" />

@endsection
@section('page-header')
    <!-- PAGE HEADER -->
    <div class="page-header mt-5-7">
        <div class="page-leftheader">
            <h4 class="page-title mb-0">{{ __('View Project Details') }}</h4>
            <ol class="breadcrumb mb-2">
                <li class="breadcrumb-item"><a href="{{route('user.dashboard')}}"><i class="fa-solid fa-message-exclamation mr-2 fs-12"></i>{{ __('User') }}</a></li>
                <li class="breadcrumb-item" aria-current="page"><a href="{{route('user.project.index')}}"> {{ __('Projects') }}</a></li>
                <li class="breadcrumb-item active" aria-current="page"><a href="{{url('#')}}"> {{ __('View Project') }}</a></li>
            </ol>
        </div>
    </div>
    <!-- END PAGE HEADER -->
@endsection

@section('content')

    <!-- SUPPORT REQUEST -->
    <div class="row">
        <div class="col-lg-12 col-md-12 col-xm-12">
            <div class="card overflow-hidden border-0">
                <div class="card-header">
                    <h3 class="card-title">{{ __('Project') }} </h3>
                </div>
                <div class="card-body pt-5">

                    <div class="row">
                        <div class="col-lg-4 col-md-4 col-12">
                            <h6 class="font-weight-bold mb-1">{{ __('Project Name') }}: </h6>
                            <span class="fs-14">{{ $project->name }}</span>
                        </div>
                    </div>

                    <div class="row pt-5">
                        <div class="col-lg-12 col-md-12 col-12">
                            <h6 class="font-weight-bold mb-1">{{ __('Details') }}: </h6>
                            <span class="fs-14">{!! $project->description   !!}</span>
                        </div>
                    </div>

                    {{--                    <a data-fancybox data-type="iframe" data-src="https://drive.google.com/file/d/1ZTNFbaKSAey8Fvhg8VN2trXr24zrZaJQ/preview" href="javascript:;">--}}
{{--                        Open video--}}
{{--                    </a>--}}
{{--                  <iframe  data-type="iframe" data-src="https://drive.google.com/file/d/1ZTNFbaKSAey8Fvhg8VN2trXr24zrZaJQ/view"></iframe>--}}

                    <div class="border-0 text-right mb-2 mt-8">
                        <a href="{{ route('user.project.index') }}" class="btn btn-primary">{{ __('Return') }}</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- END SUPPORT REQUEST -->
@endsection
@section('js')

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.3/js/lightbox.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/fancybox/3.5.7/jquery.fancybox.min.js"></script>

    <script>
        $('[data-fancybox]').fancybox({
            iframe : {
                css : {
                    width : '600px',
                    height : '350px'
                }
            }
        });

    </script>
@endsection

