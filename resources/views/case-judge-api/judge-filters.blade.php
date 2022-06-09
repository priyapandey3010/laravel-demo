 @include('components.headers_files.header') 
    <section class="filter-sec">
        <div class="container py-3">
    <div class="row">
      <div class="col-md-12"> 
         
        <div class="row justify-content-center">
          <div class="col-md-6">
            <span class="anchor" id="formLogin"></span> 
                        <!-- form card login -->
            <div class="card card-outline-secondary">
              <div class="card-header">
                <h3 class="mb-0">JUDGEMENT STATUS</h3>
              </div>
              <?php //print_r($FormData); die();  ?>
              <div class="card-body">
                <form action="{{ url('cases_details') }}" method="post" autocomplete="off" class="form" role="form">{{ csrf_field() }}
                  <div class="form-group">
                    <label for="uname1"></label> 
                      <select required="required" class="form-control" name="location" id="location">
                        <?php foreach ($FormData->benches as $key => $value) { ?>
                                            <option value="<?php echo $value->schema_name; ?>"><?php echo $value->city_name; ?></option> 
                                        <?php  }  ?>
                                        </select>
                  </div>
                  <div class="form-group">
                    <label>Select Search By</label> 
                    <select required="required" class="form-control" name="search_by" id="search_by" onchange="fn_case_dfr_type(this.value)">
                         <?php foreach ($FormData->search_by as $key => $value) { ?>
                    <option value="<?php echo $key; ?>"><?php echo $value; ?></option> 
                        <?php }  ?>
                        
                    </select>
                  </div> 
    <div class="form-group" id="caseType">
        <label>Select Case Type</label>
        <select required="required" class="form-control" name="case_type" id="case_type">
            <option value="">Select</option>
             <?php foreach ($FormData->case_types as $key => $value) { ?>
            <option value="<?php echo $value->case_type_code; ?>"><?php echo $value->case_type_name; ?></option>
                <?php }  ?></select>
    </div>
    <div class="form-group" id="party"  style="display:none">
        <label>Select Type</label>
        <select required="required" class="form-control" name="select_party" id="select_party">
            <option value="1">Main Party</option>
            <option value="2">Addtional Party</option>
        </select>
    </div>
                  <div class="form-group" id="case_no">
                    <label>Enter Case No.</label>
                    <input type="text" name="case_number" id="case_number" class="form-control required">
                </div>
        <div class="form-group" id="filling"  style="display:none">
        <label>Enter Filing No.</label>
        <input type="text" name="diary_no" id="diary_no" class="form-control">
    </div>
    <div class="form-group" id="party_n" style="display:none">
        <label>Party Name</label>
        <input type="text" name="party_name" id="party_name" class="form-control ui-autocomplete-input" autocomplete="off">
    </div>
    <div class="form-group" id="adv"  style="display:none">
        <label>Legal Representative Name</label>
        <input type="text" name="advocate_name" id="advocate_name" class="form-control required ui-autocomplete-input" autocomplete="off">
    </div>
                <div class="form-group">
                    <label>Select Case Year</label>
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

                    <button class="btn btn-success btn-lg float-right" type="submit">Search</button>
                </form>
              </div><!--/card-block-->
            </div><!-- /form card login -->
          </div>
        </div>
        
      </div><!--/col-->
    </div><!--/row-->
  </div><!--/container--> 
    </section> 
  @include('components.headers_files.footer')