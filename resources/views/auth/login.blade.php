<!DOCTYPE html>
<!--[if IE 8]>
<html lang="{{ app()->getLocale() }}" class="ie8 no-js"
      dir="{{ LaravelLocalization::getCurrentLocaleDirection() }}> <![endif]-->
<!--[if IE 9]>
<html lang="{{ app()->getLocale() }}" class="ie9 no-js"
      dir="{{ LaravelLocalization::getCurrentLocaleDirection() }}> <![endif]-->
<!--[if !IE]><!-->
<html lang="{{ app()->getLocale() }}" dir="{{ LaravelLocalization::getCurrentLocaleDirection() }}">
<!--<![endif]-->
<head>
    <meta charset="utf-8"/>
    <title>{{ __('auth.title') }}</title>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta content="width=device-width, initial-scale=1" name="viewport"/>
    <meta content="" name="description"/>
    <meta content="" name="author"/>
    <link href="{{ asset('theme/global/plugins/font-awesome/css/font-awesome.min.css') }}" rel="stylesheet" type="text/css"/>
    <link href="{{ asset('theme/global/plugins/simple-line-icons/simple-line-icons.min.css') }}" rel="stylesheet" type="text/css"/>
    <link href="{{ asset('theme/global/plugins/select2/css/select2.min.css') }}" rel="stylesheet" type="text/css"/>
    <link href="{{ asset('theme/global/plugins/select2/css/select2-bootstrap.min.css') }}" rel="stylesheet" type="text/css"/>
    @if(LaravelLocalization::getCurrentLocaleDirection() == 'ltr')
        <style>
            @font-face {
                font-family: 'Open Sans';
                font-style: normal;
                font-weight: 300;
                src: local('Open Sans Light'), local('OpenSans-Light'),
                url('{{ asset('theme/ltr/fonts//open-sans-v14-latin-300.woff2') }}') format('woff2'),
                url('{{ asset('theme/ltr/fonts//open-sans-v14-latin-300.woff') }}') format('woff');
            }

            @font-face {
                font-family: 'Open Sans';
                font-style: normal;
                font-weight: 400;
                src: local('Open Sans Regular'), local('OpenSans-Regular'),
                url('{{ asset('theme/ltr/fonts//open-sans-v14-latin-regular.woff2') }}') format('woff2'),
                url('{{ asset('theme/ltr/fonts//open-sans-v14-latin-regular.woff') }}') format('woff');
            }

            @font-face {
                font-family: 'Open Sans';
                font-style: normal;
                font-weight: 600;
                src: local('Open Sans SemiBold'), local('OpenSans-SemiBold'),
                url('{{ asset('theme/ltr/fonts//open-sans-v14-latin-600.woff2') }}') format('woff2'),
                url('{{ asset('theme/ltr/fonts//open-sans-v14-latin-600.woff') }}') format('woff');
            }

            @font-face {
                font-family: 'Open Sans';
                font-style: normal;
                font-weight: 700;
                src: local('Open Sans Bold'), local('OpenSans-Bold'),
                url('{{ asset('theme/ltr/fonts//open-sans-v14-latin-700.woff2') }}') format('woff2'),
                url('{{ asset('theme/ltr/fonts//open-sans-v14-latin-700.woff') }}') format('woff');
            }
        </style>
        <link href="{{ asset('theme/ltr/global/plugins/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet"
              type="text/css"/>
        <link href="{{ asset('theme/global/plugins/bootstrap-switch/css/bootstrap-switch.min.css') }}"
              rel="stylesheet" type="text/css"/>
        <link href="{{ asset('theme/global/plugins/bootstrap-toastr/toastr.min.css') }}" rel="stylesheet" type="text/css" />
        <link href="{{ asset('theme/ltr/global/css/components-md.min.css') }}" rel="stylesheet" id="style_components"
              type="text/css"/>
        <link href="{{ asset('theme/ltr/global/css/plugins-md.min.css') }}" rel="stylesheet" type="text/css"/>
        <link href="{{ asset('theme/ltr/pages/css/login-5.min.css') }}" rel="stylesheet" type="text/css"/>
    @elseif(LaravelLocalization::getCurrentLocaleDirection() == 'rtl')
        <style>
            @font-face {
                font-family: "Open Sans";
                src: url('{{ asset('theme/rtl/fonts/NotoNaskhArabic-Regular.ttf') }}');
            }
        </style>
        <link href="{{ asset('theme/rtl/global/plugins/bootstrap/css/bootstrap-rtl.min.css') }}" rel="stylesheet" type="text/css"/>
        <link href="{{ asset('theme/global/plugins/bootstrap-switch/css/bootstrap-switch-rtl.min.css') }}" rel="stylesheet" type="text/css"/>
        <link href="{{ asset('theme/global/plugins/bootstrap-toastr/toastr-rtl.min.css') }}" rel="stylesheet" type="text/css" />
        <link href="{{ asset('theme/rtl/global/css/components-md-rtl.min.css') }}" rel="stylesheet" id="style_components" type="text/css"/>
        <link href="{{ asset('theme/rtl/global/css/plugins-md-rtl.min.css') }}" rel="stylesheet" type="text/css"/>
        <link href="{{ asset('theme/rtl/pages/css/login-5-rtl.min.css') }}" rel="stylesheet" type="text/css"/>
    @endif
    <link rel="shortcut icon" href="{{ asset('favicon.png') }}"/>
</head>
<body class=" login">
<div class="user-login-5">
    <div class="row bs-reset">
        <div class="col-md-6 login-container bs-reset">
            <img class="login-logo login-6" src="{{ asset('logo.png') }}" height="160px"/>
            <div class="login-content">
                <h1>ZeerSign ICT Srvices</h1>
                <p>{{ __('auth.sub-title') }}</p>
                <form class="login-form" id="login-form" method="POST" action="{{ route('login') }}" data-success="{{ __('auth.success') }}">
                    @csrf
                    <div class="alert alert-danger display-hide">
                        <button class="close" data-close="alert"></button>
                        <span>{{ __('auth.login-validation') }}</span>
                    </div>
                    <div class="row">
                        <div class="col-xs-6">
                            <input class="form-control form-control-solid placeholder-no-fix form-group" type="text"
                                   autocomplete="off" placeholder="{{ __('auth.email') }}" name="email"/>
                        </div>
                        <div class="col-xs-6">
                            <input class="form-control form-control-solid placeholder-no-fix form-group" type="password"
                                   autocomplete="off" placeholder="{{ __('auth.password') }}" name="password"/>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-4">
                            <label class="rememberme mt-checkbox mt-checkbox-outline">
                                <input type="checkbox" name="remember" value="1"/> {{ __('auth.remember') }}
                                <span></span>
                            </label>
                        </div>
                        <div class="col-sm-8 text-right">
                            <div class="forgot-password">
                                <a href="javascript:;" id="forget-password" class="forget-password">{{ __('auth.forgot') }}</a>
                            </div>
                            <button class="btn blue" type="submit" id="sign-in-button">{{ __('auth.sign-in') }}</button>
                        </div>
                    </div>
                </form>
                <!-- BEGIN FORGOT PASSWORD FORM -->
                <form class="forget-form" action="{{ route('password.email') }}" method="POST">
                    @csrf
                    <h3>{{ __('auth.forgot') }}</h3>
                    <p> {{ __('auth.reset-note') }} </p>
                    <div class="form-group">
                        <input class="form-control form-control-solid placeholder-no-fix form-group" type="text"
                               autocomplete="off" placeholder="{{ __('auth.email') }}" name="email"/></div>
                    <div class="form-actions">
                        <button type="button" id="back-btn" class="btn blue btn-outline">{{ __('auth.back') }}</button>
                        <button type="submit" class="btn blue uppercase pull-right">{{ __('auth.submit') }}</button>
                    </div>
                </form>
                <!-- END FORGOT PASSWORD FORM -->
            </div>
            <div class="login-footer">
                <div class="row bs-reset">
                    <div class="col-xs-5 bs-reset">
                        <ul class="login-social">
                            <li>
                                <a href="javascript:;">
                                    <i class="icon-social-facebook"></i>
                                </a>
                            </li>
                            <li>
                                <a href="javascript:;">
                                    <i class="icon-social-twitter"></i>
                                </a>
                            </li>
                            <li>
                                <a href="javascript:;">
                                    <i class="icon-social-dribbble"></i>
                                </a>
                            </li>
                        </ul>
                    </div>
                    <div class="col-xs-7 bs-reset">
                        <div class="login-copyright text-right">
                            <p>Copyright &copy; <a href="#">ZeerSign</a></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6 bs-reset">
            <div class="login-bg"></div>
        </div>
    </div>
</div>
<?php $localeDir = LaravelLocalization::getCurrentLocaleDirection(); ?>
<!--[if lt IE 9]>
<script src="{{ asset('theme/global/plugins/respond.min.js') }}"></script>
<script src="{{ asset('theme/global/plugins/excanvas.min.js') }}"></script>
<script src="{{ asset('theme/global/plugins/ie8.fix.min.js') }}"></script>
<![endif]-->
<script src="{{ asset('theme/global/plugins/jquery.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('theme/'.$localeDir.'/global/plugins/bootstrap/js/bootstrap.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('theme/global/plugins/js.cookie.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('theme/global/plugins/jquery-slimscroll/jquery.slimscroll.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('theme/global/plugins/jquery.blockui.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('theme/global/plugins/bootstrap-switch/js/bootstrap-switch.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('theme/global/plugins/jquery-validation/js/jquery.validate.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('theme/global/plugins/jquery-validation/js/additional-methods.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('theme/global/plugins/select2/js/select2.full.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('theme/global/plugins/backstretch/jquery.backstretch.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('theme/global/plugins/bootstrap-toastr/toastr.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('theme/global/scripts/app.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('theme/pages/scripts/ui-toastr.js') }}" type="text/javascript"></script>
<script src="{{ asset('theme/pages/scripts/login-5.js') }}" type="text/javascript"></script>
<script>
    $(document).ready(function() {
        $('.login-bg').backstretch([
            "{{ asset('theme/images/bg1.jpg') }}",
            "{{ asset('theme/images/bg2.jpg') }}",
            "{{ asset('theme/images/bg3.jpg') }}"
            ], {
                fade: 1000,
                duration: 8000
            }
        );
    });
</script>
</body>

</html>
