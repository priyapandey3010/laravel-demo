@extends('components.admin.layout')

@section('page-content')

<div class="container-fluid">
  @include('components.admin.page-header', ['pageTitle' => __('message.court_master')])
  @include('components.admin.alert-manager')
  <div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">{{ __('message.court_list') }}
          <span class="float-right">
          @if (acl('court-create'))
            <a href="{{ url('courts/create') }}" class="btn btn-info btn-sm">
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
                        <th>{{ __('message.bench_name') }}</th>
                        <th>{{ __('message.court_number') }}</th>
                        <th>{{ __('message.court_name') }}</th>
                        @if (acl('court-update') || acl('court-delete'))
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
    url: "{{ url('courts/datalist') }}",
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
            return row.bench_name;
        }
      },
      {
        "orderable": true,
        "render": function(data, type, row) {
            return row.court_number;
        }
      },
      {
        "orderable": true,
        "render": function(data, type, row) {
            return row.court_name;
        }
      },
      @if (acl('court-update') || acl('court-delete'))
      {
        "orderable": false,
        "render": function (data, type, row) {
            var action = '';
            @if (acl('court-update'))
            action += buttonEdit("{{ url('courts') }}", row.id);
            @endif
            @if (acl('court-delete'))
            action += '&nbsp;';
            action += buttonDelete("{{ url('courts') }}", row.id);
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
           