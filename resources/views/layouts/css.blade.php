<link href="{{ asset('theme/global/plugins/font-awesome/css/font-awesome.min.css') }}" rel="stylesheet"
      type="text/css"/>
<link href="{{ asset('theme/global/plugins/simple-line-icons/simple-line-icons.min.css') }}" rel="stylesheet"
      type="text/css"/>
@if(LaravelLocalization::getCurrentLocaleDirection() == 'ltr')
    <link href="http://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700&subset=all" rel="stylesheet" type="text/css" />
    <link href="{{ asset('theme/ltr/global/plugins/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet" type="text/css"/>
    <link href="{{ asset('theme/global/plugins/bootstrap-switch/css/bootstrap-switch.min.css') }}" rel="stylesheet" type="text/css"/>
    <link href="{{ asset('theme/global/plugins/bootstrap-toastr/toastr.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('theme/ltr/global/css/components-md.min.css') }}" rel="stylesheet" id="style_components" type="text/css"/>
    <link href="{{ asset('theme/ltr/global/css/plugins-md.min.css') }}" rel="stylesheet" type="text/css"/>
    <link href="{{ asset('theme/ltr/layouts/layout/css/layout.min.css') }}" rel="stylesheet" type="text/css"/>
    <link href="{{ asset('theme/ltr/layouts/layout/css/themes/darkblue.min.css') }}" rel="stylesheet" type="text/css" id="style_color"/>
    <link href="{{ asset('theme/ltr/layouts/layout/css/custom.min.css') }}" rel="stylesheet" type="text/css"/>
@elseif(LaravelLocalization::getCurrentLocaleDirection() == 'rtl')
    <style>
        @font-face {
            font-family: "Open Sans";
            src: url('{{ asset('theme/rtl/fonts/NotoNaskhArabic-Regular.ttf') }}');
        }
        .dropdown-menu {
            font-family: "Open Sans", serif !important;
        }
    </style>
    <link href="{{ asset('theme/rtl/global/plugins/bootstrap/css/bootstrap-rtl.min.css') }}" rel="stylesheet" type="text/css"/>
    <link href="{{ asset('theme/global/plugins/bootstrap-switch/css/bootstrap-switch-rtl.min.css') }}" rel="stylesheet" type="text/css"/>
    <link href="{{ asset('theme/global/plugins/bootstrap-toastr/toastr-rtl.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('theme/rtl/global/css/components-md-rtl.min.css') }}" rel="stylesheet" id="style_components" type="text/css"/>
    <link href="{{ asset('theme/rtl/global/css/plugins-md-rtl.min.css') }}" rel="stylesheet" type="text/css"/>
    <link href="{{ asset('theme/rtl/layouts/layout/css/layout-rtl.min.css') }}" rel="stylesheet" type="text/css"/>
    <link href="{{ asset('theme/rtl/layouts/layout/css/themes/darkblue-rtl.min.css') }}" rel="stylesheet" type="text/css" id="style_color"/>
    <link href="{{ asset('theme/rtl/layouts/layout/css/custom-rtl.min.css') }}?v=1" rel="stylesheet" type="text/css"/>
@endif
<link href="{{ asset('theme/global/plugins/datatables/datatables.min.css') }}" rel="stylesheet" type="text/css"/>
<link href="{{ asset('theme/global/plugins/datatables/plugins/bootstrap/datatables.bootstrap.css') }}" rel="stylesheet" type="text/css"/>
<link href="{{ asset('theme/global/plugins/select2/css/select2.min.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ asset('theme/global/plugins/select2/css/select2-bootstrap.min.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ asset('theme/global/plugins/bootstrap-datepicker/css/bootstrap-datepicker3.min.css') }}" rel="stylesheet" type="text/css" />
