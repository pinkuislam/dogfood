<?php $setting = DB::table('settings')->where('deleted_at', '=', null)->first(); ?>

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width,initial-scale=1">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <link rel="icon" href="{{ asset('images/logo.png') }}">

        <!-- CSRF Token -->
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>@if(isset($title) && $title){{ $title }} | @endif Inventory Management</title>
        <link href="https://fonts.googleapis.com/css?family=Nunito:300,400,400i,600,700,800,900" rel="stylesheet">
        @yield('before-css')
        {{-- theme css --}}

        {{-- App Css for custom style --}}

        <link  rel="stylesheet" href="{{ asset('assets/vendor/iconsmind/iconsmind.css') }}">
        <link  rel="stylesheet" href="{{ asset('assets/vendor/fontawesome-free-5.10.1-web/css/all.min.css') }}">
        <link rel="stylesheet" href="{{ asset('assets/css/themes/light.min.css') }}">

        <link rel="stylesheet" href="{{asset('assets/vendor/toastr.css')}}">
        <link rel="stylesheet" href="{{asset('assets/vendor/vue-select.css')}}">
        <link rel="stylesheet" href="{{asset('assets/vendor/sweetalert2.min.css')}}">
        <link rel="stylesheet" href="{{asset('assets/vendor/nprogress.min.css')}}"> 
        <link rel="stylesheet" href="{{asset('css/app.css')}}"> 
        
        {{-- axios js --}}
        <script src="{{ asset('assets/vendor/axios.min.js') }}"></script>
        {{-- vue select js --}}
        <script src="{{ asset('assets/vendor/vue-select.js') }}"></script>
        <script defer src="{{ asset('assets/js/compact-layout.js') }}"></script>

        {{-- Alpine Js --}}
        <script defer src="{{ asset('assets/vendor/alpine-collapse.js') }}"></script>
        <script defer src="{{ asset('assets/vendor//alpine.js') }}"></script>
        <script src="{{ asset('assets/vendor/alpine-data.js') }}"></script>
        <script src="{{ asset('assets/vendor/alpine-store.js') }}"></script>
        
        {{-- page specific css --}}
        @yield('page-css')
    </head>

    <body class="text-left">
        <!-- Pre Loader Strat  -->
        <div class='loadscreen' id="preloader">
            <div class="loader spinner-bubble spinner-bubble-primary"></div>
        </div>
        <!-- Pre Loader end  -->

        <!-- ============ Vetical Sidebar Layout start ============= -->
        @include('layouts.sidebar.master')
        <!-- ============ Vetical Sidebar Layout End ============= -->

        {{-- vue js --}}
        <script src="{{ asset('assets/vendor/vue.js') }}"></script>

        <script src="{{asset('assets/vendor/vee-validate.min.js')}}"></script>
        <script src="{{asset('assets/vendor/vee-validate-rules.min.js')}}"></script>
        <script src="{{asset('assets/vendor/moment.min.js')}}"></script>

        {{-- sweetalert2 --}}
        <script src="{{asset('assets/vendor/sweetalert2.min.js')}}"></script>


        {{-- common js --}}
        <script src="{{ asset('assets/vendor/common-bundle-script.js') }}"></script>
        {{-- page specific javascript --}}
        @yield('page-js')

        <script src="{{asset('assets/vendor/toastr.min.js')}}"></script>
        <script src="{{asset('assets/vendor/nprogress.min.js')}}"></script>
        <script src="{{ asset('js/app.js') }}"></script>
        
        @yield('bottom-js')
    </body>
</html>