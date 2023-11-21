<!-- BEGIN: Vendor JS-->
<script src="{{ asset(mix('assets/vendor/libs/jquery/jquery.js')) }}"></script>
<script src="{{ asset(mix('assets/vendor/libs/popper/popper.js')) }}"></script>
<script src="{{ asset(mix('assets/vendor/js/bootstrap.js')) }}"></script>
<script src="{{ asset(mix('assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js')) }}"></script>
<script src="{{ asset(mix('assets/vendor/libs/node-waves/node-waves.js')) }}"></script>
<script src="{{ asset(mix('assets/vendor/libs/hammer/hammer.js')) }}"></script>
<script src="{{ asset(mix('assets/vendor/libs/typeahead-js/typeahead.js')) }}"></script>
<script src="{{ asset(mix('assets/vendor/js/menu.js')) }}"></script>
<script src="{{asset(mix('assets/vendor/libs/jquery-validation/jquery-validation.js'))}}"></script>
<script src="{{asset(mix('assets/vendor/libs/toastr/toastr.js'))}}"></script>
<script src="{{asset(mix('assets/vendor/libs/datatables-bs5/datatables-bootstrap5.js'))}}"></script>
<script src="{{asset(mix('assets/vendor/libs/sweetalert2/sweetalert2.js'))}}"></script>
<script src="{{asset(mix('assets/vendor/libs/select2/select2.js'))}}"></script>
<script src="{{asset('custom/main.js')}}"></script>
@yield('vendor-script')
<!-- END: Page Vendor JS-->
<!-- BEGIN: Theme JS-->
<script src="{{ asset(mix('assets/js/main.js')) }}"></script>

<!-- END: Theme JS-->
<!-- BEGIN: Page JS-->
@yield('js')
<!-- END: Page JS-->
