@include('components.headers_files.header') 
 
        <section class="content-area">
        <div class="container">
          <div class="row">
            <div class="col-lg-3 col-md-3 col-sm-12">
              <div class="right-tabs">
                <ul>
                  <li class="active" onclick="fn_case_dfr_type('filing_no_wise')">Filing Number</li>
                  <li onclick="fn_case_dfr_type('case_no_wise')">Case Number</li>
                  <li onclick="fn_case_dfr_type('case_type_wise')">Case Type</li>
                  <li onclick="fn_case_dfr_type('party_wise')">Party Name</li>
                  <li onclick="fn_case_dfr_type('advocate_wise')">Advocate Name</li>
                </ul>
              </div>
            </div>
            <div class="col-lg-9 col-lg-9 col-sm-12">
              <form action="{{ url('cases_details') }}" method="post" autocomplete="off" class="row" role="form" novalidate>{{ csrf_field() }}
                <input type="hidden" name="search_by" id="search_by" value="case_no_wise">
                <div class="col-md-4 mb-4">
                  <label class="form-label">Location</label>
                  <select required="required" class="form-control" name="location" id="location">
                        <?php foreach ($FormData->benches as $key => $value) { ?>
                                            <option value="<?php echo $value->schema_name; ?>"><?php echo $value->city_name; ?></option> 
                                        <?php  }  ?>
                                        </select>
                </div>

                <div class="col-md-4 mb-4" id="caseType">
                  <label class="form-label">Select Case Type</label>
                  <select  class="form-control" name="case_type" id="case_type">
                     <option value="">Select</option>
                      <?php foreach ($FormData->case_types as $key => $value) { ?>
            <option value="<?php echo $value->case_type_code; ?>"><?php echo $value->case_type_name; ?></option>
                <?php }  ?>
                                        </select>
                </div>

                <div class="col-md-4 mb-4" id="party" style="display:none">
                  <label class="form-label">Party Type</label>
                   <select required="required" class="form-control" name="select_party" id="select_party">
                    <option value="1">Main Party</option>
                    <option value="2">Addtional Party</option>
                </select>
                </div>

                <div class="col-md-4 mb-4"  id="party_n" style="display:none">
                  <label class="form-label">Party Name</label>
                  <input type="text" name="party_name" id="party_name" class="form-control ui-autocomplete-input" autocomplete="off">
                </div>

                <div class="col-md-4 mb-4" id="filling"  style="display:none">
                  <label class="form-label">Enter Filing No.</label>
              <input type="text" name="diary_no" id="diary_no" class="form-control">
                </div>
                 <div class="col-md-4 mb-4" id="case_no">
                    <label class="form-label">Enter Case No.</label>
                    <input type="text" name="case_number" id="case_number" class="form-control required">
                </div>
                
                <div class="col-md-4 mb-4"  id="adv" style="display:none">
                  <label class="form-label">Legal Representative Name</label>
                  <input type="text" name="advocate_name" id="advocate_name" class="form-control required ui-autocomplete-input" autocomplete="off">
                </div>
                <div class="col-md-4 mb-4">
                  <label class="form-label">Case Year</label>
                  
                  <select class="form-control required" name="case_year" id="case_year">
                        <option value="">Select</option>
                        <option value="All" selected>All</option>
                        <option value="2022">2022</option>
                        <option value="2021">2021</option>
                        <option value="2020">2020</option>
                        <option value="2019">2019</option>
                        <option value="2018">2018</option>
                        <option value="2017">2017</option>
                        <option value="2016">2016</option>
                    </select>
                </div>
                <div class="col-md-4 mb-4" style="margin-top: 28px;">
                  <button class="btn btn-primary" type="submit">Search</button>
                </div>
              </form>
            </div>
          </div>
        </div>
      </section>
     
 @include('components.headers_files.footer')