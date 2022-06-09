
    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>

    @include('components.admin.modal-delete')

    <!-- Logout Modal-->
    <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Ready to Leave?</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">Select "Logout" below if you are ready to end your current session.</div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                    <form method="POST" action="{{ url('logout') }}" id="logout-form">
                        @csrf
                        <button type="submit" class="btn btn-primary" href="#" id="btn-logout">Logout</button>
                    </form>
                </div>
            </div>
        </div>
    </div>


    <input type="hidden" id="csrf-token" value="{{ csrf_token() }}" />

    <!-- Bootstrap core JavaScript-->
    <script src="{{ asset('admin-theme/vendor/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('admin-theme/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>

    <!-- Core plugin JavaScript-->
    <script src="{{ asset('admin-theme/vendor/jquery-easing/jquery.easing.min.js') }}"></script>

    <!-- Custom scripts for all pages-->
    <script src="{{ asset('admin-theme/js/sb-admin-2.min.js') }}"></script>
    <script src="{{ asset('admin-theme/vendor/datatables.net-bs4/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('admin-theme/vendor/datatables.net-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('admin-theme/vendor/datatables.net-bs4/js/dataTables.rowReorder.min.js') }}"></script>
    <script src="{{ asset('admin-theme/vendor/bootstrap-duallistbox-master/dist/jquery.bootstrap-duallistbox.min.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>

    <script src="{{ asset('js/script.js?ver=1.5') }}"></script>

    @yield('js')


</body>

</html>