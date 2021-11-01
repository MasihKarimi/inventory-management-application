<!DOCTYPE html>
<!--[if IE 8]>
<html lang="{{ app()->getLocale() }}" class="ie8 no-js" dir="{{ LaravelLocalization::getCurrentLocaleDirection() }}> <![endif]-->
<!--[if IE 9]>
<html lang="{{ app()->getLocale() }}" class="ie9 no-js" dir="{{ LaravelLocalization::getCurrentLocaleDirection() }}> <![endif]-->
<!--[if !IE]><!-->
<html lang="{{ app()->getLocale() }}" dir="{{ LaravelLocalization::getCurrentLocaleDirection() }}">
<!--<![endif]-->
<head>
    <meta charset="utf-8"/>
    <title>ZeerSign | Shop Management</title>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta content="width=device-width, initial-scale=1" name="viewport"/>
    <meta content="" name="description"/>
    <meta content="" name="author"/>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @include('layouts.css')
    @yield('extraCSS')
    <link rel="shortcut icon" href="{{ asset('favicon.png') }}"/>
</head>
<body class="page-header-fixed page-sidebar-fixed page-container-bg-solid page-sidebar-closed-hide-logo page-content-white page-md">
<div class="page-wrapper">
    <div class="page-header navbar navbar-fixed-top">
        <div class="page-header-inner ">
            <div class="page-logo">
                <a href="{{ route('home') }}">
                    <img height="30px" style="background-color: #f0efd0" src="{{ asset('logo.png') }}"
                         alt="logo" class="logo-default"/> </a>
                <div class="menu-toggler sidebar-toggler">
                    <span></span>
                </div>
            </div>
            <a href="javascript:;" class="menu-toggler responsive-toggler" data-toggle="collapse"
               data-target=".navbar-collapse">
                <span></span>
            </a>
            @include('layouts.top-menu')
        </div>
    </div>
    <div class="clearfix"></div>
    <div class="page-container">
        <div class="page-sidebar-wrapper">
            @include('layouts.sidebar-nav')
        </div>
        <div class="page-content-wrapper">
            <div class="page-content">
                @yield('content')
            </div>
        </div>
    </div>
    @include('layouts.footer')
</div>
@include('layouts.quick-nav')
@include('layouts.js')
@yield('extraJS')
</body>
</html>
