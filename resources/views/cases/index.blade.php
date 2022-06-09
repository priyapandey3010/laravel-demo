@extends('components.admin.layout')

@if (acl('manage-cases-update'))
@section('css')

tr:hover {
    cursor:move;
}

@endsection
@endif

@section('page-content')

<div class="container-fluid">
  @include('components.admin.page-header', ['pageTitle' => __('message.manage_cases')])
  @include('components.admin.alert-manager')
  <div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">{{ __('message.manage_case_list') }}
          <span class="float-right">
          @if (acl('manage-cases-create'))
            <a href="{{ url('case/create') }}" class="btn btn-info btn-sm">
                <i class="fas fa-plus"></i> {{ __('message.create') }}
            </a>
            @endif
          </span>
        </h6>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th>{{ __('message.sno') }}</th>
                        <th>{{ __('message.case_type') }}</th>
                        <!-- <th>{{ __('Category') }}</th> -->
                        <th>{{ __('message.case_title') }}</th>
                        <!-- <th>{{ __('message.item_number') }}</th> -->
                        <th>{{ __('message.case_number') }}</th>
                        <!-- <th>{{ __('message.status') }}</th> -->
                        @if (acl('manage-cases-update') || acl('manage-cases-delete'))
                        <th>{{ __('message.action') }}</th>
                        @endif
                    </tr>
                </thead>
            </table>
        </div>
    </div>
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
    url: "{{ url('case/datalist') }}",
    @if (acl('manage-cases-update'))
    rowReorder: true,
    @endif
    columns: [
      {
        "orderable": false,
        "render": function(data, type, full, meta) {
          return serialNumber("#dataTable", meta.row);
        }
      },
      {
        "orderable": false,
        "render": function(data, type, row) {
            return row.case_type;
        }
      },
      // {
      //   "orderable": false,
      //   "render": function(data, type, row) {
      //       return row.category_name;
      //   }
      // },
      {
        "orderable": false,
        "render": function(data, type, row) {
            return row.case_title;
        }
      },
      // {
      //   "orderable": false,
      //   "render": function(data, type, row) {
      //       return row.item_number;
      //   }
      // },
      {
        "orderable": false,
        "render": function(data, type, row) {
            return row.case_number;
        }
      },
      // {
      //   "orderable": false,
      //   "render": function(data, type, row) {
      //       return `<span style="color:${row.colour_code}">${row.status}</span>`;
      //   }
      // },
      @if (acl('manage-cases-update') || acl('manage-cases-delete'))
      {
        "orderable": false,
        "render": function (data, type, row) {
            var action = '';
            @if (acl('manage-cases-update'))
            action += buttonEdit("{{ url('case') }}", row.id);
            @endif
            @if (acl('manage-cases-delete'))
            action += '&nbsp;';
            action += buttonDelete("{{ url('case') }}", row.id);
            @endif
            return action;
        }
      }
      @endif
    ]
  });

  
  oTable.on("row-reorder", function (e, diff, edit) {
    var positionData = [];
    for ( var i=0, ien=diff.length ; i<ien ; i++ ) {
      var rowData = oTable.row( diff[i].node ).data();
      
        positionData.push({
          id: rowData.id,
          sort_order: diff[i].newPosition + 1
        });
    }  

    if (positionData.length > 0) {
          $.ajax({
            url: "{{ url('case/reorder') }}",
            method: "POST",
            data: {
              reorder: positionData,
              _token: "{{ csrf_token() }}"
            },
            success: function(response) {
              success(response);
              oTable.ajax.reload();
            }
          });
        }
    
  });
  
});
</script>

@endsection
           