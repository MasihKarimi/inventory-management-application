<?php $localeDir = LaravelLocalization::getCurrentLocaleDirection(); ?>
<!--[if lt IE 9]>
<script src="{{ asset('theme/global/plugins/respond.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('theme/global/plugins/excanvas.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('theme/global/plugins/ie8.fix.min.js') }}" type="text/javascript"></script>
<![endif]-->
<!-- BEGIN CORE PLUGINS -->
<script src="{{ asset('theme/global/plugins/jquery.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('theme/'.$localeDir.'/global/plugins/bootstrap/js/bootstrap.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('theme/global/plugins/js.cookie.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('theme/global/plugins/jquery-slimscroll/jquery.slimscroll.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('theme/global/plugins/jquery.blockui.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('theme/global/plugins/bootstrap-switch/js/bootstrap-switch.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('theme/global/plugins/bootstrap-toastr/toastr.min.js') }}" type="text/javascript"></script>
<!-- END CORE PLUGINS -->
<!-- BEGIN THEME GLOBAL SCRIPTS -->
<script src="{{ asset('theme/global/plugins/counterup/jquery.waypoints.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('theme/global/plugins/counterup/jquery.counterup.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('theme/pages/scripts/ui-toastr.js') }}" type="text/javascript"></script>
<script src="{{ asset('theme/global/scripts/general_functions.js?v1.3') }}" type="text/javascript"></script>
<script src="{{ asset('theme/global/scripts/datatable.js') }}" type="text/javascript"></script>
<script src="{{ asset('theme/global/plugins/datatables/datatables.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('theme/global/plugins/datatables/plugins/bootstrap/datatables.bootstrap.js') }}" type="text/javascript"></script>
<script src="{{ asset('theme/global/plugins/select2/js/select2.full.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('theme/global/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('theme/global/plugins/jquery-repeater/jquery.repeater.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('theme/global/scripts/app.min.js') }}" type="text/javascript"></script>
<!-- END THEME GLOBAL SCRIPTS -->
<!-- BEGIN THEME LAYOUT SCRIPTS -->
<script src="{{ asset('theme/layouts/layout/scripts/layout.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('theme/layouts/global/scripts/quick-nav.min.js') }}" type="text/javascript"></script>
<!-- END THEME LAYOUT SCRIPTS -->
<script>
    if (jQuery().datepicker) {
        $('.date-picker').datepicker({
            rtl: App.isRTL(),
            orientation: "left",
            weekStart: 6,
            autoclose: true
        });
    }
</script>
