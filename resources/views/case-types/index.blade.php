@extends('components.admin.layout')

@section('page-content')

<div class="container-fluid">
  @include('components.admin.page-header', ['pageTitle' => __('message.case_type_master')])
  @include('components.admin.alert-manager')
  <div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">{{ __('message.case_type_list') }}
          <span class="float-right">
            @if (acl('case-master-create'))
            <a href="{{ url('case-types/create') }}" class="btn btn-info btn-sm">
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
                        @if (acl('case-master-update') || acl('case-master-delete'))
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
    url: "{{ url('case-types/datalist') }}",
    columns: [
      {
        "orderable": true,
        "render": function(data, type, full, meta) {
          return serialNumber("#dataTable", meta.row);
        }
      },
      {
        "orderable": true,
        "render": function(data, type, row) {
            return row.case_type;
        }
      },
      @if (acl('case-master-update') || acl('case-master-delete'))
      {
        "orderable": false,
        "render": function (data, type, row) {
            var action = '';
            @if (acl('case-master-update'))
            action += buttonEdit("{{ url('case-types') }}", row.id);
            @endif
            @if (acl('case-master-delete'))
            action += '&nbsp;';
            action += buttonDelete("{{ url('case-types') }}", row.id);
            @endif
            return action;
        }
      }
      @endif
    ]
  });
});
</script>

@endsection
           