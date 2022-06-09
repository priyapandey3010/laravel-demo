  @include('components.headers_files.header') 
  <style type="text/css">div#example_filter {
    float: right;
}</style>
       <div class="container"><section class="filter-sec">

        <table id="example" class="table table-striped table-bordered" style="width:100%">
        <thead>
            <tr>
                <th>Sr. No</th>
                <th>Filing No.</th>
                <th>Case No.</th>
                <th>Case Title</th>
                <th>Registration Date</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($Response->data as $key => $value) { ?>
            <tr>
                <td><?php echo ++$key; ?></td>
                <td><?php echo $value->filing_no; ?></td>
                <td><?php echo $value->case_no; ?></td>
                <td><?php echo $value->case_title; ?></td>
                <td><?php echo $value->date_of_registration; ?></td>  
                <td><button type="button" class="btn btn-success" onclick="fn_case_details('<?php echo $value->filing_no; ?>')"> <i class="fa fa-eye"></i> View</button></td>
            </tr> 
        <?php  }  ?>
        </tbody>
    </table>
    </section>
  </div>


   <!-- Modal -->
<div class="modal fade" id="nclatModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-scrollable modal-lg">
    <div class="modal-content">
      <div class="modal-header" style="padding: 8px 20px; background-color: #e9f2ff;">
        <h5 class="modal-title" id="exampleModalLabel">NCLAT</h5>
        <button type="button" class="btn-close text-white" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <table class="table">
        <tbody><tr>
            <th colspan="5" style="text-align: center"> 
                <span id="left"></span><br> 
                <span style="color:red"> VS <br> </span> 
                <span id="right"></span></th>
            </tr></tbody>
        </table>
        <div class="accordion" id="accordionExample">
          <div class="accordion-item">
            <h2 class="accordion-header" id="headingOne">
              <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                1. Case Detail (Filing No. <span id="fillingID"></span>)
              </button>
            </h2>
            <div id="collapseOne" class="accordion-collapse collapse show" aria-labelledby="headingOne" data-bs-parent="#accordionExample">
              <div class="accordion-body">
                <table class="table table-bordered" id="CaseTable">
                   <tr></tr>
              </table>
              </div>
            </div>
          </div>
          <div class="accordion-item">
            <h2 class="accordion-header" id="headingOne">
              <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapsetwo" aria-expanded="true" aria-controls="collapseOne">
               2. Party Details
              </button>
            </h2>
            <div id="collapsetwo" class="accordion-collapse collapse" aria-labelledby="headingOne" data-bs-parent="#accordionExample">
              <div class="accordion-body">
                <div class="row">
                <div class="col-sm-6">
                    <table class="table table-bordered" id="PartyTable">
                    <tr><th>Sr. No.</th><th>applicant/appellant`s Name.</th></tr>
                </table></div>
                <div class="col-sm-6"><table class="table table-bordered" id="PartyTableTwo">
                    <tr><th>Sr. No.</th><th>Respodent Name</th></tr>
                </table></div>
                </div>
                 
              </div>
            </div>
          </div>
          <div class="accordion-item">
            <h2 class="accordion-header" id="headingOne">
              <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapsethree" aria-expanded="true" aria-controls="collapseOne">
                3. Legal Representative
              </button>
            </h2>
            <div id="collapsethree" class="accordion-collapse collapse" aria-labelledby="headingOne" data-bs-parent="#accordionExample">
              <div class="accordion-body">
                <div class="row">
                <div class="col-sm-6">
                    <table class="table table-bordered" id="LegalTable">
                    <tr><th>Sr. No.</th><th>applicant/appellant`s Legal Representative Name</th></tr>
                </table></div>
                <div class="col-sm-6"><table class="table table-bordered" id="LegalTableTwo">
                    <tr><th>Sr. No.</th><th>Respodent Legal Representative Name</th></tr>
                </table></div>
                </div>
              </div>
            </div>
          </div>
          <div class="accordion-item">
            <h2 class="accordion-header" id="headingOne">
              <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapsefour" aria-expanded="true" aria-controls="collapseOne">
                4. First Hearing Details
              </button>
            </h2>
            <div id="collapsefour" class="accordion-collapse collapse" aria-labelledby="headingOne" data-bs-parent="#accordionExample">
              <div class="accordion-body">
                <table class="table table-bordered" id="HearingTable">
                    <tr><th>Court No.</th><td><span id="cno"></span></td><th>Hearing Date</th><td><span id="hdate"></span></td></tr>
                    <tr><th>Coram</th><td><span id="Coram"></span></td><th>Stage Of Case</th><td><span id="stage"></span></td></tr>
                   
              </table>
                
              </div>
            </div>
          </div>
          <div class="accordion-item">
            <h2 class="accordion-header" id="headingOne">
              <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapsefive" aria-expanded="true" aria-controls="collapseOne">
                5. Last Hearing Details
              </button>
            </h2>
            <div id="collapsefive" class="accordion-collapse collapse" aria-labelledby="headingOne" data-bs-parent="#accordionExample">
              <div class="accordion-body">
                <table class="table table-bordered" id="HearingTable">
                    <tr><th>Court No.</th><td><span id="lcno"></span></td><th>Hearing Date</th><td><span id="lhdate"></span></td></tr>
                    <tr><th>Coram</th><td><span id="lCoram"></span></td><th>Stage Of Case</th><td><span id="lstage"></span></td></tr>
                   
              </table>
                
              </div>
            </div>
          </div>
          <div class="accordion-item">
            <h2 class="accordion-header" id="headingOne">
              <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapsesix" aria-expanded="true" aria-controls="collapseOne">
                6. Next Hearing Details
              </button>
            </h2>
            <div id="collapsesix" class="accordion-collapse collapse" aria-labelledby="headingOne" data-bs-parent="#accordionExample">
              <div class="accordion-body">
                <table class="table table-bordered" id="HearingTable">
                    <tr><th>Hearing Date</th><td><span id="nhdate"></span></td><th>Court No.</th><td><span id="ncno"></span></td></tr>
                    <tr><th>Proceedings Summary</th><td><span id="nCoram"></span></td><th>Stage Of Case</th><td><span id="nstage"></span></td></tr>
                   
              </table>
                
              </div>
            </div>
          </div>

          <div class="accordion-item">
            <h2 class="accordion-header" id="headingOne">
              <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseseven" aria-expanded="true" aria-controls="collapseOne">
                7. Case History
              </button>
            </h2>
            <div id="collapseseven" class="accordion-collapse collapse" aria-labelledby="headingOne" data-bs-parent="#accordionExample">
              <div class="accordion-body">
                <table class="table table-bordered" id="CasehTable">
                    <tr>
                        <th>Sr. No.</th>
                        <th>Hearing Date</th>
                        <th>Court No</th>
                        <th>Purpose</th>
                        <th>Action</th>
                    </tr>                   
              </table>                
              </div>
            </div>
          </div>

          <div class="accordion-item">
            <h2 class="accordion-header" id="headingOne">
              <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseeight" aria-expanded="true" aria-controls="collapseOne">
                8. Order History
              </button>
            </h2>
            <div id="collapseeight" class="accordion-collapse collapse" aria-labelledby="headingOne" data-bs-parent="#accordionExample">
              <div class="accordion-body">
                <table class="table table-bordered" id="OrderTable">
                    <tr>
                        <th>Sr. No.</th>
                        <th>Order Date</th>
                        <th>Order Type</th>
                        <th>View</th> 
                    </tr>                   
              </table>                
              </div>
            </div>
          </div>

          <div class="accordion-item">
            <h2 class="accordion-header" id="headingOne">
              <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapsenine" aria-expanded="true" aria-controls="collapseOne">
                9. IA's/Other applications 
              </button>
            </h2>
            <div id="collapsenine" class="accordion-collapse collapse" aria-labelledby="headingOne" data-bs-parent="#accordionExample">
              <div class="accordion-body">
                <table class="table table-bordered" id="appTable">
                    <tr>
                        <th>Sr. No.</th>
                        <th>Filing No</th>
                        <th>Case No</th>
                        <th>Date of filing</th> 
                        <th>Registration date</th>
                        <th>Status</th>
                    </tr>                   
              </table>                
              </div>
            </div>
          </div>

          <div class="accordion-item">
            <h2 class="accordion-header" id="headingOne">
              <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseten" aria-expanded="true" aria-controls="collapseOne">
                9. Connected Cases 
              </button>
            </h2>
            <div id="collapseten" class="accordion-collapse collapse" aria-labelledby="headingOne" data-bs-parent="#accordionExample">
              <div class="accordion-body">
                <table class="table table-bordered" id="conTable">
                    <tr>
                        <th>Sr. No.</th>
                        <th>Filing No</th>
                        <th>Case No</th>
                        <th>Date of filing</th> 
                        <th>Registration date</th>
                        <th>Status</th>
                    </tr>                   
              </table>                
              </div>
            </div>
          </div>

        </div>
      </div>
    </div>
  </div>
</div>

 @include('components.headers_files.footer');
 <script type="text/javascript">
     function fn_case_details(filing_no) {
    $('#fillingID').html(filing_no);
    var data = {};
    data['search_type'] = 'view_details';
    data['filing_no'] = filing_no;
    data['_token'] = "{{ csrf_token() }}";
    data['bench_name'] = '<?php echo $postData; ?>';
    $.ajax({
        type: "POST",
        url: "{{ url('view_details') }}",
        data: data,
        dataType: "text",
        success: function (response) {
            var res = JSON.parse(response);
            case_details=res.data.case_details;
            party_details_app=res.data.party_details.applicant_name;
            party_details_res=res.data.party_details.respondant_name;
            legal_representative_app=res.data.legal_representative.applicant_legal_representative_name;
            legal_representative_res=res.data.legal_representative.respondent_legal_representative_name;
            first_hearing_details=res.data.first_hearing_details;
            last_hearing_details=res.data.last_hearing_details;
            next_hearing_details=res.data.next_hearing_details;
            case_history=res.data.case_history;
            order_history=res.data.order_history;
            ias_other_application=res.data.ias_other_application;
            connected_cases=res.data.connected_cases; 
            htmlFirst ='';
            //case detail
              partyHtml = '';
              partyTwoHtml = '';
              legelHtml = '';
              legelTwoHtml = '';
              caseHtml = '';
              orderHtml = '';
               $("#left").html(party_details_app[0].name);
               $("#right").html(party_details_res[0].name);
              case_details.forEach(function(item,i){
              htmlFirst +='<tr><th>Filing No</td><td>'+item.filing_no+'</td><th>Date Of Filing</td><td>'+item.date_of_filing+'</td></tr><tr><th>Case No</td><td>'+item.case_type+'</td><th>Registration Date</td><td>'+item.registration_date+'</td></tr><tr><th>Status</td><td>'+item.status+'</td></tr>';  
             });
            $('#CaseTable').html(htmlFirst);
            //party detail
              party_details_app.forEach(function(item,i){ 
              partyHtml +='<tr><td>'+ ++i +'</td><td>'+item.name+'</td></tr>';   
              }); 
              
              $('#PartyTable tr:first').after(partyHtml);
              party_details_res.forEach(function(item,j){ 
                
              partyTwoHtml +='<tr><td>'+ ++j +'</td><td>'+item.name+'</td></tr>';   
              });
             
              $('#PartyTableTwo tr:first').after(partyTwoHtml);
              //Legel
            legal_representative_app.forEach(function(item,i){   
              legelHtml +='<tr><td>'+ ++i +'</td><td>'+item+'</td></tr>';   
              }); 
              $('#LegalTable tr:first').after(legelHtml);
              legal_representative_res.forEach(function(item,j){ 
               
              legelTwoHtml +='<tr><td>'+ ++j +'</td><td>'+item+'</td></tr>';   
              });
              $('#LegalTableTwo tr:first').after(legelTwoHtml);

              //console.log(first_hearing_details);
              $("#cno").html(first_hearing_details.court_no);
              $("#hdate").html(first_hearing_details.hearing_date);
              $("#Coram").html(first_hearing_details.coram);
              $("#stage").html(first_hearing_details.stage_of_case);
              //Last Hearing
              $("#lcno").html(last_hearing_details.court_no);
              $("#lhdate").html(last_hearing_details.hearing_date);
              $("#lCoram").html(last_hearing_details.coram);
              $("#lstage").html(last_hearing_details.stage_of_case);
              //Next Hearing
              $("#ncno").html(next_hearing_details.court_no);
              $("#nhdate").html(next_hearing_details.hearing_date);
              $("#nCoram").html(next_hearing_details.coram);
              $("#nstage").html(next_hearing_details.stage_of_case);
              //Case history

              case_history.forEach(function(item,j){                
              caseHtml +='<tr><td>'+ ++j +'</td><td>'+item.hearing_date+'</td><td>'+item.court_no+'</td><td>'+item.purpose+'</td><td><button type="button" class="btn btn-success"> <i class="fa fa-eye"></i> View</button></td></tr>';   
              });
              $('#CasehTable tr:first').after(caseHtml);
              //Order
              order_history.forEach(function(item,j){                
              orderHtml +='<tr><td>'+ ++j +'</td><td>'+item.order_date+'</td><td>'+item.order_type+'</td><td><a href="'+item.order_pdf_download+'"> <i class="fa fa-pdf"></i> Download</a></td></tr>';   
              });
              $('#OrderTable tr:first').after(orderHtml);
              // Nine Step
              appHtml='';
              ias_other_application.forEach(function(item,j){                
              appHtml +='<tr><td>'+ ++j +'</td><td>'+item.filing_no+'</td><td>'+item.case_no+'</td><td>'+item.filing_date+'</td><td>'+item.registration_date+'</td><td>'+item.status+'</td></tr>';   
              });
              $('#appTable tr:first').after(appHtml);
              // ten step
               conHtml='';
              connected_cases.forEach(function(item,j){                
              conHtml +='<tr><td>'+ ++j +'</td><td>'+item.filing_no+'</td><td>'+item.case_no+'</td><td>'+item.filing_date+'</td><td>'+item.registration_date+'</td><td>'+item.status+'</td></tr>';   
              });
              $('#conTable tr:first').after(conHtml);

        },
        error: function (request, error) {
            alert("something error");
            $('.load_container').hide();
        }
    });
    $("#nclatModal").modal('show');
}
 </script>