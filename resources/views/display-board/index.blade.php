@extends('components.admin.layout')

@if (acl('display-board-manage'))
@section('css')

tr:hover {
    cursor:move;
}

.moveall, .removeall {
  background-color: teal;
  color: #fff;
}

@endsection
@endif

@section('page-content')

<div class="container-fluid">
  @include('components.admin.page-header', ['pageTitle' => __('Manage Display Board')])
  @include('components.admin.alert-manager')
  <div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">{{ __('Manage Display Board') }}
            <span class="float-right" id="header-buttons" style="display:none;">
            @if (acl('display-board-manage'))
              <button type="button" id="sort-by-item-number" class="btn btn-sm btn-success">{{ __('Start by Sequence') }}</button>
                <button type="button" id="start-display" class="btn btn-sm" style="background: green; color: #fff;">{{ __('Start Display') }}</button>
                <button type="button" id="stop-display" class="btn btn-sm btn-warning">{{ __('Stop Display') }}</button>
                <button type="button" id="restart-session" class="btn btn-sm btn-info">{{ __('Restart Session') }}</button>
                <button type="button" id="clear-list" class="btn btn-sm btn-danger">{{ __('Clear List') }}</button>
                @endif
            </span>
        </h6>
    </div>
    <div class="card-body">
      <form id="formID">
      <div class="p-3 mb-3">
        <div class="form-row">
          <div class="form-group col-md-4">
              <label class="required">{{ __('Select Date') }}</label>
              <input type="text" name="calendar" id="calendar" class="form-control" placeholder="{{ __('Calendar') }}" />
              <span class="text-danger form-error" id="calendar_error"></span>
          </div>
          <div class="form-group col-md-4">
              <label class="required">{{ __('Case Type') }}</label>
              {{ Form::select('case_type_id', case_type_list(), $row['case_type_id'] ?? null, ['class' => 'form-control', 'id' => 'case_type_id']) }}
              <span class="text-danger form-error" id="case_type_id_error"></span>
          </div>
          <div class="form-group col-md-4">
            <label class="required">{{ __('Case Number') }}</label>
            <select class="form-control" name="case_number" id="case_number">
              <option value="">{{ __('Select') }}</option>
            </select>
            <span class="text-danger form-error" id="case_number_error"></span>
          </div>
        </div>
        <div class="form-row">
          <div class="form-group col-md-4" id="case-title-dropdown" style="display:none;">
            <label class="required">{{ __('Case Title') }}</label>
            <select class="form-control" name="case_title" id="case_title">
            </select>
            <span class="text-danger form-error" id="case_title_error"></span>
          </div>
          @if (auth()->user()->role_id == config('role.bench-admin'))
          <div class="form-group col-md-4">
            <label class="required">{{ __('Court') }}</label>
            {{ Form::select('court_id', bench_court_list(), $row['court_id'] ?? null, ['class' => 'form-control', 'id' => 'court_id']) }}
            <span class="text-danger form-error" id="court_id_error"></span>
          </div>
          @endif
          <div class="form-group col-md-4">
              <label class="required">{{ __('Category') }}</label>
              {{ Form::select('category_id', category_list(), $row['category_id'] ?? null, ['class' => 'form-control', 'id' => 'category_id']) }}
              <span class="text-danger form-error" id="category_id_error"></span>
          </div>
        </div>
        <div class="form-row">
          <div class="form-group col-md-4">
            <label class="required">{{ __('Item Number') }}</label>
            <input type="text" class="form-control" id="item_number" name="item_number" placeholder="{{ __('Item Number') }}" maxlength="200" />
            <span class="text-danger form-error" id="item_number_error"></span>
          </div>
        </div>
        <div class="form-row">
          <button type="submit" class="btn btn-info" id="btn-filter">Submit</button> &nbsp;
          <button type="button" class="btn btn-secondary" id="btn-reset">Reset</button>
        </div>
      </div>
    </form>
      
        <div class="table-responsive" id="display-board-table">
            <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th>{{ __('message.sno') }}</th>
                        <th class="th-width">
                            <label class="pos-rel"><input type="checkbox" value="1" name="checkall" class="ace" /><span class="lbl"></span></label>
                        </th>
                        <th>{{ __('Start Session') }}</th>
                        <th>{{ __('message.item_number') }}</th>
                        <th>{{ __('message.case_type') }}</th>
                        <th>{{ __('Category') }}</th>
                        <th>{{ __('message.case_title') }}</th>
                        <th>{{ __('message.case_number') }}</th>
                        <th>{{ __('message.bench') }}</th>
                        <th>{{ __('message.court') }}</th>
                        <th>{{ __('message.status') }}</th>
                        <th>{{ __('Is Display?') }}</th>
                    </tr>
                </thead>
            </table>
        </div>

        @if (session('is_reordered')) 
          <button type="button" id="btn-next" class="btn btn-success disabled" disabled="disabled">{{ __('NEXT >>') }}</button>
          <span class="text-info" id="next-msg"></span>  
        @else
          <button type="button" id="btn-next" class="btn btn-success">{{ __('NEXT >>') }}</button>
        @endif
    </div>
    @if (acl('display-board-manage'))
    <div class="card-footer" id="footer-buttons" style="display:none;">
        @isset($statuses)
            @foreach ($statuses as $status)
                <button type="button" class="btn btn-sm btn-status" data-status="{{ $status->id }}" style="background-color: {{ $status->colour_code }}; color: #fff;">{{ $status->status }}</button>
            @endforeach
        @endisset
    </div>
    @endif
  </div>
</div>

@endsection

@section('js')

<script>
$(document).ready(function() {
  dataTableInit({
    id: "#dataTable",
    order: {
      column: 0,
      direction: "ASC"
    },
    url: "{{ url('display-board/datalist') }}",
    @if (acl('display-board-manage'))
    rowReorder: true,
    @endif
    columns: [
        {
        "orderable": true,
        "render": function(data, type, full, meta) {
          return serialNumber("#dataTable", meta.row);
        }
       },
        {
            "orderable": false,
            "render": function(data, type, row) {
                return `<label class="pos-rel">
                    <input type="checkbox" value="${row.id}" name="case_id" class="ace" /><span class="lbl"> </span>
                </label>`;
            }
      },
      {
        "orderable": false,
        "render": function(data, type, row) {
          if (row.status === 'In Session') {
            return `<button type="button" class="btn btn-sm btn-success" id="stop-session-button" data-id="${row.id}">Completed</button>`;
          } else {
            return `<button type="button" class="btn btn-sm btn-primary" id="start-session-button" data-id="${row.id}">Start</button>`;
          }
          
        }
      },
      {
        "orderable": false,
        "render": function(data, type, row) {
            return row.item_number;
        }
      },
      {
        "orderable": false,
        "render": function(data, type, row) {
            return row.case_type;
        }
      },
      {
        "orderable": false,
        "render": function(data, type, row) {
            return row.category_name;
        }
      },
      {
        "orderable": false,
        "render": function(data, type, row) {
            return row.case_title;
        }
      },
      {
        "orderable": false,
        "render": function(data, type, row) {
            return row.case_number;
        }
      },
      {
        "orderable": false,
        "render": function(data, type, row) {
            return row.bench_name;
        }
      },
      {
        "orderable": false,
        "render": function(data, type, row) {
            return row.court_name;
        }
      },
      {
        "orderable": false,
        "render": function(data, type, row) {
            return `<span style="color:${row.colour_code}">${row.status}</span>`;
        }
      },
      {
        "orderable": false,
        "render": function(data, type, row) {
          if (row.is_display) {
            return `<span class="badge badge-success p-2">In Display</span>`;
          } else {
            return `<span class="badge badge-warning p-2">Not In Display</span>`;
          }
            
        }
      }
    ],
    filters: ["case_type_id", "case_numbers", "calendar"] 
  });

  $("#formID").on("submit", function (event) {
      event.preventDefault();
      var calendar = $("#calendar").val();
      var caseTypeId = $("#case_type_id").val();
      var caseNumber = $("#case_number").val();
      var caseTitle = $("#case_title").val();
      var courtId = $("#court_id").val();
      var categoryId = $("#category_id").val();
      var itemNumber = $("#item_number").val();
      var csrfToken = "{{ csrf_token() }}";
      var method = 'POST';
      var url = "{{ url('/display-board') }}";

      var requestData = {
          url: url,
          method: method,
          body: {
              calendar: calendar,
              case_type_id: caseTypeId,
              case_number: caseNumber,
              case_title: caseTitle,
              court_id: courtId,
              category_id: categoryId,
              item_number: itemNumber,
              _token: csrfToken
          }
      };

      sendRequest(requestData, "{{ url('display-board') }}");
  });

  $('#btn-reset').click(function () { 
    location.reload();
			$('#formID')[0].reset();
      $("#case_numbers").select2({
        multiple: true
      });
      $("#display-board-table").hide(); 
      $("#header-buttons").hide(); 
      $("#footer-buttons").hide(); 
			oTable.ajax.reload();
  });

  $('input[name="checkall"]').click(function(){
        var isChecked = this.checked;		
        $('input[name="case_id"]').each(function(){
            this.checked = isChecked;
        });
    });

    oTable.on("row-reorder", function (e, diff, edit) {
    var positionData = [];
    
    var first = oTable.row(diff[0].node).data();
    var last = oTable.row(diff[diff.length-2].node).data();
   

    for ( var i=0, ien=diff.length ; i<ien ; i++ ) {
      var rowData = oTable.row( diff[i].node ).data();
     
        positionData.push({
          id: rowData.id,
          sort_order: diff[i].newPosition + 1
        });
    }  

    if (positionData.length > 0) {
          $.ajax({
            url: "{{ url('display-board/reorder') }}",
            method: "POST",
            data: {
              reorder: positionData,
              dir: first.sort_order > last.sort_order ? 'up' : 'down',
              _token: "{{ csrf_token() }}"
            },
            success: function(response) {
              success(response);
              $("#btn-next").attr("disabled", "disabled");
              setNextMessage();
              oTable.ajax.reload();
            }
          });
        }
    
  });

  $(".btn-status").click(function() {
    var status = $(this).data("status");
    var checkedItems = $('input[name="case_id"]:checked').length;
    
    @if (! is_role(auth()->user()->role_id, config('role.court-user')))
    if (checkedItems === 0) {
      alert("Please select at least one item");
      return;
    }
    @endif
    
    var cases = [];
    $('input[name="case_id"]:checked').each(function() {
      cases.push($(this).val());
    });
    
    $.ajax({
      url: "{{ url('display-board/update-case-status') }}",
      method: "POST",
      data: {
        status_id: status,
        cases: cases,
        _token: "{{ csrf_token() }}"
      },
      success: function(response) {
        if (response.status) {
          success(response);
          oTable.ajax.reload();
          isMultipleInSession();
          isDisplayStarted();
          isInSession();

        }
      },
      error: function (response) {
        alert(response.responseJSON.message);
        return;
      } 
    });
  });

  $("#start-display").click(function() {
    // var checkedItems = $('input[name="case_id"]:checked').length;
    // if (checkedItems === 0) {
    //   alert("Please select at least one item");
    //   return;
    // }
    // else {
    //   var cases = [];
    //   $('input[name="case_id"]:checked').each(function() {
    //     cases.push($(this).val());
		// 	});
      
      $.ajax({
        url: "{{ url('display-board/update-display') }}",
        method: "POST",
        data: {
          // is_display: 1,
          // cases: cases,
          _token: "{{ csrf_token() }}"
        },
        success: function(response) {
          if (response.status) {
            success(response);
            oTable.ajax.reload();
            isMultipleInSession();
            isDisplayStarted();
            isInSession();

          }
        } 
      });
    // }
  });


  $("#btn-next").click(function() {
    var calendar = $("#calendar").val();
    $.ajax({
      url: "{{ url('display-board/next') }}",
      method: "POST",
      data: {
        calendar: calendar,
        _token: "{{ csrf_token() }}"
      },
      success: function (response) {
        success(response);
        oTable.ajax.reload();
        isMultipleInSession();
        isDisplayStarted();
        isInSession();

      }
    });
  });

  $("#stop-display").click(function() {
    var checkedItems = $('input[name="case_id"]:checked').length;
    
    if (checkedItems === 0) {
      alert("Please select at least one item");
      return;
    }
    
    var cases = [];
    $('input[name="case_id"]:checked').each(function() {
      cases.push($(this).val());
    });
    
    $.ajax({
      url: "{{ url('display-board/stop-display') }}",
      method: "POST",
      data: {
        // is_display: 0,
        cases: cases,
        _token: "{{ csrf_token() }}"
      },
      success: function(response) {
        if (response.status) {
          success(response);
          oTable.ajax.reload();
          isMultipleInSession();
          isDisplayStarted();
          isInSession();

        }
      } 
    });

  });

  $("#sort-by-item-number").click(function() {
    $.ajax({
      url: "{{ url('display-board/sort-by-item-number') }}",
      method: "GET",
      success: function(response) {
        if (response.status) {
            success(response);
            $("#btn-next").prop("class", "btn btn-success");
            $("#btn-next").removeAttr("disabled");
            $("#next-msg").html("");
            oTable.ajax.reload();
          }
      }
    })
  });

  $("#restart-session").click(function() {
    $.ajax({
      url: "{{ url('display-board/restart-session') }}",
      method: "GET",
      success: function(response) {
        if (response.status) {
            success(response);
            oTable.ajax.reload();
            isMultipleInSession();
            isDisplayStarted();
            isInSession();

          }
      }
    })
  });

  $(document).on("click", "#start-session-button", function() {
    var id = $(this).data('id');
    $.ajax({
      url: "{{ url('display-board/start-session') }}",
      method: "POST",
      data: {
        _token: "{{ csrf_token() }}",
        id: id
      },
      success: function(response) {
        if (response.status) {
            success(response);
            if (response.data.is_reordered) {
              $("#btn-next").attr("disabled", "disabled");
              setNextMessage();
            }
            oTable.ajax.reload();
            isMultipleInSession();
            isDisplayStarted();
            isInSession();
          }
      },
      error: function (response) {
        alert(response.responseJSON.message);
        return;
      }
    })
  });

  $(document).on("click", "#stop-session-button", function() {
    var id = $(this).data('id');
    $.ajax({
      url: "{{ url('display-board/stop-session') }}",
      method: "POST",
      data: {
        _token: "{{ csrf_token() }}",
        id: id
      },
      success: function(response) {
        if (response.status) {
            success(response);
            oTable.ajax.reload();
            isMultipleInSession();
            isDisplayStarted();
            isInSession();

          }
      }
    })
  });


  $("#clear-list").click(function() {
    var checkedItems = $('input[name="case_id"]:checked').length;
    if (checkedItems === 0) {
      alert("Please select at least one item");
      return;
    }
    else {
      var cases = [];
      $('input[name="case_id"]:checked').each(function() {
        cases.push($(this).val());
			});
      
      $.ajax({
        url: "{{ url('display-board/clear-list') }}",
        method: "POST",
        data: {
          cases: cases,
          _token: "{{ csrf_token() }}"
        },
        success: function(response) {
          if (response.status) {
            success(response);
            oTable.ajax.reload();
            isMultipleInSession();
            isDisplayStarted();
            isInSession();

          }
        } 
      });
    }
  });

  $("#case_type_id").change(function() {
    var caseTypeId = $(this).val();
    var calendar = $("#calendar").val();
    $.ajax({
      url: "{{ url('display-board/get-case-numbers') }}",
      method: "GET",
      data: {
        case_type_id: caseTypeId,
        calendar: calendar
      },
      success: function(response) {
        if (response.status) {
          var caseNumbers = response.data.caseNumbers;
          var options = '<option value="">Select</option>';
          for (idx in caseNumbers) {
            options += `<option value="${caseNumbers[idx]}">${caseNumbers[idx]}</option>`;
          }
          $("#case_number").html(options); 
        }
      }
    });
  });

  $("#case_number").change(function() {
    var caseNumber = $(this).val();
    $.ajax({
      url: "{{ url('display-board/get-case-title') }}",
      method: "GET",
      data: {
        case_number: caseNumber,
      },
      success: function (response) {
        $("#case_title").html(`<option value="${response.data.caseTitle}">${response.data.caseTitle}</option>`);
        $("#case-title-dropdown").show();
      }
    });
  });

  $("#calendar").daterangepicker({
    maxDate: new Date(),
    singleDatePicker: true,
    locale: {
      format: 'DD/MM/YYYY'
    }
  });

  function showButtons() {
    oTable.ajax.reload(function() {
        if (oTable.rows().count() > 0) {
          $("#header-buttons").show(); 
          $("#footer-buttons").show(); 
        }
      }); 
    }

  function isMultipleInSession() {
    $.ajax({
      url: "{{ url('display-board/is-multiple-insession') }}",
      method: "GET",
      success: function(response) {
        if (response.data.is_multiple_insession) {
          $("#btn-next").attr("disabled", "disabled");
          setNextMessage();
        } 
      }
    });
  }

  function setNextMessage() {
    $("#next-msg").html("Note: Next button is disabled due to sequence is break. Please click above button Start By Sequence to enable it again.");
  }

  function isDisplayStarted() {
    $.ajax({
      url: "{{ url('display-board/is-display-started') }}",
      method: "POST",
      data: { _token: "{{ csrf_token() }}"},
      success: function (response) {
        if (response.data.is_display_started) {
          $("#start-display").attr("disabled", "disabled");
        }
      }
    })
  }


  function isInSession() {
    $.ajax({
      url: "{{ url('display-board/is-insession') }}",
      method: "POST",
      data: { _token: "{{ csrf_token() }}"},
      success: function (response) {
        if (response.data.is_insession) {
          $("#restart-session").attr("disabled", "disabled");
        } else {
          $("#restart-session").removeAttr("disabled");
        }
      }
    })
  }

  showButtons();
  isMultipleInSession();
  isDisplayStarted();
  isInSession();

});
</script>

@endsection
           