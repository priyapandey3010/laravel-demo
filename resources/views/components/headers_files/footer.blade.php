  <!-- -------------------- footer bottom start -------------------- -->
    <section class="footer-wrapper">
      <div class="container">
         
        <span style="background-color: #ffffff52;  height: 1px;   width: 100%;  display: block;  margin: 30px 0 10px 0;"></span>
        <div style="display:block; text-align: center;">
          <p style="color: #ffffffa8; font-size: 12px; argin-top: 27px;">Creative Commons License <br>
            Website is Designed & Developed by {Company Name}, ALl RIghts and © Copyright is reserved with {Company Name}</p>
        </div>
      </div>
    </section>
    <!-- -------------------- footer bottom end -------------------- -->



<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('admin-theme/vendor/jquery/jquery.min.js') }}"></script> 
    <script src="https://cdn.datatables.net/1.12.0/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.12.0/js/dataTables.bootstrap4.min.js"></script>
     <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/js/bootstrap.bundle.min.js"></script>
     <script type="text/javascript">
         function fn_case_dfr_type(val)
         {  $("#search_by").val(val);
            if (val=='filing_no_wise') {
                $("#caseType").hide();
                $("#case_no").hide();
                $("#party").hide();             
                $("#party_n").hide();
                $("#filling").show();

            }
            if (val=='case_no_wise') {
                $("#filling").hide();
                $("#party").hide();                             
                $("#party_n").hide();
                $("#caseType").show();
                $("#case_no").show();
            }
            if (val=='case_type_wise') { 
                $("#filling").hide();
                $("#case_no").hide();
                $("#party").hide();                
                $("#party_n").hide();
                $("#adv").hide();
                $("#caseType").show();
            }
            if (val=='party_wise') {
                 $("#caseType").hide();
                $("#case_no").hide();
                $("#filling").hide();
                $("#adv").hide();
                $("#party").show();
                $("#party_n").show();

            }
            if (val=='advocate_wise') {
                $("#caseType").hide();
                $("#case_no").hide();
                $("#party").hide();                
                $("#party_n").hide();
                $("#filling").hide();
                $("#adv").show();

            }
         }
           $(document).ready(function () {
    $('#example').DataTable();
}); 
     </script>
  </body>
</html>